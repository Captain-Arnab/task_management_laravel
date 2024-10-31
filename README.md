# Task Management Module

This is a Task Management module built with Laravel, featuring CRUD functionality, search, sorting, and drag-and-drop ordering for tasks. This README provides an overview of the project structure, setup instructions, and how to run the application.

## Project Structure

The project is organized as follows:
```
TaskManagement/
├── app/
│   ├── Console/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── TaskController.php  # Handles task CRUD logic, search, sorting, reordering
│   │   ├── Middleware/
│   ├── Models/
│   │   └── Task.php                # The Task model represents tasks in the database
│   ├── Providers/
│   ├── ...
│
├── bootstrap/
├── config/
│   └── database.php                # Database configuration according to connection type
├── database/
│   ├── migrations/
│   │   └── 2024_10_31_035133_create_tasks_table  # Migration to create the tasks table
│   │   └── 2024_10_31_044057_add_priority_to_tasks_table # To create a migration file that will add the priority column
│   ├── seeders/
├── public/
│   └── index.php                   # Entry point for the application
├── resources/
│   ├── views/
│   │   └── tasks/
│   │       ├── index.blade.php     # Displays the list of tasks with drag-and-drop ordering
│   │       ├── create.blade.php    # Form for adding a new task
│   │       ├── edit.blade.php      # Form for editing an existing task
│   ├── css/
│   ├── js/
├── routes/
│   └── web.php                     # Defines routes, including the tasks routes
├── storage/
├── tests/
├── vendor/
├── .env                             # Environment settings (e.g., database configuration)
├── composer.json                    # Lists dependencies for the Laravel project
├── artisan                          # Command-line tool for Laravel tasks
└── ...
```

## Project Setup

Follow these steps to set up the project on your local machine:

### Prerequisites

- PHP (version >= 7.3)
- Composer
- Laravel (version >= 8.x)
- A database (MySQL, PostgreSQL, etc.)

### Installation

1. Clone the repository :

   ```
   git clone https://github.com/Captain-Arnab/task_management_laravel.git
   ```
2. Install dependencies :
Run the following command to install the required packages:
```
composer install
```
3. Run Migrations :
Apply the database migrations to create the necessary tables.
```
php artisan migrate
```
4. Running the Application :
```
php artisan serve
```
The application will be accessible at 
```
http://localhost:8000/tasks
```






