# Ashford Communities – Analytics & Dashboards

A PHP dashboard portal that embeds Power BI reports, deployed on Vercel.

## Files
```
ashford-dashboard/
├── api/
│   └── index.php   ← the whole site (home page + dashboard pages)
├── vercel.json     ← tells Vercel to run PHP and routes every URL to index.php
├── .gitignore
└── README.md
```

## Pages
- `/` – home page with dashboard buttons
- `/sales-dashboard` – Sales Dashboard (Power BI)

## Add another dashboard
Open `api/index.php` and add a block to the `$dashboards` array:
```php
'marketing-dashboard' => [
    'title' => 'Marketing Dashboard',
    'icon'  => 'megaphone',        // any name from icons.getbootstrap.com
    'color' => '#7fbf6e',
    'src'   => 'https://app.powerbi.com/view?r=....',
],
```
Commit and push. Vercel redeploys automatically, and the new button, sidebar link and page appear.

## Run locally (optional)
```
php -S localhost:8000 api/index.php
```
Then open http://localhost:8000

## Deploy
1. Create a new GitHub repository and upload these files (keep the `api` folder).
2. On vercel.com → Add New → Project → Import the GitHub repo.
3. Leave Framework Preset as **Other**, no build command. Click **Deploy**.
