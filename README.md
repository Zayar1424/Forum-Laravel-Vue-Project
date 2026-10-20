# Forum Project

A web forum where members can start discussions, reply to threads, follow other
members, and browse community content. Administrators have a separate area for
managing users and categories.

The application is built with **Laravel 12**, **PHP 8.2+**, **Inertia.js 2**,
and **Vue 3**. **Vite** builds and serves the frontend assets, and **MySQL** is
the configured database for local development.

## Features

- Browse threads, with options to sort by latest, most replies, or most views.
- Search and filter threads by category or tag.
- Create, edit, and delete your own threads.
- Reply to discussions and manage your own replies.
- Register, sign in, update your profile, and change your password.
- View member profiles and follow or unfollow members. Signed-in members can
  also view threads from people they follow.
- Use an admin dashboard to manage users and discussion categories.
- Record thread views and show thread/reply activity.

Creating threads, replying, following members, and accessing profile features
requires an account. Admin pages require an authenticated account with its
`is_admin` flag enabled.

## Requirements

Install the following before setting up the project:

- PHP **8.2 or newer**, with the extensions required by Laravel. For the
  MySQL setup, enable `pdo_mysql`.
- [Composer](https://getcomposer.org/) 2.
- [Node.js](https://nodejs.org/) and npm. Vite 7 requires Node.js **20.19+** or
  **22.12+**.
- Git.
- A running MySQL server and a database created for this application.

Laravel also has SQLite, MariaDB, PostgreSQL, and SQL Server connection
settings available in `config/database.php`; configure the matching PHP driver
and `.env` values if you choose a different database.

## Run locally

After cloning the repo, run these commands from the project root.

### 1. Install dependencies

```sh
composer install
npm install
```

### 2. Create your local environment file

Copy `.env.example` to `.env`. For example:

```powershell
# Windows PowerShell
Copy-Item .env.example .env
```

```sh
# macOS / Linux
cp .env.example .env
```

Configure the database settings in your local `.env` file for your MySQL
installation. Do not commit your `.env` file or put production credentials in
it.

### 3. Configure the application key and MySQL connection

Generate an application encryption key:

```sh
php artisan key:generate
```

Set the following values in `.env`, replacing the database name, username, and
password with the credentials for your local MySQL installation:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=forum_project
DB_USERNAME=root
DB_PASSWORD=your_local_password
```

Create the database named in `DB_DATABASE` on your MySQL server before running
migrations.

### 4. Create tables and sample data

```sh
php artisan migrate --seed
```

Migrations create the users, threads, categories, tags, replies, follower
relationships, and supporting Laravel tables. The seeder creates sample
threads and forum categories and tags. Thread factories also create the users
and categories required by those sample threads.

The seeder is intended for a fresh development database. It creates fixed
category and tag records, so do not run it repeatedly against an already-seeded
database unless you first reset that database.

### 5. Start the app

The Composer `dev` script starts the Laravel server, queue listener, log
viewer, and Vite development server together:

```sh
composer run dev
```

Open [http://localhost:8000](http://localhost:8000). Keep the terminal running
while you work; stop the processes with **Ctrl+C**.

Alternatively, run the backend and frontend separately in two terminals:

```sh
# Terminal 1: Laravel
php artisan serve
```

```sh
# Terminal 2: Vite
npm run dev
```

The app is available at the URL printed by `php artisan serve` (normally
`http://127.0.0.1:8000`).

## Useful commands

| Command | Purpose |
| --- | --- |
| `php artisan migrate` | Apply pending database migrations. |
| `php artisan migrate:fresh --seed` | Drop and recreate all tables, then seed a fresh development database. **Deletes existing local database data.** |
| `php artisan db:seed` | Run the database seeder against the configured database. |
| `php artisan route:list` | List registered application routes. |
| `php artisan test` | Run the Pest/PHPUnit test suites. |
| `composer test` | Clear Laravel's config cache and run the test suites. |
| `npm run dev` | Start Vite's frontend development server. |
| `npm run build` | Build frontend assets for deployment. |

The test configuration uses an in-memory SQLite database, array-backed cache
and sessions, and a synchronous queue. SQLite is used for tests only; tests
should not use or modify your local MySQL development database.

## Becoming an administrator locally

The database seeder does **not** create an administrator account. First
register and sign in through the application, then set `is_admin` for that
account using Tinker:

```sh
php artisan tinker
```

At the Tinker prompt, replace the email with the account you registered:

```php
$user = App\Models\User::where('email', 'you@example.com')->firstOrFail();
$user->is_admin = true;
$user->save();
```

After that, visit `/admin/dashboard`. Admin access is checked by the
`AdminMiddleware` middleware.

## Project structure

| Path | Contents |
| --- | --- |
| `app/Http/Controllers/` | Request handling for threads, replies, profiles, following, categories, and admin features. |
| `app/Http/Middleware/` | Request middleware, including the admin access check. |
| `app/Models/` | Eloquent models and relationships for users, threads, replies, categories, and tags. |
| `database/migrations/` | Database schema and incremental schema changes. |
| `database/factories/` and `database/seeders/` | Test/sample record factories and sample forum data. |
| `resources/js/Pages/` | Inertia/Vue pages for the home feed, threads, profiles, authentication, and admin screens. |
| `resources/js/Components/` and `resources/js/Layouts/` | Shared Vue UI components and layouts. |
| `resources/css/` | Application styles. |
| `routes/web.php` and `routes/auth.php` | Web and authentication route definitions. |
| `tests/` | Pest feature and unit tests. |

The Laravel controllers return Inertia pages; the corresponding Vue components
are resolved from `resources/js/Pages/`. The `resources/js/app.js` entry point
sets up Inertia, Vue, Ziggy route helpers, and the user/admin layouts.

## Local environment notes

- If you change `.env` after configuration has been cached, run
  `php artisan config:clear`.

## Deployment

The GitHub Actions workflow in `.github/workflows/deploy.yml` runs on pushes to
`master` and deploys to a configured server over SSH. It expects the
`SERVER_SECRET_KEY` GitHub Actions secret and the matching remote server
configuration to be set up. Review that workflow and its target environment
before enabling or changing deployments.
