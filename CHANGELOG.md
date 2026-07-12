# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- **Document Numbering Settings**: Added a new settings page (`/settings/document-numbering`) allowing users to configure custom prefixes and document number formats (using placeholders like `{PREFIX}`, `{DOC_TYPE}`, `{FY}`, and `{SEQ}`) for Invoices, Receipts, and Payment Vouchers.
- **Editable Document Numbers**: Users can now manually edit the generated `Receipt Number` and `Voucher Number` directly on the Record Receipt and Record Payment forms before saving.
- **Dashboard Year Filter**: Added a "Year" filter button to the Dashboard that automatically maps to the organization's current financial year.

### Changed
- **Dashboard Periods Logic**: Updated the logic for Dashboard period filters (Month, Quarter). Quarter filtering now strictly aligns with the current financial quarter, and Month filtering maps to the current calendar month.

### Fixed
- **Invoice Template Previews**: Resolved a `500 Internal Server Error` on the Invoice Templates settings page caused by missing `discount_amount` and `discount_percent` properties in the mock data used for template generation.
- **Invoice & Bill Syncing**: Fixed an issue where creating or deleting Receipts and Payment Vouchers failed to properly update the `amount_paid` and status (e.g., "SENT" to "PAID" or "PARTIAL") of the related Invoice or Purchase Bill.

## [1.0.0] - Initial Release

### Added
- **Core Billing & Invoicing**: Comprehensive system for creating and managing sales invoices and purchase bills.
- **Ledger & Accounting**: Integrated financial ledger to track all transactions across the organization.
- **Automated Inventory**: Real-time stock tracking with automated adjustments upon sales and purchases.
- **Reporting**: Detailed financial and inventory reporting tools.
- **Contact Management**: Complete vendor and customer support form UI.
- **User Management**: Role-based access control and user management UI for team collaboration.
- **Documentation**: Comprehensive User Guide written for non-technical users, along with detailed repository documentation and PRD.
