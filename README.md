# Backend - School Information System and Online PPDB Platform

## About the Project

This project is the development of a **School Information System and Online Student Admission (PPDB) Platform for MIN 1 Rokan Hulu**, an Islamic elementary school located in Rokan Hulu Regency, Riau Province, Indonesia. Until now, the dissemination of school information and the new student admission process had been conducted manually through social media platforms such as Facebook and WhatsApp, as well as using physical banners. This approach is inefficient, prone to data entry errors, and burdensome for parents in terms of time and cost.

To address these issues, the system is developed using the **Rapid Application Development (RAD)** methodology, which emphasizes fast and iterative development. The core features of the system include:

- Management of school data including profiles, teachers, students, facilities, and activities.
- Online student registration process.
- Digital document upload for admission requirements.
- Student selection supported by a Decision Support System using the **Simple Additive Weighting (SAW)** method.

The technologies used in this system include:

- **Laravel (PHP)** for the backend.
- **Vue.js** for the frontend.
- **MySQL** as the database.

This system aims to improve administrative efficiency, expand information accessibility, reduce manual errors, and support transparent and objective student selection processes.

---

## Getting Started

### Prerequisites

- PHP >= 8.2
- Node.js >= 18
- MySQL 8+

### Installation

```bash
git clone https://github.com/yourusername/yourproject.git
cd yourproject
composer install
cp .env.example .env
php artisan key:generate
npm install && npm run dev
php artisan migrate --seed
php artisan serve
```

---

## Using Laravel Framework

Laravel is a web application framework with expressive, elegant syntax. It simplifies common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

For more details, check the full [Laravel documentation](https://laravel.com/docs).

## License

This project is open-source and available under the [MIT license](https://opensource.org/licenses/MIT).