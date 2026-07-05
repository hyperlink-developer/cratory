# Cratory - Comprehensive User Guide

Welcome to **Cratory**, a modern, responsive, and fully-featured Invoicing, Ledger, and Finance Management System designed for freelancers, agencies, and small businesses. This guide covers all the core functionalities so you can get the most out of your application.

---

## 🏢 1. Organization Management
Cratory is multi-tenant out of the box, meaning you can manage multiple businesses (organizations) from a single account.

- **Switching Organizations:** Click on your avatar/profile in the top right (or the sidebar on mobile) and select **Switch Organization**.
- **Organization Settings:** Customize your organization's GST registered status, Composition Scheme status, and default currency in the settings. This dynamically adjusts what tax fields and UI elements are shown throughout the application.

## 👥 2. Contacts (Customers & Vendors)
The Contacts module serves as your CRM and Vendor management directory.

- **Customers:** Used primarily for Sales Invoices and Receipts. 
- **Vendors:** Used for Purchase Bills and Payment Vouchers.
- **Quick Add:** When creating a new invoice or purchase, you can instantly add a new contact without leaving the form.

## 📦 3. Products & Inventory
Manage your goods and services.

- **Products vs Services:** Ensure you accurately select whether an item is a Product (physical good) or Service.
- **Automated Stock Tracking:** For Products, Cratory tracks inventory. 
  - Creating a **Purchase Bill** (and marking it "Received") automatically increases stock.
  - Sending a **Sales Invoice** automatically decreases stock.
- **HSN / SAC Codes:** You can assign HSN (Products) or SAC (Services) codes to items, ensuring compliance on your generated invoices.

## 💰 4. Sales Invoices
Generate and manage your sales.

- **Invoice Basis (Credit vs Cash):** 
  - **Credit Invoices:** Standard invoices where payment is expected later. Can be tracked for outstanding balances.
  - **Cash Invoices:** For point-of-sale or immediate payments. A cash invoice requires no due date, and automatically creates a corresponding "Receipt" to mark the transaction as fully paid instantly.
- **Tax Configuration:** Automatically adjusts based on your Organization's settings (e.g., standard GST vs Composition taxpayer).

## 🛒 5. Purchase Bills
Log your incoming goods and expenses.

- **Entering Bills:** When you receive a bill from a vendor, log it as a Purchase. 
- **Status Management:** 
  - **Draft:** Work in progress.
  - **Received:** Finalizes the bill. Crucially, marking a bill as "Received" triggers the automated inventory addition.
- **Cash Purchases:** Similar to Sales, marking a Purchase as "Cash" will automatically create a Payment Voucher and mark the bill as fully paid.

## 💵 6. Receipts & Payment Vouchers
Manage the flow of money for Credit transactions.

- **Receipts:** Log incoming payments from Customers. You can select a Customer, enter the amount received, and the system will automatically let you allocate that payment across their open Sales Invoices.
- **Payment Vouchers:** Log outgoing payments to Vendors. Works identically to Receipts, allowing you to allocate one bulk payment across multiple open Purchase Bills.

## 📓 7. The Ledger & Accounting
Cratory features a fully automated Double-Entry Accounting system working invisibly in the background.

- **Automatic Journal Entries:** Whenever a Sale, Purchase, Receipt, or Voucher is finalized, Cratory automatically generates the corresponding Journal Entries (Debits and Credits) to the correct Accounts (e.g., Cash, Accounts Receivable, Sales, Inventory).
- **Chart of Accounts:** Accounts are logically grouped (Assets, Liabilities, Equity, Revenue, Expenses) and are strictly managed by the system to maintain financial integrity.

## 📊 8. Reports & Analytics
Cratory provides powerful financial reports that can be viewed on-screen or exported as PDF / CSV.

- **Sales / Purchase Reports:** Breakdowns of all billing activity over a selected time period.
- **Profit & Loss:** A complete income statement showing your Revenues against Expenses and Cost of Goods Sold over a period.
- **Trial Balance:** A snapshot of all account balances ensuring your debits and credits match perfectly.
- **Balance Sheet:** A statement of your organization's Assets, Liabilities, and Equity at a specific point in time.

## 📱 9. Mobile Experience
Cratory is designed with a **Mobile-First** philosophy.
- The sidebar neatly tucks away into a bottom navigation bar or hamburger menu on smaller screens.
- Complex tables (like Invoice Items and Reports) automatically transform into beautifully stacked, responsive cards on mobile, ensuring you can run your business efficiently directly from your phone.

---

### Need Further Assistance?
If you encounter any bugs or need new features, ensure you check the `laravel.log` in `storage/logs` for detailed error messages, or consult the Laravel and Livewire documentation for technical extensions.
