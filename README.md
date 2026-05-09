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
| **Authentication** | Laravel Breeze & Sanctum |
| **Frontend** | Tailwind CSS v4 & Blade Components |
| **Database** | MySQL (Production) / SQLite (Testing) |
| **Testing** | Pest 3.x / PHPUnit |

---

## 🧪 Quality Assurance & Security
*   **Logic Verification:** Unit tests for `ProgressService` ensure mathematical accuracy.
*   **Route Gating:** Feature tests ensure certificates cannot be accessed via URL manipulation.
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
3. **Database Migration**
   ```bash
   php artisan migrate --seed
   ```

---

**Built with ❤️ by Yam Technologies**  
*Engineered for workforce excellence.*
