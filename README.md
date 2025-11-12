# Restate (Laravel)

A Laravel-based real estate site. This README covers how to publish the project to GitHub and how someone else can set it up locally.

## 1) Prepare the repository safely

- Make sure secrets are not committed:
  - `.env` must NOT be committed
  - `LOGIN-DETAILS.txt` must NOT be committed
  - `vendor/` and `node_modules/` must NOT be committed
- This repo includes a `.gitignore` that already excludes those.
- Add a sanitized `.env.example` for others to copy.

## 2) Create the GitHub repository

- Go to https://github.com/new
- Repository name: `restate` (or your choice)
- Choose Public (shareable link) or Private (invite collaborators)
- Do NOT initialize with a README (you already have one)

## 3) Push local code (Windows PowerShell)

From the project root (e.g., `C:\xampp\htdocs\restate`):

```powershell
# initialize git
git init

# optional: set default branch to main
git branch -M main

# review what's going to be committed
git status

# add files respecting .gitignore
git add .

# commit
git commit -m "Initial commit: Laravel real estate site"

# add remote (replace <user>/<repo>)
git remote add origin https://github.com/<your-username>/<your-repo>.git

# push
git push -u origin main
```

## 4) Share access

- Public repo: share the URL
- Private repo: Settings → Collaborators → Add people (enter their GitHub username/email)

## 5) How others can run the project locally

Requirements:
- PHP 8.x
- Composer
- Node.js + npm
- MySQL (or compatible), and a database created (e.g., `restate`)

Steps:

```powershell
# clone
git clone https://github.com/<your-username>/<your-repo>.git
cd <your-repo>

# PHP dependencies
composer install

# JS/CSS dependencies
npm install

# copy env and set values
copy .env.example .env

# generate app key
php artisan key:generate

# configure DB in .env, then migrate and seed (if seeds exist)
php artisan migrate
# php artisan db:seed    # optional

# create storage symlink for public URLs
php artisan storage:link

# build frontend assets (or use vite dev)
npm run build
# npm run dev            # for development

# serve (or use your Apache/XAMPP vhost)
php artisan serve
```

Visit the site at the URL shown (e.g., http://127.0.0.1:8000) or your Apache vhost.

## 6) Environment notes

- Configure `.env` with your DB credentials and mail settings
- Never commit `.env` or credentials
- If you accidentally committed secrets, rotate them immediately and force-remove from history

## 7) Common Laravel maintenance

```powershell
# clear caches (when config/views get out of sync)
php artisan optimize:clear

# run tests (if present)
php artisan test
```

## 8) Troubleshooting

- 404s on uploaded images: ensure `php artisan storage:link` was run
- White page/500: check `storage/logs/laravel.log`
- Node build errors: delete `node_modules`, run `npm ci` or `npm install` again
- Composer issues: delete `vendor`, run `composer install`
