# Cratory — Product Requirements Document (PRD)
### Invoice & Inventory Management SaaS

**Version:** 1.0 (MVP scope)
**Stack:** Laravel 12 · Livewire 3 + Alpine.js · Tailwind CSS · MySQL (primary) · SQLite (backup/export)
**Design mandate:** Mobile-first, production-grade UI/UX (not a default-Bootstrap-admin look)

---

## 1. Product Summary

Cratory is a multi-organization SaaS for small and medium businesses to manage **sales & service invoicing, purchases, receipts/payments, and inventory** from one dashboard. A single top-level user ("Commander") can own and switch between multiple organizations. Each organization is a fully isolated tenant (its own ledgers, invoices, stock, and team).

Build for scale from day one (clean multi-tenant data model, service-layer architecture, queue-ready), but only implement the feature set in Section 5 for this phase.

---

## 2. Tech Stack & Architecture Decisions

| Layer | Choice | Notes |
|---|---|---|
| Backend framework | Laravel 12 | Use latest conventions (typed properties, enums, `casts()` method, invokable controllers where sensible) |
| Frontend rendering | Blade + Livewire 3 + Alpine.js | SPA-like feel without a separate JS build pipeline; use `wire:navigate` for instant page transitions |
| CSS | Tailwind CSS (v4) | Custom design tokens (Section 6), no default Tailwind UI look-alikes |
| Primary DB | MySQL 8 | All transactional data |
| Secondary DB | SQLite | Used for automated nightly export/backup snapshots and as an offline-portable copy per organization (see Section 9.4) — not used for live writes |
| Auth | Laravel Breeze/Fortify (Livewire stack) + Spatie `laravel-permission` | Role & permission management per organization |
| Multi-tenancy model | Single database, `organization_id` scoping via Global Scope + `BelongsToOrganization` trait | Simpler ops than DB-per-tenant; revisit only if a client needs hard data isolation later |
| PDF generation | `barryvdh/laravel-dompdf` or `spatie/laravel-pdf` (Browsershot) | Needed for invoice templates — prefer Browsershot for pixel-accurate Tailwind-styled PDFs |
| Charts | ApexCharts (via CDN, wired through Alpine) | For dashboard revenue/receivable charts |
| Numbering | Custom `DocumentNumberGenerator` service | Per-organization, per-document-type sequences (see Section 8) |
| File storage | Laravel filesystem, `local`/`s3` driver switchable | Logos, invoice templates, attachments |
| Queue | Database or Redis driver | For PDF generation, backups, notifications |

---

## 3. User Roles

| Role | Scope | Description |
|---|---|---|
| **Commander** | Global (cross-organization) | Creates organizations, is automatically Super Admin of every org they create, can create additional organizations, switches between orgs via an org-switcher. Only Commanders can create new organizations. |
| **Org Admin** | Single organization | Full access within one organization (invited by Commander); cannot create new organizations. |
| **Accountant** | Single organization | Access to invoices, purchases, receipts, payment vouchers, reports. No user/org management. |
| **Sales/Inventory Staff** | Single organization | Create sales/service invoices, manage stock, view own records only (configurable). |

