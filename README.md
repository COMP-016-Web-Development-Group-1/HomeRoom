# 🏡 HomeRoom

## 🚀 Getting Started

Follow these steps to get the project running locally.
Laravel Herd is the recommended environment for this project, but alternatives are included below.

---

### 1. Set Up the Project Directory

If you're using **Laravel Herd**, run:

```bash
cd ~/Herd
```

Otherwise, use any location you prefer:

```bash
cd <your-project-folder>
```

Then clone the repository:

```bash
git clone https://github.com/COMP-016-Web-Development-Group-1/HomeRoom.git
```

After cloning, make sure you enter the project folder:

```bash
cd HomeRoom
```

---

### 2. Install Dependencies

```bash
composer install
npm install && npm run build
```

---

### 3. Environment Configuration

Copy the example `.env` file:

```bash
cp .env.example .env
```

Then open `.env` and fill in the following fields:

```env
DEFAULT_LANDLORD_NAME=
DEFAULT_LANDLORD_EMAIL=
DEFAULT_LANDLORD_PASSWORD=
```

---

### 4. Create the SQLite Database

This project uses SQLite for local development.

#### Mac/Linux or Git Bash:

```bash
touch database/database.sqlite
```

#### PowerShell:

```powershell
New-Item -ItemType Directory -Path "database"
New-Item -ItemType File -Path "database/database.sqlite"
```

#### CMD:

```cmd
mkdir database
type NUL > database\database.sqlite
```

Alternatively, you can **manually create a blank file** named `database.sqlite` in the `database` directory.

---

### 5. Generate App Key

```bash
php artisan key:generate
```

---

### 6. Link Storage

```bash
php artisan storage:link
```

---

### 7. Migrate and Seed the Database

```bash
php artisan migrate --seed
```

---

### 8. Serve the Application

If you're using **Laravel Herd**, simply visit:

```
http://homeroom.test
```

If you're **not using Herd**, start the server with:

```bash
php artisan serve
```

Then visit the URL provided in the terminal, e.g.:

```
http://127.0.0.1:8000
```

---

## 🛠️ Developing

Common tasks while working on the project:

### Recompile Frontend Assets

Use this to build and watch for changes during development:

```bash
composer run dev
```

> Note: This runs `npm run dev` internally, but keeps everything Laravel-flavored.

---

### Reset the Database

Use this to wipe and re-seed your database:

```bash
php artisan migrate:fresh --seed
```

---

### Run Tests

```bash
php artisan test
```

---

### Format Code

```bash
vendor/bin/pint
```

---

## 🤝 Contributing

### Contribution Guide

1. Ensure you're on the latest `main` branch:

    ```bash
    git checkout main
    git pull origin main
    ```

2. Create a new branch:

    ```bash
    git checkout -b <type>/<short-task-desc>
    ```

    **Examples:**

    - `feat/login-form`
    - `fix/navbar-alignment`
    - `docs/update-readme`

    **Types:**

    - `feat` → New feature
    - `fix` → Bug fix
    - `refactor` → Code clean-up
    - `chore` → Config or dependency update
    - `docs` → Documentation changes

3. Confirm your current branch:

    ```bash
    git branch
    ```

4. Commit your work:

    ```bash
    git add -A
    git commit -m "<type>: <short-description>"
    ```

5. Push to GitHub:

    ```bash
    git push origin <your-branch-name>
    ```

6. Open a Pull Request:

    - Go to: [GitHub Pull Requests](https://github.com/COMP-016-Web-Development-Group-1/HomeRoom/pulls)
    - Base: `main`, Compare: your branch
    - Add a clear title and description

7. **Notify the team**

    Let the team know in the Messenger group chat once your PR is ready or if you need help.
