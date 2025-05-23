# 🎓 Student Management System

A scalable, multi-tenant student management platform built with Laravel and Vue.js. It supports academic workflows for universities, departments, professors, and students, with role-based access control, tenant isolation, and RESTful APIs.

---

## 🛠 Tech Stack

- **Backend:** Laravel 10, PHP 8.3
- **Frontend:** Vue.js 3, Pinia
- **Database:** MySQL
- **Authentication:** JWT (via Laravel Passport)
- **Caching:** Tenant-aware Laravel Cache
- **Multi-tenancy:** Shared-database model scoped by `university_id` and `department_id`

---

## ✨ Features

- 🔐 Role-based access (Superadmin, Professor, Student)
- 🏫 Multi-university & multi-department support
- 📚 Course offerings, enrollments, assignments, grades, exams, and transcripts
- 📅 Appointment scheduling & complaint tracking
- 📊 Full academic CRUD workflows
- 🚀 Layered API architecture (Controller → Processor → Repository)
- ⚡ Global scopes for tenant isolation

---

## ⚙️ Setup Instructions

```bash
# Clone the repository
git clone https://github.com/your-username/student-management-system.git
cd student-management-system

# Install dependencies
composer install
npm install && npm run dev

# Set environment
cp .env.example .env
php artisan key:generate

# Migrate and seed the database
php artisan migrate 

# Serve the app
php artisan serve
