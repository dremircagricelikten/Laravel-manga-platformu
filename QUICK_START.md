# 🚀 LARAVEL MANGA CMS - QUICK START

## Installation via Web Installer (2-3 Minutes!)

### Step 1: Upload Files to Server
Upload all files to your Plesk domain directory via FTP or File Manager.

### Step 2: Set Document Root
In Plesk → Hosting Settings, set document root to `/public`

### Step 3: Run Web Installer
Visit: `https://yourdomain.com/install`

Follow the wizard:
1. **Welcome** - Click "Get Started"
2. **Requirements** - Verify all checks pass
3. **Database** - Enter MySQL credentials → Test → Run Migrations
4. **Admin** - Create your admin account
5. **Complete** - Installation done!

### Step 4: Access Admin Panel
Visit: `https://yourdomain.com/admin`

Login with credentials created in Step 3.

---

## What's Included

### ✅ FilamentPHP Admin Resources (Complete!)
- **Series Management** - Add manga/novel/anime
- **Chapter Management** - With bulk upload feature
- **Coin Packages** - Set up Ki coin bundles
- **User Management** - Edit balances, assign VIP
- **Transactions** - View all coin movements
- **Site Settings** - Configure everything (tabs!)

### ✅ Database & Backend (Complete!)
- 15 database tables
- 14 Eloquent models
- Wallet system
- Chapter unlock logic
- Transaction logging

### ✅ Features Ready to Use
- Time-gated chapter monetization
- Ki coin economy
- VIP user system
- FTP storage support
- Role-based permissions

---

## After Installation

### 1. Configure Site Settings
Admin → Settings → Site Settings

Configure:
- Site name & logo
- Colors & appearance
- Coin name ("Ki")
- Default chapter costs
- Social media links
- SEO settings

### 2. Create Categories
Admin → Categories → Create

Examples: Action, Romance, Fantasy, Isekai

### 3. Create Coin Packages
Admin → Coin Packages → Create

Example:
- Starter Pack: 100 Ki - $0.99
- Popular Pack: 500 Ki + 50 bonus - $4.99

### 4. Add Your First Series
Admin → Series → Create

- Choose type (manga/novel/anime)
- Upload cover image
- Select categories
- Save!

### 5. Upload Chapters

**Single Upload:**
Admin → Chapters → Create

**Bulk Upload:**
Admin → Chapters → Bulk Upload
- Select series
- Add multiple chapters at once
- Upload images (for manga)

---

## File Structure

```
Laravel Manga Platformu/
├── app/
│   ├── Filament/
│   │   ├── Resources/              # All admin resources
│   │   │   ├── SeriesResource.php
│   │   │   ├── ChapterResource.php
│   │   │   ├── CoinPackageResource.php
│   │   │   ├── UserResource.php
│   │   │   └── TransactionResource.php
│   │   └── Pages/
│   │       └── SiteSettings.php    # Site configuration
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── InstallController.php  # Web installer
│   │   └── Middleware/
│   │       └── CheckChapterAccess.php
│   ├── Models/                     # 14 Eloquent models
│   └── Services/                   # WalletService, UnlockChapterService
├── database/
│   └── migrations/                 # 15 migrations
├── resources/
│   └── views/
│       └── install/                # Installer views
├── routes/
│   └── install.php                 # Installation routes
├── .env.example
├── composer.json
├── README.md
├── PLESK_DEPLOYMENT.md
└── NEXT_STEPS.md
```

---

## Quick Reference

### Admin Panel URL
```
https://yourdomain.com/admin
```

### Installation URL
```
https://yourdomain.com/install
```

### Reset Installation
Delete this file to re-run installer:
```
storage/installed
```

---

## Troubleshooting

### Issue: Requirements not met
**Solution:** Enable missing PHP extensions in Plesk → PHP Settings

### Issue: Database connection failed
**Solution:** Verify database exists in Plesk → Databases

### Issue: 500 Error
**Solution:** Check directory permissions (storage/, bootstrap/cache/)

---

## Support & Documentation

- **Full Guide:** `PLESK_DEPLOYMENT.md`
- **Features:** `README.md`
- **Technical Docs:** `walkthrough.md`
- **Next Steps:** `NEXT_STEPS.md`

---

## 🎉 You're All Set!

Your Manga CMS is installed and ready to use!

Start adding content via the admin panel at `/admin`

**Total Installation Time:** ~2-3 minutes ⚡
