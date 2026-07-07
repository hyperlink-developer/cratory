# Cratory — User Guide

> A complete, step-by-step guide to help you get started with Cratory and master every feature. No technical knowledge required!

---

## Table of Contents

1. [Getting Started](#1-getting-started)
2. [Creating Your Organization](#2-creating-your-organization)
3. [Navigating the App](#3-navigating-the-app)
4. [Managing Contacts (Customers & Vendors)](#4-managing-contacts-customers--vendors)
5. [Products & Inventory](#5-products--inventory)
6. [Creating Sales Invoices](#6-creating-sales-invoices)
7. [Downloading & Printing Invoices (PDF)](#7-downloading--printing-invoices-pdf)
8. [Recording Purchases](#8-recording-purchases)
9. [Receipts — Recording Customer Payments](#9-receipts--recording-customer-payments)
10. [Payment Vouchers — Recording Vendor Payments](#10-payment-vouchers--recording-vendor-payments)
11. [Reports & Analytics](#11-reports--analytics)
12. [Invoice Templates](#12-invoice-templates)
13. [Tax Rates](#13-tax-rates)
14. [User Management & Roles](#14-user-management--roles)
15. [Profile & Account Settings](#15-profile--account-settings)
16. [Switching Between Organizations](#16-switching-between-organizations)
17. [Using Cratory on Mobile](#17-using-cratory-on-mobile)
18. [Getting Help](#18-getting-help)

---

## 1. Getting Started

### Creating Your Account

1. Visit the Cratory website and click the **"Get Started Free"** or **"Sign Up"** button.
2. Fill in your **Name**, **Email Address**, and choose a strong **Password**.
3. Click **Register** to create your account.
4. You will be automatically logged in and redirected to the **Organization Setup Wizard**.

---

## 2. Creating Your Organization

After signing up, the first thing you need to do is set up your business (called an "Organization" in Cratory).

### The Setup Wizard

The onboarding wizard walks you through everything in simple steps:

1. **Organization Name** — Enter your business name (e.g., "Sunshine Traders").
2. **Business Type** — Select your type of business.
3. **GST Registration** — Choose whether your business is GST registered. If yes, enter your GSTIN number.
4. **Composition Scheme** — If you're a small taxpayer under the Composition Scheme, toggle this on. The app will automatically adjust tax calculations for you.
5. **Default Currency** — Pick the currency your business operates in (e.g., INR, USD, etc.).
6. **Logo** — Upload your business logo. This will appear on all your invoices and receipts.

Once you complete the wizard and click **Finish**, your organization is ready to use! You will be assigned as the **Org Admin**, giving you full control.

> **Tip:** You can create multiple organizations from the same account if you manage more than one business.

---

## 3. Navigating the App

### On Desktop (Laptop / PC)

- A **sidebar** on the left side shows all the main sections:
  - **Dashboard** — Your business overview at a glance.
  - **Invoices** — Create and manage sales invoices.
  - **Purchases** — Log bills from your vendors/suppliers.
  - **Contacts** — Your customer and vendor directory.
  - **Inventory** — Products, services, and stock levels.
  - **Receipts** — Record payments received from customers.
  - **Vouchers** — Record payments made to vendors.
  - **Reports** — Sales reports, profit & loss, balance sheet, etc.
  - **Settings** — Tax rates, invoice templates, user management.

### On Mobile (Phone / Tablet)

- The sidebar transforms into a **bottom navigation bar** with key tabs.
- Tap the **menu icon** (☰) to access additional sections.
- A **floating action button** (+) on the bottom right lets you quickly create new invoices, contacts, or products.

---

## 4. Managing Contacts (Customers & Vendors)

Contacts are the people and businesses you deal with. Cratory separates them into **Customers** (who buy from you) and **Vendors** (who supply to you).

### Adding a New Contact

1. Go to **Contacts** from the sidebar.
2. Click the **"+ New Contact"** button (or tap the floating + button on mobile).
3. Fill in the details:
   - **Name** — Full name or business name.
   - **Type** — Select **Customer** or **Vendor** (or both).
   - **Email** & **Phone** — For communication.
   - **Address** — Their billing/shipping address.
   - **GSTIN** — If they are GST registered (optional).
4. Click **Save**.

### Quick Add from Invoice

When creating an invoice, you can **add a new customer on the fly** without leaving the invoice form — just click "Add New" next to the customer dropdown.

### Editing & Deleting Contacts

- From the **Contacts** list, click on any contact to edit their details.
- Use the **Delete** button to remove a contact (this won't delete associated invoices).

---

## 5. Products & Inventory

### Adding a Product or Service

1. Go to **Inventory** from the sidebar.
2. Click **"+ New Product"**.
3. Fill in:
   - **Name** — What you sell (e.g., "Laptop Bag" or "Web Design Service").
   - **Type** — Choose **Product** (physical good) or **Service**.
   - **Selling Price** — Your default selling price.
   - **Purchase Price** — What you typically pay for it.
   - **HSN / SAC Code** — Tax classification code (optional but recommended for compliance).
   - **Opening Stock** — How many units you currently have (for Products only).
   - **Unit** — Pieces, kg, hours, etc.
4. Click **Save**.

### How Inventory Tracking Works

Cratory **automatically** updates your stock levels:

| Action | Effect on Stock |
|---|---|
| Create a **Sales Invoice** | Stock **decreases** |
| Create a **Purchase Bill** | Stock **increases** |

You don't need to manually adjust inventory — it all happens in the background!

### Viewing Stock Levels

On the **Inventory** page, you can see the current stock quantity for each product at a glance.

---

## 6. Creating Sales Invoices

This is the core feature of Cratory — creating professional invoices for your customers.

### Step-by-Step: Create a New Invoice

1. Go to **Invoices** from the sidebar.
2. Click **"+ New Invoice"**.
3. **Select a Customer** — Pick from your existing contacts, or click "Add New" to create one.
4. **Invoice Type:**
   - **Credit Invoice** — The customer will pay later (standard). You can set a due date.
   - **Cash Invoice** — For immediate/point-of-sale payments. The system will automatically create a receipt and mark it as fully paid.
5. **Add Line Items:**
   - Click **"Add Item"** to add products or services.
   - Select a product from your inventory (prices auto-fill).
   - Adjust the **Quantity**, **Price**, or **Discount** per item.
   - Tax is calculated automatically based on your settings.
6. **Additional Fields:**
   - **Invoice Date** — When the invoice is issued.
   - **Due Date** — When payment is expected (for Credit invoices).
   - **Notes** — Any additional information for the customer.
   - **Payment Info** — Bank details or UPI for payment.
7. Click **Save** to save as Draft, or **Send** to finalize.

### Invoice Statuses Explained

| Status | Meaning |
|---|---|
| **Draft** | Saved but not yet sent. You can still edit it. |
| **Sent** | Finalized and sent to the customer. Inventory is deducted. |
| **Partial** | Customer has paid some, but not all. |
| **Paid** | Fully paid — no balance remaining. |
| **Overdue** | Past the due date and still unpaid. |
| **Cancelled** | Voided — no longer active. |

---

## 7. Downloading & Printing Invoices (PDF)

Cratory generates beautiful, professional PDF invoices that you can download and print or share digitally.

### How to Download a PDF

1. Go to **Invoices** and find the invoice you want.
2. Click on the invoice to open it.
3. Click the **"Download PDF"** button (or the PDF icon).
4. The PDF will be generated and downloaded to your device.

### How to Print

1. Download the PDF (see above).
2. Open the downloaded file.
3. Press **Ctrl + P** (Windows) or **Cmd + P** (Mac) to print.
4. Select your printer and click **Print**.

### Choosing Invoice Templates

Cratory comes with multiple professionally designed invoice templates. Go to **Settings > Invoice Templates** to preview and select your preferred design:

- **Standard** — Clean and professional.
- **Modern** — Sleek with accent colors.
- **Minimal** — Simple and elegant.
- **Elegant** — Refined with premium styling.

Your selected template will be used for all new PDF invoices.

---

## 8. Recording Purchases

When you buy goods or services from vendors/suppliers, record them as **Purchases**.

### Creating a Purchase Bill

1. Go to **Purchases** from the sidebar.
2. Click **"+ New Purchase"**.
3. **Select a Vendor** — Pick from your contacts.
4. **Add Items** — Select products you're purchasing and enter quantities and prices.
5. **Purchase Type:**
   - **Credit Purchase** — You'll pay the vendor later.
   - **Cash Purchase** — Paid immediately. A Payment Voucher is auto-created.
6. Click **Save**.

### Effect on Inventory

When a purchase is finalized, the stock for each product in the bill is **automatically increased**.

---

## 9. Receipts — Recording Customer Payments

When a customer pays you for an outstanding (credit) invoice, you record it as a **Receipt**.

### Creating a Receipt

1. Go to **Receipts** from the sidebar (under Finance).
2. Click **"+ New Receipt"**.
3. **Select the Customer** who is making the payment.
4. **Enter the Amount Received**.
5. **Allocate the Payment:**
   - The system shows all unpaid invoices for that customer.
   - Enter how much of the payment goes towards each invoice.
   - Example: Customer pays ₹10,000. You allocate ₹6,000 to Invoice #101 and ₹4,000 to Invoice #102.
6. Click **Save**.

The invoices will automatically update their status to **Partial** or **Paid** based on how much was allocated.

---

## 10. Payment Vouchers — Recording Vendor Payments

When you pay a vendor for an outstanding purchase bill, record it as a **Payment Voucher**.

### Creating a Payment Voucher

1. Go to **Vouchers** from the sidebar (under Finance).
2. Click **"+ New Voucher"**.
3. **Select the Vendor** you are paying.
4. **Enter the Amount Paid**.
5. **Allocate** the payment to one or more open purchase bills.
6. Click **Save**.

The purchase bills will automatically update their status.

---

## 11. Reports & Analytics

Cratory provides powerful, real-time financial reports to help you understand your business performance.

### Available Reports

| Report | What It Shows |
|---|---|
| **Sales Report** | Breakdown of all sales invoices for a selected date range. |
| **Purchase Report** | Breakdown of all purchase bills for a selected date range. |
| **Profit & Loss** | Total Revenue minus Expenses = your Net Profit or Loss. |
| **Trial Balance** | A list of all account balances to verify debits = credits. |
| **Balance Sheet** | A snapshot of your Assets, Liabilities, and Equity at a point in time. |

### How to View a Report

1. Go to **Reports** from the sidebar.
2. Select the report you want (e.g., Sales Report).
3. Choose the **date range** (e.g., This Month, Last Quarter, Custom Range).
4. The report will generate instantly on screen.

### Exporting Reports

Each report can be exported as:
- **PDF** — For printing or sharing.
- **CSV** — For opening in Excel or Google Sheets for further analysis.

Just click the **"Export PDF"** or **"Export CSV"** button on any report page.

---

## 12. Invoice Templates

Customize how your invoices look when downloaded as PDFs.

1. Go to **Settings > Invoice Templates**.
2. Browse through the available templates:
   - **Standard** — Traditional business invoice layout.
   - **Modern** — Contemporary design with color accents.
   - **Minimal** — Clean, whitespace-focused design.
   - **Elegant** — Premium, refined styling.
3. Click **"Use Template"** to set it as your default.

All future invoices will use this template when generating PDFs.

---

## 13. Tax Rates

Set up the tax rates applicable to your business.

1. Go to **Settings > Tax Rates**.
2. You'll see default tax rates (e.g., GST 5%, 12%, 18%, 28%).
3. You can **add custom tax rates** or **edit** existing ones.
4. When creating invoices or purchases, these tax rates will appear in the dropdown for easy selection.

> **Note:** If your organization is under the Composition Scheme, the tax fields on invoices will be adjusted automatically.

---

## 14. User Management & Roles

If you work with a team, you can invite other users to your organization and assign them specific roles.

### How to Add a Team Member

1. Go to **Settings > User Management**.
2. Click **"+ Add User"**.
3. Enter their **Name**, **Email**, and **Password**.
4. Assign a **Role**:

| Role | What They Can Do |
|---|---|
| **Org Admin** | Full access to everything within the organization. Can manage users, settings, and all data. |
| **Accountant** | Access to invoices, purchases, receipts, vouchers, and reports. Cannot manage users or settings. |
| **Staff** | Limited access. Can create invoices and manage contacts. Cannot access reports or settings. |

5. Click **Save**. The user can now log in with their email and password.

### Editing & Removing Users

- From the **User Management** page, you can change a user's role or remove them from the organization entirely.

> **Note:** The person who creates the organization is automatically assigned the **Org Admin** role.

---

## 15. Profile & Account Settings

### Updating Your Profile

1. Click your **avatar/name** in the top right corner of the sidebar.
2. Select **Profile**.
3. You can update your:
   - **Name**
   - **Email Address**
   - **Password**
4. Click **Save** to apply changes.

---

## 16. Switching Between Organizations

If you manage multiple businesses, you can switch between them without logging out.

1. Click your **avatar/name** in the sidebar.
2. Click **"Switch Organization"**.
3. Select the organization you want to work with.
4. The entire app will reload with data specific to that organization.

> Each organization is completely separate — invoices, contacts, inventory, and finances are all isolated.

---

## 17. Using Cratory on Mobile

Cratory is designed as a **mobile-first** application, meaning it works beautifully on phones and tablets.

### Key Mobile Features

- **Bottom Navigation Bar** — Quick access to Dashboard, Invoices, Contacts, Inventory, and More.
- **Floating Action Button (+)** — Tap the round button at the bottom right to quickly create a new invoice, contact, or product.
- **Responsive Tables** — Data tables automatically transform into easy-to-read stacked cards on small screens.
- **Touch-Friendly** — All buttons and inputs have large tap targets (44px minimum) for comfortable use.
- **Swipe-Friendly Forms** — Multi-step forms are optimized for mobile interaction.

### Tips for Mobile Users

- **Bookmark** your Cratory URL to your phone's home screen for instant access (it works like a native app!).
- Use the **search bar** on list pages to quickly find invoices, contacts, or products.
- **Landscape mode** works great for viewing detailed reports and tables.

---

## 18. Getting Help

### In-App Help Center

Visit the **Help Center** page from the website footer for searchable FAQs covering:
- Getting Started
- Invoicing & Billing
- Inventory Management

### Contact Support

If you can't find what you need:
1. Go to the **Contact Support** page.
2. Fill in your **Name**, **Email**, and describe your issue.
3. Click **Send Message**.
4. Our team will respond within 24 hours on business days.

**Email:** cratory.support@yagneshbhanani.com  
**Support Hours:** Monday–Friday, 9am–5pm EST

---

## Quick Reference Cheat Sheet

| I want to... | Go to... |
|---|---|
| See my business overview | **Dashboard** |
| Create a new invoice | **Invoices > + New Invoice** |
| Download an invoice PDF | **Invoices > [Select Invoice] > Download PDF** |
| Add a new customer | **Contacts > + New Contact** |
| Add a product to my catalog | **Inventory > + New Product** |
| Log a purchase from a supplier | **Purchases > + New Purchase** |
| Record a customer payment | **Receipts > + New Receipt** |
| Pay a vendor bill | **Vouchers > + New Voucher** |
| See my profit/loss | **Reports > Profit & Loss** |
| Change invoice design | **Settings > Invoice Templates** |
| Add a team member | **Settings > User Management** |
| Switch to another business | **Profile > Switch Organization** |

---

*Last updated: July 7, 2026*
