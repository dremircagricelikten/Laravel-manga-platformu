# PLESK HIZLI KURULUM REHBERİ

## 🚨 Şu Anda Aldığınız Hata

```
vendor/autoload.php: No such file or directory
```

**Sebep:** `composer install` henüz çalıştırılmadı.

---

## ✅ Adım Adım Kurulum

### 1. Plesk'te Composer Çalıştırma

**Yöntem A: Plesk Composer Aracı (Varsa)**
1. Plesk'te domain'inize tıklayın
2. **"Composer"** aracını bulun
3. `install` butonuna tıklayın
4. Bekleyin (2-3 dakika sürebilir)

**Yöntem B: SSH Terminal (Önerilen)**
```bash
cd /var/www/vhosts/mangadiyari.com/deneme.mangadiyari.com
/opt/plesk/php/8.3/bin/php /usr/lib/plesk-9.0/composer.phar install --no-dev
```

**Yöntem C: Yerel Bilgisayarda**
```bash
# Kendi bilgisayarınızda:
cd C:\Users\Emir\Desktop\Laravel Manga Platformu
composer install --no-dev

# Sonra TÜMÜNÜ (vendor dahil) Plesk'e yükleyin
```

---

### 2. APP_KEY Oluşturma

SSH ile:
```bash
cd /var/www/vhosts/mangadiyari.com/deneme.mangadiyari.com
/opt/plesk/php/8.3/bin/php artisan key:generate
```

VEYA `.env` dosyasını manuel düzenleyin:
```
APP_KEY=base64:BURAYA32KARAKTERLIKBIRSIFREGIRIN
```

---

### 3. Dizin İzinleri (ÇOK ÖNEMLİ!)

SSH ile:
```bash
cd /var/www/vhosts/mangadiyari.com/deneme.mangadiyari.com
chmod -R 775 storage bootstrap/cache
chown -R youruser:psaserv storage bootstrap/cache
```

Veya Plesk File Manager'dan:
- `storage/` → Sağ tık → Permissions → 775
- `bootstrap/cache/` → Sağ tık → Permissions → 775

---

### 4. Document Root Ayarı

1. Plesk → Domain → **Hosting Settings**
2. **Document root** değiştir:
```
/httpdocs/public
```
Veya
```
/deneme.mangadiyari.com/public
```

**UYARI:** Root dizin `/public` olmalı!

---

### 5. Database Yapılandırması

`.env` dosyasını düzenleyin:
```env
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=deneme_manga_cms
DB_USERNAME=deneme_manga_user
DB_PASSWORD=YourDatabasePassword
```

Plesk → **Databases** bölümünden:
- Database oluşturun
- User oluşturun
- Credentials'ı `.env`'ye kopyalayın

---

### 6. Web Installer'a Erişim

Artık şu adresi ziyaret edebilirsiniz:
```
https://deneme.mangadiyari.com/install
```

---

## 🔧 Sorun Giderme

### Hata: "500 Internal Server Error"

**Çözüm 1:** Cache temizleme
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

**Çözüm 2:** `.env` dosyasının varlığını kontrol edin
```bash
ls -la .env
```

### Hata: "Class not found"

**Çözüm:** Autoload yenileme
```bash
composer dump-autoload
```

### Hata: "Permission denied"

**Çözüm:** İzinleri düzeltin
```bash
chmod -R 775 storage bootstrap/cache
```

---

## 📋 Kontrol Listesi

- [ ] Tüm dosyalar yüklendi
- [ ] `composer install` çalıştırıldı
- [ ] `.env` dosyası oluşturuldu
- [ ] `APP_KEY` ayarlandı
- [ ] Document root `/public` olarak ayarlandı
- [ ] `storage/` ve `bootstrap/cache/` yazılabilir (775)
- [ ] Database oluşturuldu
- [ ] Database credentials `.env`'de doğru

---

## 🎯 Kısa Yol (En Kolay)

### Yerel Bilgisayarınızda:

1. **Composer Install:**
```bash
cd "C:\Users\Emir\Desktop\Laravel Manga Platformu"
composer install --no-dev
```

2. **APP_KEY oluştur:**
```bash
php artisan key:generate
```

3. **TÜMÜNÜ Plesk'e yükle** (vendor dahil)
   - FTP veya File Manager ile tüm dosyaları yükleyin

4. **Plesk'te sadece izinleri ayarla:**
   - `storage/` → 775
   - `bootstrap/cache/` → 775

5. **Tarayıcıda aç:**
```
https://deneme.mangadiyari.com/install
```

---

## 💡 Önerilen Yaklaşım

**EN KOLAY:** Yerel bilgisayarınızda `composer install` yapıp vendor ile birlikte yükleyin.

**PROFESYONEl:** Plesk SSH'ta composer çalıştırın (vendor klasörü 50-100 MB olabilir, upload uzun sürer).

---

Hangi yöntemi tercih edersiniz?
