# Cratory

Cratory is a modern, responsive, and fully-featured Invoicing and Finance Management System built with Laravel, Livewire 3, and Tailwind CSS. It is designed to help freelancers, agencies, and small businesses manage their invoices, customers, and products effortlessly.

## 🔒 Repo Policy & Security

Because this project is public but restricted, please note the following before contributing or cloning:

- **Active Branch:** The default and active development branch is `dev`. All forks and pull requests must target this branch.
- **Pull Requests:** Any contribution from a fork requires manual review and explicit approval by the owner before it will be merged.
- **Signed Commits Required:** The `dev` branch strictly requires cryptographically signed commits. Unsigned pushes will be automatically rejected.
- **Main Branch Lock:** The `main` branch is locked against direct pushes and pulls; it is reserved strictly for finalized, production-ready deployments by the owner.

## 🚀 Features

- **Smart Invoicing:** Create, edit, and manage sales and service invoices.
- **Customizable PDF Templates:** Generate professional PDFs with 4 unique templates (Standard, Modern, Minimal, Elegant). Customize colors, fonts, and toggle specific fields (like shipping addresses or tax details).
- **Responsive Line Items:** A bulletproof responsive UI that provides a seamless, side-by-side table on desktop and beautifully stacked cards on mobile devices.
- **Unsaved Changes Protection:** Built-in safeguards that warn you if you attempt to leave the page with unsaved form data.
- **Editable Invoice Numbers:** Intelligent auto-generation of sequence numbers with the flexibility to override them manually.
- **Quick Add Capabilities:** Instantly add new customers or line items directly from the invoice creation form without breaking your workflow.
- **Multi-tenant Architecture:** Full support for managing multiple organizations.
- **Robust Security:** Powered by Laravel Fortify for secure authentication.

## 🛠 Tech Stack

- **Backend:** Laravel 13.x, PHP 8.5+
- **Frontend:** Livewire 3, Alpine.js, Tailwind CSS
- **PDF Generation:** Barryvdh/laravel-dompdf
- **Database:** MySQL / SQLite

## 📦 Installation & Setup

1. **Clone the repository**
   ```bash
   git clone git@github.com:TheLateNightArtisan/cratory.git
   cd cratory
   ```

2. **Install PHP Dependencies**
   ```bash
   composer install
   ```

3. **Install NPM Dependencies**
   ```bash
   npm install
   npm run build
   ```

4. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Update your `.env` file with your database credentials.*

5. **Run Migrations & Seeders**
   ```bash
   php artisan migrate --seed
   ```

6. **Serve the Application**
   ```bash
   php artisan serve
   ```

## 🚀 Production Deployment

When deploying to a production server, ensure the following commands are run to optimize the application:

```bash
# Optimize Laravel
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Build production frontend assets
npm run build
```
Ensure your `.env` contains:
```env
APP_ENV=production
APP_DEBUG=false
```

## 📝 License & Copyright

**Copyright (c) 2026 TheLateNightArtisan (Yagnesh Bhanani). All Rights Reserved.**

This project is licensed under a strict **Non-Commercial** model. Permission is granted exclusively to view, fork, and test this software for personal, educational, and non-commercial exploration.

🔴 **COMMERCIAL USE, RESALE, RE-DISTRIBUTION, OR RE-LICENSING OF THIS SOFTWARE (IN WHOLE OR IN PART) IS STRICTLY PROHIBITED.** 

No individual or entity is permitted to sell this project, charge money for access to it, or use it for direct corporate monetary gain without explicit, written legal permission from the copyright holder.
