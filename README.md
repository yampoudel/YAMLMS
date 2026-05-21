# YamLMS | Yet Another Multi-tenant LMS

[![Laravel 12](https://shields.io)](https://laravel.com)
[![PHP 8.5](https://shields.io)](https://php.net/releases/8.5/en.php)
[![TailwindCSS 4](https://shields.io)](https://tailwindcss.com)

**YamLMS** is a high-performance, multi-tenant Learning Management System engineered for high-compliance industries such as **Aged Care, NDIS providers, and Educational Institutions**, as well as **any company or trader who needs to provide training to their customers**. Built on the cutting-edge **Laravel 12** framework and optimized for **PHP 8.5**, it delivers a secure, audit-ready digital ecosystem for modern workforce and client training.

---

## 🌟 Core Pillars

### 🎓 The Learner Journey
*   **Cinema-Mode Interface:** A high-contrast, distraction-free player designed for focus.
*   **Adaptive Progress Tracking:** Real-time state persistence across devices.
*   **Compliance-Locked Certification:** Automated PDF generation via **DomPDF**, cryptographically locked until 100% completion.

### 💰 Secure E-Commerce & Monitization
*   **Stripe Elements Integration:** Implements an on-demand frontend `StripePaymentGateway` utilizing Stripe's unified secure frames.
*   **Tokenless Stateful Checkout:** Intercepts frontend transactions via `confirmPayment()` to reduce PCI compliance overhead, mapping directly to Laravel web session guards.
*   **Asynchronous Webhook Processing:** A signature-verified, cryptographic listener captures background events to automatically settle orders and provision learning access.

### 🛡️ Admin & Compliance (Audit-Ready)
*   **Workforce Oversight:** Real-time compliance monitoring for managers.
*   **Government-Standard Reporting:** One-click PDF reports designed for NDIS regulatory audits.
*   **Granular RBAC:** Robust Role-Based Access Control ensuring strict data isolation.

### 📱 Modern API & Mobile Connectivity
*   **Laravel 12 Sanctum Auth:** Secure stateful sessions (Web) and token-based auth (Mobile).
*   **PHP 8.5 Optimized:** Leveraging modern syntax like **Property Hooks** and the **Pipe Operator** for lean, readable logic.
*   **Performance-First API:** Optimized using **Eloquent Resources** and eager loading to prevent N+1 overhead.

---

## 🛠️ Technical Architecture


| Component | Technology |
| :--- | :--- |
| **Backend** | **Laravel 12** |
| **PHP Runtime** | **PHP 8.5** |
| **Authentication** | Laravel Breeze & Sanctum (Stateful-Enabled API stack) |
| **Frontend** | Tailwind CSS v4 & Stripe SDK (Dahlia Release Channel) |
| **Payment Gateway** | Stripe API (Payment Intents Architecture) |
| **Database** | MySQL (Production) / SQLite (Testing) |
| **Testing** | Pest 3.x / PHPUnit |

---

## 🧪 Quality Assurance & Security
*   **Logic Verification:** Unit tests for `ProgressService` ensure mathematical accuracy.
*   **Route Gating:** Feature tests ensure certificates cannot be accessed via URL manipulation.
*   **Signature Verification:** Webhook payloads require a valid `Stripe-Signature` validation handshake before processing.
*   **Automation:** Full test suite ensures zero regressions.
    ```bash
    php artisan test
    ```

---

## ⚙️ Quick Start

1. **Clone & Install**
   ```bash
   git clone https://github.com/yourusername/yamlms.git
   composer install
   npm install && npm run build
   ```

2. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Configure your Stripe Sandbox tokens inside your `.env` configuration file:
   ```env
   STRIPE_KEY=pk_test_your_publishable_key
   STRIPE_SECRET=sk_test_your_secret_key
   STRIPE_WEBHOOK_SECRET=whsec_your_local_tunnel_secret
   ```

3. **Database Migration**
   ```bash
   php artisan migrate --seed
   ```

4. **Local Webhook Execution (Development Only)**
   To test e-commerce transactions locally, open a separate terminal window and forward real-time cloud payment notifications to your app:
   ```bash
   stripe listen --forward-to yamlms.test/api/integrations/stripe/webhook
   ```

---

**Built with ❤️ by Yam Technologies**  
*Engineered for workforce excellence.*
