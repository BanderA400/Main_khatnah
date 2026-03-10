# ختمة (Khatma)

تطبيق ويب لإدارة خطط ختم القرآن الكريم مع تتبع التقدم اليومي، سجل الإنجاز، ولوحة تحكم إدارية.

## المتطلبات

- PHP `8.3+`
- Composer
- Node.js `20.19+`
- قاعدة بيانات MySQL أو SQLite

## التقنيات الأساسية

- Laravel 12
- Filament 4 (لوحة المستخدم + مركز التحكم)
- Livewire
- Vite + Tailwind CSS

## التشغيل المحلي

1. تثبيت الاعتماديات:
```bash
composer install
npm install
```

2. إعداد البيئة:
```bash
cp .env.example .env
php artisan key:generate
```

3. إعداد قاعدة البيانات ثم الترحيل:
```bash
php artisan migrate
```

4. تشغيل التطبيق محليًا:
```bash
composer dev
```

## الاختبارات

لتشغيل جميع الاختبارات:
```bash
composer test
```

## أوامر إنتاج مهمة

بعد رفع نسخة جديدة:
```bash
php artisan migrate --force
php artisan optimize
```

وعند الحاجة لتفريغ الكاش:
```bash
php artisan optimize:clear
```

## ملاحظات تشغيلية

- مسار لوحة المستخدم: `/app`
- مسار مركز التحكم: `/control`
- النظام يعتمد التحقق البريدي قبل دخول اللوحات.
- عداد زيارات الصفحة الرئيسية يدعم التجميع الذري لتقليل التضارب تحت الضغط.
