# 🐺 WolfLab - Cyber Security Educational Laboratory

<div align="center">

```text
 __      __      .__   _____.__          ___.   
/  \    /  \____ |  |_/ ____\  | _____   \_ |__ 
\   \/\/   /  _ \|  |\   __\|  | \__  \   | __ \
 \        (  <_> )  |_|  |  |  |__/ __ \_ | \_\ \
  \__/\  / \____/|____/__|  |____(____  / |___  /
       \/                             \/      \/ 
```

### **Bilingual Web Application Penetration Testing Lab (English / العربية)**

Developed by **[Gouda Nasralla](https://github.com/GoudaWolfDev)**

[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%207.4-8892BF?style=for-the-badge&logo=php)](https://www.php.net/)
[![SQLite Version](https://img.shields.io/badge/SQLite-3-003B57?style=for-the-badge&logo=sqlite)](https://www.sqlite.org/)
[![Platform](https://img.shields.io/badge/Platform-Kali%20Linux%20%7C%20Linux-green?style=for-the-badge&logo=linux)](https://www.kali.org/)
[![License](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](https://opensource.org/licenses/MIT)

---

[**English Guide**](#english-version) • [**الدليل العربي**](#العربية)

</div>

---

# English Version

## ⚠️ Important Disclaimer
> [!CAUTION]
> **This project is for educational and research purposes only.**
> WolfLab is designed as a local sandbox utility to help web developers and cybersecurity enthusiasts understand vulnerabilities, defensive mechanics, and secure coding practices. Running these exploitation techniques against unauthorized external hosts is illegal and subject to criminal liability. The developer is not liable for any misuse of this project.

---

## 📌 Project Overview
**WolfLab** is a lightweight, self-contained educational login portal simulating an administrative panel. It is built natively on top of **PHP** and **SQLite** to bypass complex database server setups (e.g., MySQL or XAMPP). 

It highlights key OWASP Top 10 vulnerabilities:
1. **SQL Injection (SQLi Bypass)** in the login authentication mechanism.
2. **Sensitive Information Exposure** through verbose database error outputs.
3. **Insecure Session Management**.

---

## 📂 Project Structure
```text
WolfLab/
│
├── login.php          # Login portal containing the SQL Injection flaw
├── dashboard.php      # Cyberpunk-styled security command panel
├── logout.php         # Destroys active user sessions and cookies
├── init_db.php        # Database initialization script
├── style.css          # Core CSS variables and animations
└── README.md          # Project documentation (Bilingual)
```

---

## 🚀 Installation & Local Hosting on Kali Linux

### 1. Install Dependencies
Ensure PHP and the SQLite extension are installed:
```bash
sudo apt update
sudo apt install php php-sqlite3 -y
```

### 2. Enter the Project Directory
```bash
cd lab-pentesting
```

### 3. Initialize the Database
Generate the `users.db` SQLite file containing the default credentials:
```bash
php init_db.php
```

### 4. Fire Up the Server
Host the lab locally on Port `8000`:
```bash
php -S 0.0.0.0:8000
```
Open your browser and navigate to:  
🔗 **`http://localhost:8000/login.php`**

---

## 🎯 Educational Exploitation Scenarios

### Scenario 1: Authentication Bypass via SQLi

#### 💡 The Vuln Code:
Inside [login.php](file:///c:/Users/Gouda/Desktop/lab-pentesting/login.php), variables are directly concatenated inside raw SQL queries:
```php
// Unprepared SQL queries allow inputs to interfere with query logic
$query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
```

#### 🛠️ Exploitation Steps:
1. Access the login screen.
2. Enter the following payload in the **Operator Username** field:
   ```sql
   ' OR 1=1-- -
   ```
3. Leave the password blank and press **Authorize**.
4. **Why it works**: The `'` terminates the string field, `OR 1=1` forces the statement evaluation to resolve to `TRUE` globally, and `-- -` comments out the subsequent password constraints.

---

### Scenario 2: Request Analysis using Burp Suite
1. Launch **Burp Suite** on Kali Linux.
2. Under the **Proxy** tab, ensure **Intercept is ON**.
3. Use the integrated browser (**Open Browser**) and visit `http://localhost:8000/login.php`.
4. Enter random credentials and capture the `POST` request.
5. Replace the `username` parameter with: `admin'+OR+'1'='1` and forward the request to see the security dashboard unlock instantly.

---

## 🛡️ Secure Coding Fix (Remediation)
Always use **Prepared Statements** to enforce clear boundaries between structure and data:
```php
$query = "SELECT * FROM users WHERE username = :username AND password = :password";
$stmt = $db->prepare($query);
$stmt->execute([
    ':username' => $username,
    ':password' => $password
]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
```

---

# العربية

## ⚠️ إخلاء مسؤولية هام
> [!CAUTION]
> **هذا المشروع مخصص للأغراض التعليمية والبحثية فقط.**
> تم بناء هذا المختبر لغرض محاكاة الثغرات الأمنية وفهمها برمجياً ودفاعياً داخل بيئة عمل محلية (Local Sandbox). استخدام هذه الأساليب ضد أنظمة أو مواقع بدون إذن صريح ومكتوب هو أمر غير قانوني ويقع تحت طائلة المسؤولية الجنائية. المطور غير مسؤول عن أي سوء استخدام للمشروع.

---

## 📌 فكرة المشروع
**WolfLab** هو مختبر أمني تفاعلي خفيف الوزن ومستقل يحاكي لوحة تحكم إدارية لشركة أمنية وهمية. يعتمد المشروع على لغة **PHP** وقاعدة بيانات **SQLite** لتبسيط التشغيل دون الحاجة لإعداد خوادم معقدة مثل Apache أو MySQL.

يهدف المعمل إلى توضيح مخاطر:
1. **ثغرة حقن قواعد البيانات (SQL Injection - SQLi Bypass)** في صفحة الدخول.
2. **عرض تفاصيل الأخطاء الحساسة (Verbose SQL Errors)** للمستخدم النهائي.
3. **ضعف إدارة الجلسات (Weak Session Management)**.

---

## 📂 هيكلية المشروع

```text
WolfLab/
│
├── login.php          # صفحة الدخول المصابة بثغرة الـ SQL Injection
├── dashboard.php      # لوحة التحكم الأمنية (تظهر بعد تخطي الحماية)
├── logout.php         # سكربت إنهاء الجلسة وتدمير ملفات الكوكيز
├── init_db.php        # سكربت لتهيئة وإنشاء قاعدة البيانات تلقائياً
├── style.css          # ملف التنسيق البصري بأسلوب الـ Cyberpunk
└── README.md          # وثيقة دليل الاستخدام والتشغيل الحالية
```

---

## 🚀 طريقة التثبيت والتشغيل على Kali Linux

### 1. تثبيت الحزم المطلوبة
تأكد من تنصيب محرك PHP والملحق الخاص بـ SQLite:
```bash
sudo apt update
sudo apt install php php-sqlite3 -y
```

### 2. تحميل وتشغيل المشروع
انتقل إلى مجلد المشروع داخل نظام تشغيلك:
```bash
cd lab-pentesting
```

### 3. تهيئة قاعدة البيانات
قم بتشغيل سكربت التهيئة لإنشاء ملف قاعدة البيانات `users.db` وإدراج حساب الأدمن الافتراضي:
```bash
php init_db.php
```

### 4. تشغيل خادم الويب المحلي
شغل سيرفر PHP المدمج على البورت `8000`:
```bash
php -S 0.0.0.0:8000
```
الآن، افتح متصفح الويب واذهب إلى العنوان التالي لبدء التحدي:  
🔗 **`http://localhost:8000/login.php`**

---

## 🎯 السيناريوهات التعليمية وتطبيق الاختراق

### التحدي الأول: تخطي المصادقة عبر SQL Injection

#### 💡 ما المشكلة في الكود؟
تحدث الثغرة بسبب دمج مدخلات المستخدم مباشرة داخل جملة استعلام SQL دون معالجة أو استخدام استعلامات مجهزة (Prepared Statements):

```php
// ❌ كود مصاب بثغرة أمنية
$query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
```

#### 🛠️ طريقة التجاوز (Login Bypass Payload):
1. في حقل **Operator Username** اكتب المدخل التالي:
   ```sql
   ' OR 1=1-- -
   ```
2. اترك حقل الباسورد فارغاً أو اكتب أي كلمة مرور عشوائية.
3. اضغط على **Authorize**.
4. **كيف يعمل الهجوم؟**
   يقوم الرمز `'` بإغلاق الحقل النصي لاسم المستخدم، بينما يقوم الجزء `OR 1=1` بإجبار الشرط الإجمالي على أن يكون صحيحاً دائماً (True). الجزء الأخير `-- -` يمثل بداية سطر التعليقات في الـ SQL، مما يؤدي إلى إهمال وتجاهل بقية الاستعلام (شرط التحقق من الباسورد).

---

### التحدي الثاني: فحص الطلبات باستخدام Burp Suite
1. قم بتشغيل برنامج **Burp Suite** على نظام Kali Linux الخاص بك.
2. اذهب إلى تبويب **Proxy** ثم قم بتفعيل **Intercept is ON**.
3. استخدم متصفح بورب المدمج عبر الضغط على **Open Browser** ثم تصفح الرابط: `http://localhost:8000/login.php`.
4. أدخل أي بيانات خاطئة واضغط على **Authorize**.
5. ستلاحظ التقاط Burp Suite للطلب من نوع `POST`.
6. قم بتعديل قيمة المتغير `username` في نافذة الطلب داخل Burp إلى Payload الحقن: `admin'+OR+'1'='1` ثم اضغط على **Forward** لمشاهدة النتيجة الفورية وتخطي الحماية.

---

## 🛡️ كيف نقوم بتأمين الكود؟ (Mitigation & Secure Coding)

لتجنب هذه الثغرة الكارثية، يجب استخدام **Prepared Statements** مدعومة بـ **PDO** لفصل البيانات المدخلة عن بنية أمر الاستعلام:

```php
//  الكود الآمن والمحمي بالكامل
$query = "SELECT * FROM users WHERE username = :username AND password = :password";
$stmt = $db->prepare($query);
$stmt->execute([
    ':username' => $username,
    ':password' => $password
]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
```

---

## 👨‍💻 Developed by
* **Developer Name:** Gouda Nasralla
* **GitHub Profile:** [@GoudaWolfDev](https://github.com/GoudaWolfDev)
* **Project Repository:** [WolfLab](https://github.com/GoudaWolfDev/WolfLab)

---
⚡ **WolfLab Security Environment** - Designed for educational simulations. Built with 💻 by Gouda Nasralla.
