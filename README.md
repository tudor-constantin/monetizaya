# MonetizaYa

> Monetization platform for digital content creators. Recurring subscriptions, premium content, and payments managed with Stripe.

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![Livewire](https://img.shields.io/badge/Livewire-3.x-4E56A6?style=flat-square&logo=livewire)](https://livewire.laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/TailwindCSS-4.x-38BDF8?style=flat-square&logo=tailwindcss)](https://tailwindcss.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=flat-square)](LICENSE)

---

## Table of Contents

1. [Description](#description)
2. [Problem it solves](#problem-it-solves)
3. [Value proposition](#value-proposition)
4. [Core features](#core-features)
5. [User roles](#user-roles)
6. [System flow](#system-flow)
7. [Tech stack](#tech-stack)
8. [Architecture](#architecture)
9. [Production considerations](#production-considerations)
10. [Local installation](#local-installation)
11. [Project structure](#project-structure)
12. [Roadmap](#roadmap)
13. [Best practices](#best-practices)
14. [License](#license)

---

## Description

**MonetizaYa** is a SaaS platform that allows digital content creators — writers, educators, artists, developers — to monetize their audience through monthly recurring subscriptions.

Creators publish exclusive content (posts, downloadable resources, courses) accessible only to their active subscribers. The platform manages the complete cycle: registration, payments, content access, and revenue distribution, retaining a configurable commission per transaction.

The project is built for real production from day one: clean architecture, secure payments via Stripe, and a maintainable, scalable codebase.

---

## Problem it solves

Content creators lack a membership platform with reasonable pricing, transparent commissions, and real control over their content and audience. Existing options (Patreon, Ko-fi, Buy Me a Coffee) charge high fees and offer limited flexibility for specific business models.

MonetizaYa fills this gap with:

- A platform built for creators, with transparent and configurable commissions.
- Full control over content types and subscription structures.
- Own infrastructure, without third-party dependency for audience management.

---

## Value proposition

| For the creator | For the subscriber |
|---|---|
| Publish posts, files, and courses in one place | Access all exclusive content from creators they follow |
| Get paid automatically every month via Stripe | Manage active subscriptions from a unified dashboard |
| View revenue and subscriber metrics | Pay securely; their data isn't managed by the creator |
| Control what content is free vs premium | Receive notifications when new content is published |

---

## Core features

### Creator management

- Customizable public profile: avatar, cover image, bio, social links, and subscription link.
- Creator dashboard with metrics: monthly revenue, active subscribers, monthly churn, and most visited content.
- Monthly subscription price configuration.
- Premium content creation and editing with visibility control.

### Content types

- **Posts**: Rich text entries with image and embed support. Can be free or subscriber-exclusive.
- **Downloadable resources**: PDF, ZIP, images, audio, or any file format. Protected downloads via temporary signed URLs.
- **Courses**: Structured modules and ordered lessons. Optional sequential access. Student progress persisted per user.

### Subscriptions and payments

- Subscription flow managed entirely with Stripe Checkout and Laravel Cashier.
- Monthly recurring charges with automatic renewal.
- Payment failure handling: Stripe automatic retries and user notification.
- Cancellation and reactivation from the subscriber dashboard.
- Revenue share: the platform retains a configurable percentage; the creator sees their net income in real time.
- Payment history and PDF invoice downloads.

### Admin panel

- User and creator management: activation, suspension, and deletion.
- Global platform commission configuration.
- Transaction, dispute, and refund overview.
- Reported content review.
- Global statistics: GMV, net revenue, active creators, retention.

### Notifications

- Transactional emails (new subscriber, successful payment, failed payment, cancellation).
- In-platform notifications for subscribers (new content published).

---

## User roles

### `user` — Subscriber

Registered user who can explore the platform, subscribe to creators, and access premium content from their active subscriptions. Manages payment methods and subscriptions from their personal dashboard.

### `creator` — Creator

User with an enabled creator profile (can request it from their account or be assigned by an admin). Can publish content, configure subscription pricing, and access their analytics and revenue dashboard.

### `admin` — Administrator

Full platform access. Manages users, creators, content, commission settings, and oversees the financial state of the system. Cannot access individual users' payment methods (managed directly by Stripe).

---

## System flow

```
[Visitor]
    │
    ├── Explores creator profiles and free content
    │
    └── Registers as a user
            │
            ├── Searches for a creator → visits their public profile
            │
            └── Starts a subscription
                    │
                    ├── Redirects to Stripe Checkout (secure payment)
                    │
                    └── Stripe confirms payment
                            │
                            ├── Webhook → Laravel activates the subscription in the DB
                            │
                            ├── User accesses the creator's premium content
                            │
                            └── Each month: Stripe charges → webhook → transaction recorded
                                    │
                                    ├── Stripe calculates creator net (commission deducted)
                                    │
                                    └── Creator views the income in their dashboard
```

Critical Stripe events (payments, cancellations, failures, disputes) are managed exclusively through signed webhooks, never via polling or client redirects.

---

## Tech stack

| Layer | Technology | Reason |
|---|---|---|
| Backend | Laravel 13 | Mature PHP framework, complete ecosystem, excellent queue, job, and event support |
| Frontend | Blade + Livewire 3 | Reactive interactivity without SPA; less complexity and better SEO than React/Vue for this product |
| Styling | Tailwind CSS 4 | Atomic utilities, no unnecessary custom CSS, easy to maintain at scale |
| Database | MySQL 8 / PostgreSQL 15 | Relational; both supported; PostgreSQL recommended for production due to robustness and advanced type support |
| Payments | Stripe + Laravel Cashier | Cashier abstracts subscription management over the Stripe API; reduces boilerplate and errors |
| Queues | Laravel Queues + Redis | Async jobs for emails, webhooks, and file processing |
| Storage | Laravel Filesystem (S3 / local) | Premium files stored outside the webroot, served via signed URLs |
| Deployment | Coolify / Dokploy | Self-hosted PaaS on Docker; simple CI/CD pipelines |

---

## Architecture

### Backend

Business logic is organized in services (`app/Services`) decoupled from controllers. Controllers are thin: they validate input, delegate to the appropriate service, and return the response.

Long-running jobs (file processing, email sending, Stripe sync) are dispatched to Redis queues to not block the request-response cycle.

### Frontend

Blade views are complemented with Livewire components for parts requiring reactivity: publishing forms, subscription dashboard, course progress, and real-time notifications. Custom JavaScript is limited to specific integrations (Stripe.js for payment forms).

### Payments

The payment flow does pass through MonetizaYa servers: the user is redirected to Stripe Checkout, which securely handles card data capture. Payment events come back via webhooks signed with Stripe's secret key, verified before processing.

The platform commission is implemented via revenue share calculation on each transaction recorded locally.

### Security

- Authentication with Laravel Breeze / Fortify (2FA support).
- Authorization via Laravel Policies; no creator endpoint is accessible without role verification.
- Premium content served exclusively with short-lived S3 signed URLs (15 minutes); no permanent URLs exist.
- Stripe webhooks verified with `StripeSignatureVerificationException` before processing any event.
- Sensitive variables (API keys, DB credentials) managed with `.env` and never versioned.
- HTTP security headers configured at the server level (HSTS, CSP, X-Frame-Options).
- Rate limiting on authentication endpoints and public API.

---

## Production considerations

### Scalability

- The application is stateless: can scale horizontally by adding instances without code changes.
- Sessions and cache are stored in Redis, shared across instances.
- User files reside in S3 (or compatible), not on instance disk.
- Queues absorb traffic spikes without affecting response times.

### Payment security

- MonetizaYa never stores card data; PCI compliance delegated to Stripe.
- Stripe `payment_method` and `customer_id` are the only financial identifiers saved in the DB.
- All transactions are recorded locally for auditing, even when the origin is a webhook.

### Premium content protection

- Files are not accessible via direct URL; the storage server keeps them private.
- Each download request generates a new signed URL, verifying in the backend that the user has an active subscription.
- Video streaming (if applicable in the roadmap) will be implemented with HLS and short-lived access tokens.

### Payment error handling

- Stripe manages automatic retries according to its Smart Retries logic.
- The `invoice.payment_failed` webhook triggers an email to the subscriber and marks the subscription as `past_due`.
- After the configurable grace period, the subscription is cancelled and content access is automatically revoked.

---

## Local installation

### Prerequisites

- PHP 8.3+
- Composer 2.x
- Node.js 20+ and npm
- MySQL 8+ or PostgreSQL 15+
- Redis
- Stripe account with test keys

### Steps

```bash
# 1. Clone the repository
git clone https://github.com/tudor-constantin/monetizaya.git
cd monetizaya

# 2. Install PHP dependencies
composer install

# 3. Install frontend dependencies
npm install

# 4. Copy the environment file
cp .env.example .env

# 5. Generate the application key
php artisan key:generate

# 6. Run migrations and seeders
php artisan migrate --seed

# 7. Compile assets
npm run dev

# 8. Start the development server
php artisan serve

# 9. (In another terminal) Start the queue worker
php artisan queue:work redis

# 10. (Optional) Listen to Stripe webhooks locally
stripe listen --forward-to localhost:8000/stripe/webhook
```

#### Configure `.env`

Edit the `.env` file with your local environment values:

```env
APP_NAME=MonetizaYa
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=monetizaya
DB_USERNAME=root
DB_PASSWORD=

REDIS_HOST=127.0.0.1
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=

STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...

CASHIER_CURRENCY=eur
PLATFORM_COMMISSION_PERCENTAGE=10
```

The application will be available at `http://localhost:8000`.

The seeder creates a default admin user:

```
Email: admin@monetizaya.test
Password: password
```

---

## Project structure

```
monetizaya/
├── app/
│   ├── Console/            # Custom Artisan commands
│   ├── Enums/              # PHP backed enums (ContentStatus, TransactionStatus, TransactionType)
│   ├── Events/             # Domain events (SubscriptionCreated, ContentPublished...)
│   ├── Http/
│   │   ├── Controllers/    # Thin controllers
│   │   ├── Middleware/     # Custom middleware (CheckSubscription, EnsureCreator, EnsureAdmin)
│   │   └── ...
│   ├── Jobs/               # Async jobs (SendWelcomeEmail, ProcessDownload...)
│   ├── Listeners/          # Event listeners
│   ├── Models/             # Eloquent models
│   ├── Policies/           # Authorization policies
│   ├── Services/           # Business logic (SubscriptionService, RevenueService...)
│   ├── View/Components/    # Blade view components
│   └── Webhooks/           # Stripe webhook handlers
├── bootstrap/
├── config/                 # Application configuration
├── database/
│   ├── factories/          # Model factories for testing
│   ├── migrations/
│   └── seeders/
├── public/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       ├── components/     # Reusable Blade components
│       ├── creator/        # Creator panel views
│       ├── admin/          # Admin panel views
│       └── layouts/
├── routes/
│   ├── web.php
│   ├── auth.php
│   └── console.php
├── storage/
│   └── app/private/        # Temporary files (not for permanent production use)
└── tests/
    ├── Feature/
    └── Unit/
```

---

## Roadmap

### v1.0 — Initial release

- [x] Authentication and roles (user, creator, admin)
- [x] Creator public profile
- [x] Posts with visibility control
- [x] Downloadable resources with signed URLs
- [x] Monthly subscriptions via Stripe Checkout
- [x] Revenue share calculation
- [x] Creator dashboard with basic metrics
- [x] Admin panel
- [x] Transactional emails
- [x] SPA navigation with Livewire Volt (wire:navigate)
- [x] Complete dark mode with persistent toggle
- [x] Professional UI/UX with Tailwind CSS 4
- [x] Secure auth forms with English messages
- [x] Toast notifications with auto-dismiss and responsive manual close
- [x] Complete translations in `lang/en/` (auth, passwords, validation, UI/profile)
- [x] Clean creator profile URLs (slug-first, no random suffix)
- [x] Avatar/cover uploads for creator profile and avatar upload for all users
- [x] Enhanced post editor with cover image and excerpt
- [x] Enriched creator profile settings (mandatory avatar, tagline, social links, pricing)
- [x] Premium post access enforcement (free users cannot read premium content)
- [x] Public post detail pages with creator breadcrumbs
- [x] Premium post previews with subscribe paywall and creator sidebar context
- [x] Clean post URLs (slug-based, no random suffix)
- [x] Authorization policies for premium content
- [x] Role middleware (creator, admin)
- [x] Business services (SubscriptionService, RevenueService)
- [x] Stripe webhook handler for payment events
- [x] Models and migrations (Post, Resource, Course, Module, Lesson, Transaction)

### v1.1 — Courses

- [ ] Modules and lessons with configurable ordering
- [ ] Student progress tracking
- [ ] Optional sequential access
- [ ] Completion certificate issuance (PDF)

### v1.2 — Advanced monetization

- [ ] Subscription tiers with differentiated benefits
- [ ] One-time payment content (outside subscription)
- [ ] Discount codes and temporary free access

### v1.3 — Community and engagement

- [ ] Comment system on posts and lessons
- [ ] Push notifications (web push API)
- [ ] Subscriber activity feed

### v2.0 — Multi-tenant / white-label platform

- [ ] Custom domain per creator
- [ ] Configurable visual themes
- [ ] Public API for external integrations

---

## Best practices

- **Layered architecture**: controllers contain no business logic; it resides in testable services.
- **Events and listeners**: secondary actions (emails, notifications, metric logging) are decoupled via Laravel's event system.
- **Tests from the start**: Feature test coverage for critical flows (subscription, content access, webhooks). Uses `RefreshDatabase` and factories to isolate each test.
- **Atomic migrations**: each migration does one thing; avoid migrations that modify and create in the same file.
- **Naming conventions**: follows Laravel standards for models, controllers, policies, and jobs.
- **Typed environment variables**: boolean and numeric values cast in `config/`; never call `env()` directly from application code.
- **Secrets outside the repository**: `.env` in `.gitignore`; `.env.example` documented and updated in each PR.
- **Structured logs**: differentiated log channels for payments, webhook errors, and user activity.

---

## License

This project is licensed under the [MIT License](LICENSE).

---

<p align="center">
  Built with ☕ and Laravel · <a href="https://github.com/tudor-constantin/monetizaya">github.com/tudor-constantin</a>
</p>
