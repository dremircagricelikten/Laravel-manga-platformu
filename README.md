# Manga/Novel/Anime CMS Platform

##Complete Content Management System built with Laravel 11 and FilamentPHP v3

A comprehensive CMS specifically designed for managing Manga, Novels, and Anime content with time-gated monetization using "Ki" coins.

## ✨ Features

### Content Management
- **Polymorphic Series Support**: Manage Manga, Novels, and Anime in one system
- **Volume Organization**: Group chapters into volumes
- **Chapter Management**: Support for images (manga), rich text (novels), and video embed (anime)
- **Bulk Upload**: Upload multiple chapters at once via Filament admin
- **Scheduled Publishing**: Set future publish dates for chapters

### Monetization System  
- **Ki Coin Economy**: Virtual currency for unlocking premium content
- **Time-Gated Access**: Chapter automatically become free after X days (default: 3 days)
- **Flexible Pricing**: Set custom unlock costs per chapter
- **Coin Packages**: Sell coin bundles with bonus coins
- **Transaction Logging**: Complete audit trail of all coin movements

### User Management
- **Role-Based Access**: Using Spatie/laravel-permission
- **VIP System**: VIP members with expiration dates
- **User Wallets**: Ki coin balance tracking
- **Chapter Unlock History**: Track which chapters users have accessed
- **Shopping Cart**: Purchase coin packages

### Admin Panel (FilamentPHP v3)
- **Dashboard**: Sales, views, and upload statistics
- **Series Management**: Full CRUD with categories and cover images
- **Chapter Management**: Type-specific forms (manga/novel/anime)
- **User Management**: Edit balances, assign roles
- **Site Settings**: Comprehensive settings system with tabs:
  - General (site name, logo, etc.)
  - Appearance (colors, dark mode)
  - Social Media links
  - SEO configuration
  - Economy settings
- **Menu Manager**: Drag-and-drop navigation builder
- **Page Builder**: Create custom pages (About, FAQ, etc.)

### Frontend (VallrScans-Style)
- **Dark Theme**: Modern black background design
- **Hero Slider**: Showcase featured series
- **Card Grid Layout**: Beautiful series cards with hover effects
- **Status Badges**: Visual indicators (New, Manga, Completed, etc.)
- **Responsive Design**: Mobile-first approach
- **Chapter Reader**: Optimized reading experience

### Technical Features
- **FTP Storage Support**: Store manga images on remote FTP server
- **MySQL Database**: Full utf8mb4 support for international characters
- **Service Layer Architecture**: Clean separation of business logic
- **Custom Middleware**: Chapter access control
- **Settings System**: Cached site configuration
- **Soft Deletes**: Safe content management

## 📋 Requirements

- PHP >= 8.2
- MySQL >= 8.0
- Composer
- Node.js & NPM (for frontend assets)

## 🚀 Installation on Plesk

### Step 1: Create Laravel Application in Plesk

1. Log in to your Plesk panel
2. Go to **Domains** → Select your domain
3. Go to **Git** (if available) or **File Manager**
4. Upload all files from this directory to your domain's root directory

### Step 2: Install Dependencies

Via Plesk **SSH Terminal** or use Plesk **Composer** tool:

```bash
composer install --optimize-autoloader --no-dev
```

### Step 3: Configure Environment

1. Copy `.env.example` to `.env`:
```bash
cp .env.example .env
```

2. Generate application key:
```bash
php artisan key:generate
```

3. Edit `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### Step 4: Run Migrations

```bash
php artisan migrate
```

### Step 5: Install FilamentPHP

```bash
php artisan filament:install --panels
```

Create admin user:
```bash
php artisan make:filament-user
```

### Step 6: Set Up Storage

```bash
php artisan storage:link
```

### Step 7: Install Spatie Permissions

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

### Step 8: Seed Default Settings (Optional)

Create a seeder for default settings or add them via admin panel.

### Step 9: Set Correct Permissions

In Plesk, ensure these directories are writable:
- `storage/`
- `bootstrap/cache/`

### Step 10: Configure Document Root

In Plesk, set your domain's document root to:
```
/public
```

## 📝 Post-Installation Configuration

### Configure FTP Storage (Optional)

If using remote FTP for manga images, edit `.env`:

```env
MANGA_STORAGE_DRIVER=ftp_manga
FTP_HOST=your-ftp-host.com
FTP_USERNAME=your-username
FTP_PASSWORD=your-password
FTP_PORT=21
FTP_ROOT=/manga
FTP_SSL=false
```

### Install Frontend Dependencies

```bash
npm install
npm run build
```

### Set Up Cron Job (for scheduled tasks)

In Plesk, go to **Cron Jobs** and add:

```
* * * * * cd /your/domain/path && php artisan schedule:run >> /dev/null 2>&1
```

## 🎨 Customization

### Site Settings

Access via Admin Panel → Settings:
- **General**: Site name, logo, favicon
- **Appearance**: Colors, dark mode, items per page
- **Social Media**: Facebook, Twitter, Instagram links
- **SEO**: Meta tags, Analytics code
- **Economy**: Coin name, default costs, registration bonus

### Adding Filament Resources

Resources for Series and Chapters need to be created. See `implementation_plan.md` for complete resource code.

## 📚 Documentation

See `implementation_plan.md` for:
- Complete database schema
- Model relationships
- Service layer architecture
- Filament resource implementations
- Frontend component code

## 🛠️ Built With

- [Laravel 11](https://laravel.com) - PHP Framework
- [FilamentPHP v3](https://filamentphp.com) - Admin Panel
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission) - Role Management
- [MySQL](https://www.mysql.com) - Database
- [Tailwind CSS](https://tailwindcss.com) - Styling
- [Alpine.js](https://alpinejs.dev) - JavaScript Framework

## 📄 License

This project is open-sourced software.

## 🤝 Support

For issues and questions, please refer to the implementation plan or contact your developer.

---

**Note**: This is a complete CMS system. After installation, you'll need to:
1. Create Filament resources for Series, Chapters, etc. (code provided in implementation_plan.md)
2. Build frontend views and components (templates provided in implementation_plan.md)
3. Configure site settings via admin panel
4. Create roles and permissions
5. Add initial content
