# Dayflow - Human Resource Management System

> "Every workday, perfectly aligned."

Dayflow is a production-ready HRMS built with Laravel 10, designed to streamline employee management, attendance tracking, leave processing, and payroll generation.

## Features

-   **Role-Based Access Control**: Secure Admin and Employee portals.
-   **Employee Management**: Full CRUD for employee profiles.
-   **Attendance System**: Daily check-in/check-out with status tracking.
-   **Leave Management**: Request system with Admin approval workflow.
-   **Payroll Generation**: Monthly salary slip generation.
-   **Modern UI**: Clean, responsive interface built with Bootstrap 5 and custom styling.

## Tech Stack

-   **Backend**: PHP 8.1+, Laravel 10
-   **Database**: MySQL
-   **Frontend**: Blade Templates, Bootstrap 5
-   **Authentication**: Laravel Auth (Session-based)

## Setup Instructions

1.  **Clone/Nav**: Open the project folder in terminal.
2.  **Install Dependencies**:
    ```bash
    composer install
    ```
3.  **Environment**:
    -   Copy `.env.example` to `.env`.
    -   Configure database: `DB_DATABASE=dayflow_hrms`, `DB_USERNAME=root`.
4.  **Database Setup**:
    ```bash
    php artisan migrate --seed
    ```
5.  **Run Server**:
    ```bash
    php artisan serve
    ```

## Login Credentials

### Admin

-   **Email**: `admin@dayflow.com`
-   **Password**: `password`

### Employees

**Sales Department**

-   **Email**: `aarav.sales@workhive.com`
-   **Password**: `WorkHive@2026`

**IT Department**

-   **Email**: `rohan.it@workhive.com`
-   **Password**: `WorkHive@2026`

**HR Department**

-   **Email**: `kavya.hr@workhive.com`
-   **Password**: `WorkHive@2026`

**Operations Department**

-   **Email**: `deepak.operations@workhive.com`
-   **Password**: `WorkHive@2026`

## Contribution

Built for Hackathon 2026. Code follows MVC architecture and standard Laravel best practices.
