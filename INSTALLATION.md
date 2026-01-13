# Installation Guide - CI4 CRUD Maker

Follow these steps to set up the **CI4 CRUD Maker** on your local machine.

## Prerequisites
- **PHP** >= 8.1
- **Composer** (Dependency Manager for PHP)
- **Node.js** & **NPM** (For frontend assets)
- **MySQL** or **MariaDB** Database

## 1. Get the Code
Clone the repository or extract the project files to your web server directory.
```bash
git clone <repository_url> ci4-crud-maker
cd ci4-crud-maker
```

## 2. Install Dependencies
Install PHP dependencies via Composer:
```bash
composer install
```

Install Frontend dependencies via NPM:
```bash
npm install
```

## 3. Environment Configuration
Copy the specific environment file to `.env`:
```bash
cp env .env
```
Open `.env` in a text editor and update the following settings:
1.  Set `CI_ENVIRONMENT` to `development`.
2.  Set `app.baseURL` to your local URL (e.g., `http://localhost:8080/`).
3.  **Database Config**: Uncomment and set your MySQL credentials.
    ```ini
    database.default.hostname = localhost
    database.default.database = ci4_crud_maker
    database.default.username = root
    database.default.password = your_password
    database.default.DBDriver = MySQLi
    ```

## 4. Database Setup
Create the database in MySQL if it doesn't exist:
```sql
CREATE DATABASE ci4_crud_maker;
```

Run the migrations to create all necessary tables (Shield Auth + RBAC + Modules):
```bash
php spark migrate --all
```

## 5. Create First User
You can create a user via the command line using CodeIgniter Shield:
```bash
php spark shield:user create
```
*Follow the prompts to enter a username, email, and password.*

Alternatively, just register via the browser UI once the app is running.

## 6. Run the Application
Start the local development server:
```bash
php spark serve
```
Access the application at: [http://localhost:8080](http://localhost:8080)

## 7. Post-Install Plugin Setup (AdminLTE)
If AdminLTE styles seem missing, ensure the plugins are correctly published. 
(Note: The project uses standard AdminLTE 3 paths. If `public/plugins` is empty, you may need to copy them from `node_modules/admin-lte/plugins` to `public/plugins`).

## Usage
1.  **Login**: Go to `/login` with your created user.
2.  **Admin Dashboard**: Navigate to `/admin/dashboard`.
3.  **Create Module**:
    - Go to **Admin > Modules > Create New**.
    - Define your module (e.g. Products) and its fields.
    - Click **Generate**.
    - Run `php spark migrate` again to create the new module's table.
    - Access your new CRUD at `/admin/products`.