Use Spatie roles/permissions scoped per organization (a user's role is stored on the `organization_user` pivot, not globally, except the `is_commander` flag which is global).

---

## 4. Core Data Model (MVP)

### 4.1 Organizations & Users

**organizations**
| Field | Type | Notes |
|---|---|---|
| id | ulid/bigint | |
| name | string | |
| type | enum | `proprietorship`, `partnership`, `pvt_ltd`, `llp`, `huf`, `individual`, `other` |
| business_category | enum | `product`, `service`, `both` — drives whether Purchase module is shown |
| pan_number | string, nullable | validated format |
| gst_number | string, nullable | validated GSTIN format when present |
| address_line_1 / 2 | string | |
| city / state / pincode / country | string | |
| phone / email | string, nullable | |
| logo_path | string, nullable | |
| invoice_prefix | string | e.g. `CRT` |
| financial_year_start_month | tinyint | default 4 (April) for IN businesses |
| currency | string | default `INR` |
| created_by (commander user_id) | FK | |
| is_active | boolean | |
| timestamps, soft deletes | | |

**users**
id, name, email, phone, password, is_commander (bool), avatar_path, current_organization_id (last active org, for session default), timestamps.

**organization_user** (pivot — membership + role)
organization_id, user_id, role (`commander`, `org_admin`, `accountant`, `staff`), is_default_org (bool), status (`active`,`invited`,`suspended`), timestamps.

> **Onboarding rule:** On first login with zero organizations, force the "Create Organization" wizard (Section 5.1) before any other screen is reachable. All subsequent org creations go through the org-switcher's "+ New Organization" action, available only to `is_commander = true` users.

### 4.2 Contacts (Customers & Vendors)

**contacts**
| Field | Notes |
|---|---|
| id, organization_id | scoped |
| type | enum: `customer`, `vendor`, `both` |
| name, display_name | |
| gst_number, pan_number | nullable |
| billing_address (line1,line2,city,state,pincode,country) | |
| shipping_address | nullable, same shape |
| phone, email | |
| opening_balance | decimal(14,2), signed (+receivable / -payable) |
| is_active | |
| timestamps |

### 4.3 Inventory

**product_categories** — id, organization_id, name, parent_id (nullable, for sub-categories)

**products** (also represents "items" sold; `item_type` distinguishes product vs service)
| Field | Notes |
|---|---|
| id, organization_id | |
| item_type | enum: `product`, `service` |
| category_id | FK nullable |
| name, sku (unique per org), description | |
| unit | enum/string: `pcs`, `box`, `kg`, `ltr`, `hr`, `service`, custom |
| hsn_code (products) / sac_code (services) | |
| purchase_price | decimal, nullable for services |
| selling_price | decimal |
| tax_rate_id | FK → `tax_rates` |
| opening_stock | decimal, products only |
| current_stock | decimal, computed & cached, products only |
| reorder_level | decimal, nullable, products only |
| image_path | nullable |
| is_active | |
| timestamps |

**stock_movements** (ledger — append-only, source of truth for `current_stock`)
id, organization_id, product_id, type (`purchase_in`, `sale_out`, `adjustment_in`, `adjustment_out`, `opening`), quantity, reference_type (morph: SaleInvoice/PurchaseInvoice/Adjustment), reference_id, balance_after, created_by, created_at.

**tax_rates**
id, organization_id, name (e.g. "GST 18%"), percentage, is_gst (bool, splits into CGST/SGST when intra-state vs IGST when inter-state — computed at invoice time, not stored on the rate).

### 4.4 Sales & Service Invoices

**invoices** (shared table for `sales` and `service` via `invoice_type`, plus template link)
| Field | Notes |
|---|---|
| id, organization_id | |
| invoice_type | enum: `sales`, `service` |
| invoice_number | generated, unique per org (Section 8) |
| contact_id | customer FK |
| invoice_date, due_date | |
| template_id | FK → `invoice_templates` |
| subtotal, discount_total, tax_total, round_off, grand_total | decimal(14,2) |
| amount_paid | decimal, denormalized & recalculated from receipts |
| balance_due | computed = grand_total − amount_paid |
| status | enum: `draft`, `sent`, `partial`, `paid`, `overdue`, `cancelled` |
| notes, terms_and_conditions | text, nullable |
| place_of_supply (state) | for CGST/SGST vs IGST logic |
| created_by | |
| timestamps, soft deletes |

**invoice_items**
id, invoice_id, product_id (nullable — allow free-text line for services not in catalog), description, quantity, unit, rate, discount_percent/discount_amount, tax_rate_id, tax_amount, line_total.

> **Business rule:** Confirming a `sales` invoice (not `service`) creates `stock_movements` (`sale_out`) and decrements `current_stock`. Service invoices never touch stock. Cancelling/voiding a sales invoice must reverse the stock movement.

### 4.5 Invoice Templates (customization)

**invoice_templates**
id, organization_id, name, is_default (bool), color_primary/color_secondary (hex), logo_position (`left`/`center`/`right`), show_fields (JSON: e.g. `{"hsn":true,"discount":true,"shipping_address":false}`), header_note, footer_note, font_choice.

Provide 3–4 starter templates (Classic, Modern, Minimal, Bold-color) seeded per new organization; user can duplicate and tweak. Rendering is done via a Blade view keyed by `template.slug`, styled with Tailwind, converted to PDF via Browsershot.

### 4.6 Purchases

**purchase_invoices** *(module hidden/disabled entirely when `organization.business_category === 'service'`)*
| Field | Notes |
|---|---|
| id, organization_id | |
| purchase_number | internal sequence |
| vendor_bill_number | vendor's own invoice number |
| contact_id | vendor FK |
| purchase_date, due_date | |
| subtotal, discount_total, tax_total, round_off, grand_total | |
| amount_paid, balance_due | |
| status | `draft`, `received`, `partial`, `paid`, `cancelled` |
| attachment_path | photo/PDF of vendor bill |
| timestamps, soft deletes |

**purchase_invoice_items** — mirrors `invoice_items`, tied to `purchase_invoice_id`; confirming creates `stock_movements` (`purchase_in`).

### 4.7 Receipts & Payment Vouchers

**receipts** (money received against sales/service invoices)
id, organization_id, receipt_number, contact_id, receipt_date, amount, payment_mode (`cash`,`bank_transfer`,`upi`,`cheque`,`card`,`other`), reference_number, notes, created_by, timestamps.

**receipt_allocations** (a receipt can settle multiple invoices, or an invoice can be settled by multiple receipts)
receipt_id, invoice_id, allocated_amount.

**payment_vouchers** (money paid against purchases)
id, organization_id, voucher_number, contact_id (vendor), voucher_date, amount, payment_mode, reference_number, notes, created_by, timestamps.

**payment_voucher_allocations**
payment_voucher_id, purchase_invoice_id, allocated_amount.

> **Business rule:** On saving a receipt/voucher, recalculate `amount_paid` and `status` on each allocated invoice/purchase inside a DB transaction; recompute dashboard cache keys (Section 5.2) asynchronously via a queued job.

---

## 5. Feature Requirements (MVP Scope)

### 5.1 Organization Onboarding Wizard
- Triggered automatically for a Commander with 0 organizations, and via "+ New Organization" afterward.
- Multi-step (mobile-first stepper, swipe/next between steps, progress indicator at top):
  1. **Business basics** — name, type, business category (product/service/both)
  2. **Tax details** — PAN (required), GSTIN (optional, validated format, auto-splits state code)
  3. **Address** — line1/2, city, state, pincode, country
  4. **Commander details** — confirm/edit name, phone, email, upload avatar (pre-filled from account if editing profile already exists)
  5. **Review & Create** — summary card, "Create Organization" CTA
- On submit: create `organization`, attach current user via `organization_user` with role `commander`, seed default `tax_rates` (0%, 5%, 12%, 18%, 28% for IN GST) and default `invoice_templates`, set as `current_organization_id`, redirect to Dashboard.
- Org-switcher component (visible in top nav / mobile bottom-sheet): lists all orgs the user belongs to, shows role badge, "+ New Organization" entry only if `is_commander`.

### 5.2 Dashboard
Mobile-first, card-first layout (grid collapses to stacked cards <768px):
- **KPI cards**: Total Sales (period-filterable: this month/quarter/year/custom), Total Purchases (**hidden if org is service-only**), Total Receivable, Total Payable/Due.
- **Revenue chart**: line/bar combo (Sales vs Collections) — monthly buckets, ApexCharts, swipeable on mobile.
- **Receivable aging widget**: buckets (0–15, 16–30, 31–60, 60+ days overdue).
- **Recent activity feed**: last 10 invoices/receipts/purchases, tappable to detail.
- **Low-stock alert card**: products at/under `reorder_level` (hidden if org is service-only).
- Cache KPI aggregates per organization (Redis/DB cache, 5–10 min TTL or invalidate on relevant writes) — do not run heavy aggregate queries on every page load.

### 5.3 Sales Invoice
- Create/edit form: contact picker (with "+ quick add contact" inline), invoice date/due date, dynamic line-item repeater (product picker with live stock display, quantity, rate auto-filled from product but editable, discount, tax auto-computed from product's tax rate and `place_of_supply` vs org state → CGST+SGST or IGST split shown separately), auto-computed subtotal/tax/round-off/grand total live as you type (Livewire reactive).
- Actions: Save as Draft, Save & Send (marks `sent`), Duplicate, Cancel/Void, Download PDF, Print, Share (WhatsApp/email link with PDF attached — MVP: generate shareable PDF link).
- Stock is decremented only when status moves out of `draft` (i.e., confirmed/sent).
- List view: filterable/searchable table (desktop) collapsing to card list (mobile) with status pills (color-coded), swipe actions on mobile (mark paid / view / delete draft).

### 5.4 Service Invoice
- Same UX as Sales Invoice but:
  - Line items pull from `item_type = service` catalog (or free-text line for one-off services)
  - No stock deduction, no stock display in item picker
  - Optional "duration/hours" field per line if useful for the org
- Shares the `invoices` table (`invoice_type = service`) and the same template/PDF engine.

### 5.5 Customizable Invoice Templates
- Template gallery screen: preview cards of available templates, "Set as Default", "Duplicate & Edit".
- Template editor: live-preview pane (mobile: preview toggled via tab, not split-screen) with controls for logo position, primary/secondary color pickers, toggle visibility of fields (HSN/SAC, discount column, shipping address, notes, bank details block), header/footer free text.
- PDF rendering must exactly match the live preview (single Blade partial reused for both).

### 5.6 Purchase Invoices *(hidden entirely for service-only organizations)*
- Similar CRUD to Sales Invoice but against vendor contacts.
- Attachment upload for vendor's physical/scanned bill (image or PDF, stored, thumbnail shown).
- Confirming increments stock (`purchase_in` movement) and updates `products.purchase_price` optionally (prompt: "Update product cost price?").

### 5.7 Receipts (Payment Received)
- "Record Payment" action available from an invoice detail screen (pre-fills contact & suggested amount = balance due) or standalone from Receipts list ("+ New Receipt" → pick contact → shows all their open invoices to allocate against, supports partial + multi-invoice allocation with a running "unallocated amount" indicator).
- Payment mode selector, reference number, date, notes.
- Auto-updates invoice status (`partial`/`paid`) and dashboard receivable totals.
- Generates a printable Receipt PDF/voucher for the customer.

### 5.8 Payment Vouchers (Payment Made)
- Mirrors Receipts but against `purchase_invoices` and vendor contacts.
- Same allocation UX, same PDF voucher generation.

### 5.9 Inventory Management
- **Product/Service catalog**: list (card grid on mobile w/ image, stock badge, price; table on desktop), search/filter by category/stock status, bulk import via CSV (nice-to-have, can stub for phase 2).
- **Product form**: name, category (with inline "+ add category"), SKU (auto-suggest/generate), unit, HSN/SAC, purchase price, selling price, tax rate, opening stock (only editable before any transaction exists), reorder level, image upload.
- **Stock ledger view** per product: chronological list of all `stock_movements` with running balance, filterable by date range and movement type.
- **Manual stock adjustment**: form to add `adjustment_in`/`adjustment_out` with a mandatory reason/notes field (for damage, correction, etc.).
- **Low stock report**: list of all products at/under reorder level, exportable.

---

## 6. UI/UX Requirements

- **Mobile-first, not mobile-adapted**: design every screen at 375px width first, then scale up to tablet/desktop breakpoints — not the reverse.
- **Navigation**: bottom tab bar on mobile (Dashboard, Invoices, Inventory, More) with a floating action button for "Create" (opens a bottom-sheet action menu: New Sales Invoice / Service Invoice / Purchase / Receipt / Voucher). Sidebar nav on desktop (≥1024px).
- **Design tokens**: define a Tailwind theme extension — a distinct primary/accent color pair (avoid default Tailwind `blue-600` SaaS-template look), a clear type scale, consistent 8px spacing rhythm, soft shadows/rounded-2xl cards, and a defined dark mode palette from the start.
- **Tables → Cards on mobile**: every data table must have a card-based equivalent below the `md` breakpoint; do not just shrink table font size.
- **Forms**: large tap targets (min 44px), sticky bottom action bar (Save/Cancel) on long forms on mobile, inline validation, optimistic UI via Livewire where safe.
- **Empty states & skeleton loaders**: every list screen needs a designed empty state (icon + CTA) and skeleton loaders for async loads — no blank flashes.
- **Status/semantic color coding**: consistent color meaning across the app for `draft` (gray), `sent` (blue), `partial` (amber), `paid`/`received` (green), `overdue`/`cancelled` (red).
- **Accessibility**: sufficient color contrast, focus states on all interactive elements, form labels (not placeholder-only).
- Reference: use the project's frontend-design skill/guidelines for exact spacing, color, and component tokens rather than generic Tailwind UI defaults.

---

## 7. Non-Functional Requirements

- **Multi-tenancy safety**: every query on tenant-scoped models must pass through the `organization_id` global scope; add automated tests asserting cross-org data leakage is impossible.
- **Authorization**: every controller/Livewire action must check the acting user's role within the *current* organization context (not just authentication).
- **Scalability**: keep heavy aggregate/reporting queries out of request cycle where possible (cache, or move to scheduled jobs writing to a `dashboard_snapshots` table).
- **Auditability**: soft deletes on financial documents (invoices, purchases, receipts, vouchers) — never hard-delete; add an `activity_log` (Spatie Activitylog) for create/update/void events on financial documents.
- **Numbering integrity**: invoice/purchase/receipt/voucher numbering must be gap-free and race-condition safe per organization (use DB transactions with row locking, not "max+1 in PHP").
- **Localization-ready**: currency/date formatting driven by organization settings, not hardcoded, even though MVP targets India (GST) specifically.
- **Testing**: Feature tests for the full lifecycle of each document type (create → confirm → stock/ledger effect → payment → status transition) and for multi-org data isolation.

---

## 8. Document Numbering Convention

Format: `{ORG_PREFIX}-{DOC_TYPE_CODE}-{FY}-{SEQUENCE}`
Example: `CRT-INV-2526-0001`, `CRT-PUR-2526-0001`, `CRT-RCT-2526-0001`, `CRT-PV-2526-0001`

- `DOC_TYPE_CODE`: `INV` (sales), `SRV` (service invoice) or shared `INV` with type shown separately — decide based on client preference, default to separate series per `invoice_type`.
- `FY` resets sequence at organization's financial-year-start month.
- Implement as a `DocumentSequence` service class + DB table (`organization_id`, `document_type`, `financial_year`, `last_number`) updated inside a locked transaction.

---

## 9. Assumptions & Open Items to Confirm Before Build

1. **SQLite backup role**: interpreted as scheduled export snapshots (per-organization or full-DB) written to SQLite files for portability/offline backup — not used for live reads/writes. Confirm this matches intent, or clarify if SQLite should serve a different purpose (e.g., local/offline-first mode).
2. Multi-currency support is out of scope for MVP (single currency per organization) — confirm.
3. E-invoicing / GST portal API integration (IRN/e-way bill) is **not** in this phase — flag as a likely Phase 2 requirement given GST fields are being collected now.
4. Whether customers/vendors need self-service portals (view/pay invoices online) — assumed **out of scope** for MVP; current scope is business-user-facing only.
5. Email/WhatsApp delivery of invoices/receipts: MVP assumed to generate a shareable PDF/link; confirm if actual email sending (queued) is required now vs. phase 2.

---

## 10. Suggested Build Order (for incremental delivery)

1. Auth + Organization onboarding wizard + org-switcher + roles/permissions scaffold
2. Contacts (customers/vendors) module
3. Inventory: categories, products/services, stock ledger, adjustments
4. Tax rates + Document numbering service
5. Sales Invoice + Service Invoice (CRUD, stock effect, status lifecycle)
6. Invoice Templates + PDF generation
7. Purchase Invoices (with conditional visibility for service-only orgs)
8. Receipts + allocations, Payment Vouchers + allocations
9. Dashboard (KPIs, charts, aging, low-stock)
10. Polish pass: empty states, skeletons, dark mode, mobile QA across all screens
11. Backup/export job (MySQL → SQLite snapshot)

---

## 11. Definition of Done (MVP)

- A Commander can sign up, create an organization through the wizard, and land on a populated (empty-state) dashboard.
- A Commander can create a second organization and switch between them, with data fully isolated.
- Sales and service invoices can be created, sent, paid (fully/partially via receipts), and downloaded/printed as PDF using at least 2 selectable templates.
- Purchase invoices can be recorded and paid via payment vouchers (for product/both-type orgs); module is hidden for service-only orgs.
- Product stock correctly increments on purchase, decrements on sale, and reflects manual adjustments, all visible in a per-product ledger.
- Dashboard KPIs and revenue chart reflect real data and update after relevant transactions.
- All screens are fully usable and visually polished at 375px width, not just functional.
