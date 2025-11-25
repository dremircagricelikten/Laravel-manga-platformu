# NEXT STEPS - AFTER INSTALLATION

## Files You Need to Create (Code Templates in implementation_plan.md)

### 1. Filament Resources

#### SeriesResource
**Location:** `app/Filament/Resources/SeriesResource.php`
- Full CRUD for manga/novel/anime series
- Form with type selection, title, slug, cover image
- Categories relationship (many-to-many)
- Table with filters and search

#### ChapterResource  
**Location:** `app/Filament/Resources/ChapterResource.php`
- Dynamic forms based on series type
- Manga: Multiple image upload
- Novel: Rich text editor
- Anime: Video embed field
- Premium/unlock cost settings
- Publishing controls

#### BulkUploadChapters Page
**Location:** `app/Filament/Resources/ChapterResource/Pages/BulkUploadChapters.php`
- Repeater field for multiple chapters
- Batch chapter creation

#### CoinPackageResource
**Location:** `app/Filament/Resources/CoinPackageResource.php`
- Package name, coin amount, price
- Bonus coins
- Active/inactive toggle

#### UserResource (Enhanced)
**Location:** `app/Filament/Resources/UserResource.php`
- View Ki balance
- Manual balance adjustment
- Role assignment
- VIP status management

#### TransactionResource
**Location:** `app/Filament/Resources/TransactionResource.php`
- Read-only transaction logs
- Filters by type
- User relationship display

#### SiteSettings Page
**Location:** `app/Filament/Pages/SiteSettings.php`
- Tabs form for settings groups
- Save functionality with cache clearing
- Settings helper integration

---

### 2. Frontend Components (Blade)

#### Layouts
- `resources/views/layouts/app.blade.php` - Main layout with navigation
- `resources/views/layouts/navigation.blade.php` - Top navigation bar
- `resources/views/layouts/footer.blade.php` - Footer

#### Components
- `resources/views/components/series-card.blade.php` - Series card with cover and badges
- `resources/views/components/hero-slider.blade.php` - Featured series carousel
- `resources/views/components/status-badge.blade.php` - Badge component
- `resources/views/components/trending-section.blade.php` - Trending series grid

#### Pages
- `resources/views/pages/home.blade.php` - Homepage with hero + trending
- `resources/views/pages/series/index.blade.php` - All series browse page
- `resources/views/pages/series/show.blade.php` - Series detail page
- `resources/views/pages/chapters/reader.blade.php` - Chapter reader
- `resources/views/pages/chapters/unlock.blade.php` - Unlock chapter page
- `resources/views/pages/library.blade.php` - User library
- `resources/views/pages/profile.blade.php` - User profile

---

### 3. Controllers

#### HomeController
**Location:** `app/Http/Controllers/HomeController.php`
- Fetch featured series for hero slider
- Fetch trending series
- Fetch latest updates

#### SeriesController
**Location:** `app/Http/Controllers/SeriesController.php`
- Index: Browse all series
- Show: Series detail with chapters list

#### ChapterController  
**Location:** `app/Http/Controllers/ChapterController.php`
- Show: Chapter reader (with middleware)
- Unlock form and process

#### WalletController
**Location:** `app/Http/Controllers/WalletController.php`
- Display wallet balance
- Transaction history

#### CartController
**Location:** `app/Http/Controllers/CartController.php`
- Add coin package to cart
- Checkout process

---

### 4. Routes

#### Web Routes
**Location:** `routes/web.php`

```php
// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// Series
Route::get('/series', [SeriesController::class, 'index'])->name('series.index');
Route::get('/series/{series:slug}', [SeriesController::class, 'show'])->name('series.show');

// Chapters (with middleware)
Route::middleware(['auth', 'chapter.access'])->group(function () {
    Route::get('/chapters/{chapter}', [ChapterController::class, 'show'])->name('chapters.show');
});

Route::get('/chapters/{chapter}/unlock', [ChapterController::class, 'unlock'])->name('chapters.unlock');
Route::post('/chapters/{chapter}/unlock', [ChapterController::class, 'processUnlock'])->name('chapters.process-unlock');

// Wallet
Route::middleware('auth')->group(function () {
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
});
```

---

### 5. Seeders (Recommended)

#### SettingsSeeder
**Location:** `database/seeders/SettingsSeeder.php`

Default settings:
- site_name
- coin_name = "Ki"
- default_chapter_cost = 10
- default_lock_duration = 3
- primary_color
- dark_mode_enabled = true

#### RolesSeeder
**Location:** `database/seeders/RolesSeeder.php`

Create roles:
- Super Admin
- Admin
- Editor
- Uploader
- VIP User

---

### 6. Frontend Assets

#### Tailwind CSS Configuration
**Location:** `tailwind.config.js`

See implementation_plan.md for dark theme configuration.

#### Alpine.js Integration
**Location:** `resources/js/app.js`

```javascript
import Alpine from 'alpinejs'
window.Alpine = Alpine
Alpine.start()
```

#### Swiper.js for Hero Slider
Install via NPM:
```bash
npm install swiper
```

---

## Quick Start After Installation

### 1. Access Admin Panel

Navigate to: `https://yourdomain.com/admin`

Login with the admin user you created.

### 2. Configure Site Settings

Once you create the SiteSettings page (code in implementation_plan.md):
- Set site name
- Upload logo
- Set primary/secondary colors
- Add social media links
- Configure economy settings

### 3. Create Categories

Via Filament → Categories:
- Action
- Romance
- Fantasy
- Isekai
- etc.

### 4. Create Coin Packages

Via Filament → Coin Packages:
- Starter Pack: 100 Ki - $0.99
- Popular Pack: 500 Ki - $4.99
- Ultimate Pack: 1000 Ki + 100 bonus - $9.99

### 5. Add Your First Series

Via Filament → Series:
- Type: Manga
- Title: "Your Manga Name"
- Upload cover image
- Select categories
- Status: Ongoing

### 6. Upload Chapters

Via Filament → Chapters → Bulk Upload:
- Select series
- Add multiple chapters
- Upload images (for manga)
- Set premium/free status
- Set publish dates

---

## Development Workflow

1. **Local Development** (Optional)
   - Install Laravel on local machine
   - Develop and test features
   - Push to Git
   
2. **Staging on Plesk**
   - Use subdomain for staging
   - Test before production

3. **Production Deployment**
   - Deploy to main domain
   - Run migrations
   - Clear cache

---

## Testing Checklist

- [ ] Admin login works
- [ ] Can create series
- [ ] Can upload chapters
- [ ] Images display correctly
- [ ] Chapter unlock logic works
- [ ] Ki coin transactions log correctly
- [ ] Frontend displays properly
- [ ] Mobile responsive design works
- [ ] Navigation menu works
- [ ] Search functionality (when implemented)

---

## Performance Optimization

### Enable Caching
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Image Optimization
- Use WebP format for manga images
- Implement lazy loading on frontend

### Database Optimization
- Add indexes (already in migrations)
- Regular OPTIMIZE TABLE commands

---

## Code References

All code templates and full implementations are in:
**`implementation_plan.md`**

This includes:
- Complete Filament Resource code
- Blade component templates
- Controller examples
- Service layer methods
- Middleware implementation

---

## Need Help?

1. Check `implementation_plan.md` - Complete architecture documentation
2. Check `README.md` - Feature overview and basic setup
3. Check `PLESK_DEPLOYMENT.md` - Deployment troubleshooting
4. Review Laravel documentation: https://laravel.com/docs
5. Review FilamentPHP documentation: https://filamentphp.com/docs

---

**You're all set! Start building your Manga CMS! 🚀**
