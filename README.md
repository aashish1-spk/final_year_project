# PahiloJob - Job Portal Platform

A modern, professional job portal platform built with Laravel and Bootstrap. Connect job seekers with employers and manage job postings efficiently.

## 📋 Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database Setup](#database-setup)
- [Usage](#usage)
- [Project Structure](#project-structure)
- [Key Features](#key-features)
- [API Endpoints](#api-endpoints)
- [Contributing](#contributing)
- [License](#license)

## ✨ Features

### For Job Seekers
- Browse and search job listings
- Filter jobs by category, location, and job type
- Save favorite jobs
- Apply for jobs with CV upload
- Manage profile and CV
- View application history
- Leave reviews for companies
- Receive job notifications

### For Employers
- Post and manage job listings
- Feature job postings for better visibility
- View job applications
- Manage company profile
- Track featured job expiration
- Payment integration for featured jobs

### For Administrators
- Dashboard with analytics
- Manage users (job seekers and employers)
- Manage job categories and types
- Moderate job postings
- View all applications
- Manage payments and featured jobs
- User approval system

## 🛠 Tech Stack

- **Backend**: Laravel 10.x
- **Frontend**: Bootstrap 5, Blade Templates
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **Payment Gateway**: Integrated payment system
- **File Storage**: Local storage with public disk
- **Email**: Laravel Mail
- **Icons**: Font Awesome 4.7 & 6.4

## 📦 Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL 5.7 or higher
- Node.js & npm (optional, for asset compilation)

### Steps

1. **Clone the repository**
```bash
git clone https://github.com/yourusername/pahilojob.git
cd pahilojob
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Copy environment file**
```bash
cp .env.example .env
```

4. **Generate application key**
```bash
php artisan key:generate
```

5. **Configure database**

Configure your database connection in the `.env` file with appropriate credentials.

6. **Run migrations**
```bash
php artisan migrate
```

7. **Seed database (optional)**
```bash
php artisan db:seed
```

8. **Create storage symlink**
```bash
php artisan storage:link
```

9. **Start development server**
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

**Important**: All configuration details should be set in the `.env` file. Never commit sensitive information to version control.

## ⚙️ Configuration

### Environment Setup

1. Copy the example environment file:
```bash
cp .env.example .env
```

2. Generate application key:
```bash
php artisan key:generate
```

3. Configure your database connection in `.env` file

4. Set up your payment gateway credentials in `.env` file

**Note**: Never commit `.env` file to version control. All sensitive credentials should be kept private and only shared securely with authorized team members.

### Important Security Notes

- Keep `.env` file out of version control (already in .gitignore)
- Never share API keys or credentials in documentation
- Use environment variables for all sensitive data
- Rotate credentials regularly
- Use strong, unique passwords for database and services

## 🗄️ Database Setup

### Key Tables

- **users**: User accounts (job seekers, employers, admins)
- **jobs**: Job postings
- **categories**: Job categories
- **job_types**: Employment types (Full-time, Part-time, etc.)
- **job_applications**: Job applications from seekers
- **cvs**: Candidate CVs
- **saved_jobs**: Bookmarked jobs
- **payments**: Payment records for featured jobs
- **reviews**: Company reviews
- **notifications**: User notifications

### Run Migrations

```bash
php artisan migrate
```

### Rollback Migrations

```bash
php artisan migrate:rollback
```

## 🚀 Usage

### User Roles

1. **Job Seeker**
   - Register and create profile
   - Upload CV
   - Search and apply for jobs
   - Save favorite jobs
   - Leave reviews

2. **Employer**
   - Register company
   - Post job listings
   - Feature jobs (paid)
   - View applications
   - Manage postings

3. **Admin**
   - Access admin dashboard
   - Manage all users
   - Moderate content
   - View analytics

### Common Tasks

**Post a Job**
1. Login as employer
2. Click "Post a Job"
3. Fill job details
4. Submit

**Apply for Job**
1. Login as job seeker
2. Browse jobs
3. Click "Apply"
4. Upload CV
5. Submit application

**Feature a Job**
1. Post a job
2. Click "Feature Job"
3. Complete payment
4. Job appears in featured section

## 📁 Project Structure

```
pahilojob/
├── app/
│   ├── Console/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   ├── AccountController.php
│   │   │   ├── JobsController.php
│   │   │   └── ...
│   │   ├── Middleware/
│   │   └── Kernel.php
│   ├── Mail/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Job.php
│   │   ├── Category.php
│   │   └── ...
│   └── Providers/
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── public/
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   ├── images/
│   │   └── fonts/
│   ├── cv/
│   ├── profile_pic/
│   └── index.php
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       ├── Admin/
│       ├── Auth/
│       ├── front/
│       │   ├── account/
│       │   ├── layouts/
│       │   ├── home.blade.php
│       │   └── ...
│       └── email/
├── routes/
│   ├── web.php
│   └── api.php
├── storage/
├── tests/
├── .env.example
├── .gitignore
├── composer.json
├── package.json
└── README.md
```

## 🔑 Key Features

### Job Management
- Create, edit, delete job postings
- Feature jobs for premium visibility
- Set salary ranges
- Specify job types and locations
- Add detailed descriptions

### Application System
- Track job applications
- Upload and manage CVs
- Application status tracking
- Notification system

### User Management
- Role-based access control (Admin, Employer, Job Seeker)
- User approval system
- Profile management
- PAN verification for employers

### Payment Integration
- Payment gateway integration
- Payment history tracking
- Automatic featured job expiration

### Search & Filter
- Search by keyword
- Filter by location
- Filter by category
- Filter by job type
- Save favorite jobs

### Reviews System
- Leave company reviews
- Reply to reviews
- Rating system
- Review moderation

## 📡 API Endpoints

### Authentication
- `POST /login` - User login
- `POST /register` - User registration
- `POST /logout` - User logout
- `POST /forgot-password` - Password reset

### Jobs
- `GET /jobs` - List all jobs
- `GET /jobs/{id}` - Get job details
- `POST /jobs` - Create job (employer only)
- `PUT /jobs/{id}` - Update job
- `DELETE /jobs/{id}` - Delete job

### Applications
- `POST /jobs/{id}/apply` - Apply for job
- `GET /applications` - List user applications
- `GET /applications/{id}` - Get application details

### Admin
- `GET /admin/dashboard` - Admin dashboard
- `GET /admin/users` - List users
- `GET /admin/jobs` - List all jobs
- `GET /admin/applications` - List all applications

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


## 🙏 Acknowledgments

- Laravel framework
- Bootstrap CSS framework
- Font Awesome icons
- All contributors and users

---

**Last Updated**: January 2025
**Version**: 1.0.0
**Status**: Active Development
