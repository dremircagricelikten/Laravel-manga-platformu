# 🚨 VERİTABANI BİLGİLERİNİ GİRİN

## Seçenek 1: Web Installer Kullanın (ÖNERİLEN)

**En kolay yol:**

1. Tarayıcıda açın:
```
https://deneme.mangadiyari.com/install
```

2. **Database Configuration** ekranında:
   - Database Host: `localhost`
   - Database Port: `3306`
   - Database Name: **Plesk'teki database adınız**
   - Database Username: **Plesk'teki user adınız**
   - Database Password: **Plesk'teki şifreniz**

3. **Test Connection** → **Run Migrations** → İşlem tamamlanacak!

---

## Seçenek 2: Manuel .env Düzenleme

Eğer terminal kullanıyorsanız:

### 1. Plesk'te Database Bilgilerini Bulun

**Plesk → Databases** bölümünde:
- Database adı (örn: `md_deneme`)
- User adı (örn: `md_user`)
- Password

### 2. .env Dosyasını Düzenleyin

Plesk File Manager → `.env` dosyasını açın:

```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=md_deneme          # ← Kendi database adınızı yazın
DB_USERNAME=md_user            # ← Kendi user adınızı yazın
DB_PASSWORD=YourActualPassword # ← Kendi şifrenizi yazın
```

### 3. Cache Temizleyin

```bash
cd /var/www/vhosts/mangadiyari.com/deneme.mangadiyari.com
/opt/plesk/php/8.3/bin/php artisan config:clear
```

### 4. Migration'ları Çalıştırın

```bash
/opt/plesk/php/8.3/bin/php artisan migrate --force
```

---

## ⚠️ Önemli Notlar

1. **Database mutlaka Plesk'te oluşturulmuş olmalı**
2. **User, database'e erişim hakkına sahip olmalı**
3. **Web installer kullanırsanız** database bilgilerini otomatik kaydeder

---

## 🔍 Plesk'te Database Nasıl Oluşturulur?

1. Plesk → **Databases** → **Add Database**
2. Database Name: `md_deneme`
3. **Create** butonuna tıklayın
4. **Add Database User** → Username, Password girin
5. Credentials'ı `.env` dosyasına kopyalayın

---

Hangi yöntemi tercih ediyorsunuz?
