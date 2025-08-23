# Expense Tracker - Laravel 12

A simple expense tracker built with Laravel 12, Livewire, and Chart.js.

## Features

- User authentication
- Add, view, and categorize expenses
- Monthly summary of expenses by category
- Dashboard with Chart.js visualization
- Responsive UI with Blade components

## Requirements

- PHP 8.2+
- Composer
- Node.js & npm (for frontend assets, if needed)
- SQLite (default) or other supported DB

## Setup Instructions

1. **Clone the repository**
   ```bash
   git clone https://github.com/sayful1411/expense_tracker.git
   cd expense_tracker
   ```
2. **Install PHP dependencies**
   ```bash
   composer install

   npm install
   ```
3. **Set up the environment file**
   ```bash
   cp .env.example .env
   ```
4. **Generate the application key**
   ```bash
   php artisan key:generate
   ```
5. **Run the database migrations**
   ```bash
   php artisan migrate --seed
   ```
6. **Serve the application**
   ```bash
   composer run dev
   ```
7. **Access the app**
   Open your browser and go to `http://localhost:8000`.

## Usage

- Register a new account or login with an existing one.
- Add your expenses with details like amount, category, and date.
- View your expenses in a list or chart format.
- Use the monthly summary to track your spending habits.

## Credentials
- test@example.com
- password