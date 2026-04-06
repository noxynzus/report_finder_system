# report_finder_system

# 🚀 Quick Start Guide - 5 นาที

เริ่มใช้งาน Report Management System ภายใน 5 นาที!

## 📋 สิ่งที่ต้องเตรียม

- ✅ PHP 8.2 หรือสูงกว่า
- ✅ Composer
- ✅ Node.js & NPM
- ✅ PostgreSQL 15
- ✅ Git

## ⚡ ติดตั้งแบบเร็ว

### Option 1: ใช้ Laravel ที่มีอยู่แล้ว

```bash
cd your-laravel-project

# คัดลอกไฟล์ทั้งหมดจาก Artifacts ไปยัง:
# 1. app/Http/Controllers/ReportController.php
# 2. app/Models/Report.php
# 3. resources/views/layouts/app.blade.php
# 4. resources/views/reports/index.blade.php
# 5. database/migrations/xxxx_create_reports_table.php

# ติดตั้ง Authentication
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build

# Setup
php artisan migrate
php artisan storage:link

# สร้าง test user
php artisan tinker
>>> User::create(['name'=>'Admin','email'=>'admin@example.com','password'=>Hash::make('password')]);

# รัน!
php artisan serve
```

### Option 2: สร้างโปรเจคใหม่

```bash
# 1. สร้างโปรเจค
composer create-project laravel/laravel report-system
cd report-system

# 2. ติดตั้ง Breeze
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build

# 3. ตั้งค่า .env
nano .env
# แก้ไข DB_* values

# 4. คัดลอกไฟล์จาก Artifacts (ดูรายละเอียดด้านล่าง)

# 5. Migrate
php artisan migrate

# 6. Storage link
php artisan storage:link

# 7. สร้าง test user
php artisan db:seed --class=TestUserSeeder

# 8. รัน!
php artisan serve
```

## 📂 ไฟล์ที่ต้องสร้าง/คัดลอก

### 1. Migration (สร้างอัตโนมัติ)

```bash
php artisan make:migration create_reports_table
```

คัดลอกเนื้อหาจาก artifact: **report_management_system**

### 2. Model

สร้างไฟล์: `app/Models/Report.php`
```bash
nano app/Models/Report.php
```
คัดลอกจาก artifact

### 3. Controller

สร้างไฟล์: `app/Http/Controllers/ReportController.php`
```bash
nano app/Http/Controllers/ReportController.php
```
คัดลอกจาก artifact

### 4. Views

สร้าง directories:
```bash
mkdir -p resources/views/layouts
mkdir -p resources/views/reports
```

สร้างไฟล์:
- `resources/views/layouts/app.blade.php`
- `resources/views/reports/index.blade.php`

คัดลอกจาก artifact

### 5. Routes

แก้ไข `routes/web.php` เพิ่มบรรทัดนี้:

```php
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return redirect()->route('reports.index');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/reports/{report}/download', [ReportController::class, 'download'])->name('reports.download');
    Route::get('/reports/{report}/preview', [ReportController::class, 'preview'])->name('reports.preview');
    Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');
});
```

### 6. Seeder (Optional แต่แนะนำ)

สร้างไฟล์: `database/seeders/TestUserSeeder.php`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );
    }
}
```

## 🗄️ ตั้งค่า Database

### PostgreSQL

```bash
# เข้า PostgreSQL
psql -U postgres

# สร้าง database
CREATE DATABASE report_management;

# สร้าง user (optional)
CREATE USER laravel WITH PASSWORD 'secret';
GRANT ALL PRIVILEGES ON DATABASE report_management TO laravel;

\q
```

### แก้ไข .env

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=report_management
DB_USERNAME=laravel
DB_PASSWORD=secret
```

## ✅ Checklist ก่อนรัน

```
☐ ติดตั้ง Laravel Breeze แล้ว
☐ คัดลอก Migration, Model, Controller แล้ว
☐ คัดลอก Views ทั้ง 2 ไฟล์แล้ว
☐ แก้ไข Routes แล้ว
☐ ตั้งค่า Database ใน .env แล้ว
☐ รัน php artisan migrate แล้ว
☐ รัน php artisan storage:link แล้ว
☐ สร้าง test user แล้ว
☐ รัน npm run build แล้ว
```

## 🎯 ทดสอบว่าทำงาน

```bash
# รัน server
php artisan serve

# เปิดเบราว์เซอร์
http://localhost:8000

# Login
Email: admin@example.com
Password: password

# ทดสอบ Upload
1. คลิก "Upload New Report"
2. กรอกข้อมูล
3. เลือกไฟล์ PDF
4. คลิก Upload

# ทดสอบ Preview
คลิกปุ่ม "Preview" ที่รายงาน

# ทดสอบ Download
คลิกปุ่ม "Download" ที่รายงาน

# ทดสอบ Search
พิมพ์คำค้นหาในช่อง Search
```

## 🐛 แก้ปัญหาเร็ว

### Error: Class "App\Models\Report" not found

```bash
composer dump-autoload
```

### Error: Storage link not found

```bash
php artisan storage:link
chmod -R 775 storage
```

### Error: npm not found

```bash
# ติดตั้ง Node.js จาก nodejs.org
npm install
npm run build
```

### Error: SQLSTATE[08006] Connection refused

```bash
# ตรวจสอบ PostgreSQL รันอยู่หรือไม่
sudo systemctl status postgresql

# เริ่ม PostgreSQL
sudo systemctl start postgresql

# ตรวจสอบ .env
cat .env | grep DB_
```

### Error: 419 Page Expired (CSRF)

```bash
# Clear cache
php artisan config:clear
php artisan cache:clear
```

## 📝 คำสั่งที่ใช้บ่อย

```bash
# ดู routes
php artisan route:list

# Clear all cache
php artisan optimize:clear

# Create user via tinker
php artisan tinker
>>> User::create(['name'=>'Test','email'=>'test@test.com','password'=>Hash::make('password')]);

# Check logs
tail -f storage/logs/laravel.log

# Reset database
php artisan migrate:fresh --seed
```

## 🎉 สำเร็จ!

ตอนนี้คุณสามารถใช้งานระบบได้แล้ว:

- 📋 ดูรายการรายงาน
- ⬆️ อัพโหลดรายงาน PDF
- 👁️ Preview รายงาน
- ⬇️ Download รายงาน
- 🔍 ค้นหารายงาน
- 🏷️ จัดกลุ่มตามหมวดหมู่

## 📞 ต้องการความช่วยเหลือ?

ตรวจสอบ:
1. `README.md` - คู่มือละเอียด
2. `storage/logs/laravel.log` - Log files
3. Laravel Documentation - https://laravel.com/docs

---

**Happy Coding! 🚀**