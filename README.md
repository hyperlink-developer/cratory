# Cratory

Cratory is a modern, responsive, and fully-featured Invoicing and Finance Management System built with Laravel, Livewire 3, and Tailwind CSS. It is designed to help freelancers, agencies, and small businesses manage their invoices, customers, and products effortlessly.

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
   git clone git@github.com:hyperlink-developer/cratory.git
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

## 📝 License

This project is proprietary and confidential.
