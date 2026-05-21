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
*A local sandbox designed to demonstrate OWASP Top 10 vulnerabilities, exploitation mechanisms, and secure defensive coding practices.*

Developed by **[Gouda Nasralla](https://github.com/GoudaWolfDev)**

[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%207.4-8892BF?style=for-the-badge&logo=php)](https://www.php.net/)
[![SQLite Version](https://img.shields.io/badge/SQLite-3-003B57?style=for-the-badge&logo=sqlite)](https://www.sqlite.org/)
[![Platform](https://img.shields.io/badge/Platform-Kali%20Linux%20%7C%20Windows%20%7C%20Linux-green?style=for-the-badge&logo=linux)](https://www.kali.org/)
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
**WolfLab** is a lightweight, self-contained educational login portal simulating an administrative command panel. It is built natively using **PHP** and **SQLite** to bypass complex database server setups (e.g., MySQL or XAMPP). 

It highlights 5 major vulnerabilities based on the OWASP Top 10 guidelines:
1. **SQL Injection (SQLi Bypass)** in the login authentication mechanism.
2. **Sensitive Information Exposure** through verbose database error outputs.
3. **Direct File Exposure** allowing direct download of the SQLite database file (`users.db`).
4. **Plaintext Password Storage** showcasing insecure credential storage practices.
5. **Insecure Session Management** vulnerable to session hijacking and fixation.

---

## 📂 Project Structure
```text
WolfLab/
│
├── login.php          # Login portal containing the SQL Injection and Verbose Error flaws
├── dashboard.php      # Cyberpunk-styled security command panel displaying operator registry
├── logout.php         # Destroys active user sessions and clears browser cookies
├── init_db.php        # Database initialization script (creates user table with plaintext accounts)
├── style.css          # Core CSS variables, animations, and cyberpunk dark theme
├── SECURITY_REPORT.md # [NEW] Bilingual Comprehensive Security Audit & Vulnerability Remediation Report
└── README.md          # Project documentation (Bilingual)
```

---

## 🚀 Installation & Local Hosting

### 1. Install Dependencies
Ensure PHP and the SQLite extension are installed:
* **Debian/Ubuntu/Kali Linux**:
  ```bash
  sudo apt update
  sudo apt install php php-sqlite3 -y
  ```
* **Windows**:
  Ensure PHP is installed and the `extension=sqlite3` and `extension=pdo_sqlite` lines are uncommented in your `php.ini` file.

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
Inside [`login.php`](file:///c:/Users/Gouda/Desktop/lab-pentesting/login.php), user input is directly concatenated inside raw SQL queries:
```php
$query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
```

#### 🛠️ Exploitation Steps:
1. Access the login screen.
2. Enter the following payload in the **Operator Username** field:
   ```sql
   ' OR 1=1-- -
   ```
3. Leave the password blank and press **Authorize**.
4. **Why it works**: The `'` terminates the string field, `OR 1=1` forces the statement evaluation to resolve to `TRUE` globally, and `-- -` comments out the subsequent password checks in SQLite.

---

### Scenario 2: Sensitive Information Leakage
Input an unmatched quote character (e.g. `'` or `)`) into the **Operator Username** field and press **Authorize**.
* **Result**: The application crashes and exposes the raw SQLite driver exception, showing table schemas, column names, and the exact query template.

---

### Scenario 3: Database Acquisition
Since the database file `users.db` is stored inside the web root, an attacker can directly download the entire database bypassing the web interface completely:
```bash
curl -O http://localhost:8000/users.db
```
Once downloaded, the attacker can view all plain-text passwords and credentials stored in the `users` table.

---

## 🛡️ Secure Coding Remediation (Patches)
To protect the application against these threats, you must implement the defensive patterns detailed in the **[SECURITY_REPORT.md](file:///c:/Users/Gouda/Desktop/lab-pentesting/SECURITY_REPORT.md)**:

1. **Prepared Statements**:
   ```php
   $query = "SELECT * FROM users WHERE username = :username";
   $stmt = $db->prepare($query);
   $stmt->execute([':username' => $username]);
   $user = $stmt->fetch(PDO::FETCH_ASSOC);
   ```
2. **Password Hashing**: Use `password_hash($password, PASSWORD_BCRYPT)` and verify with `password_verify($password, $user['password'])`.
3. **Error Suppression**: Log errors quietly to server logs (`error_log`) and display a safe generic message.
4. **Secure Sessions**: Call `session_regenerate_id(true)` upon login and set `HttpOnly` and `Secure` cookie attributes.

---

# العربية

## ⚠️ إخلاء مسؤولية هام
> [!CAUTION]
> **هذا المشروع مخصص للأغراض التعليمية والبحثية فقط.**
> تم بناء هذا المختبر لغرض محاكاة الثغرات الأمنية وفهمها برمجياً ودفاعياً داخل بيئة عمل محلية (Local Sandbox). استخدام هذه الأساليب ضد أنظمة أو مواقع بدون إذن صريح ومكتوب هو أمر غير قانوني ويقع تحت طائلة المسؤولية الجنائية. المطور غير مسؤول عن أي سوء استخدام للمشروع.

---

## 📌 فكرة المشروع
**WolfLab** هو مختبر أمني تفاعلي خفيف الوزن ومستقل يحاكي لوحة تحكم إدارية لشركة أمنية وهمية. يعتمد المشروع على لغة **PHP** وقاعدة بيانات **SQLite** لتبسيط التشغيل دون الحاجة لإعداد خوادم معقدة.

يبرز المعمل 5 ثغرات أمنية حرجة بناءً على معايير OWASP Top 10 العالمية:
1. **ثغرة حقن قواعد البيانات (SQL Injection - SQLi Bypass)** لتخطي لوحة تسجيل الدخول.
2. **عرض تفاصيل الأخطاء الحساسة (Verbose SQL Errors)** للمستخدم النهائي.
3. **الوصول المباشر للملفات الحساسة** بتحميل ملف قاعدة البيانات `users.db` مباشرة.
4. **تخزين كلمات المرور بنص صريح (Plaintext Passwords)** دون تشفير.
5. **ضعف إدارة الجلسات (Insecure Sessions)** وقابليتها للاختراق وتثبيت الجلسة (Session Fixation).

---

## 📂 هيكلية المشروع
```text
WolfLab/
│
├── login.php          # صفحة الدخول المصابة بثغرة الـ SQL Injection وعرض الأخطاء التفصيلية
├── dashboard.php      # لوحة التحكم الأمنية (تظهر سجلات النظام وجدول مشغلي النظام)
├── logout.php         # سكربت إنهاء الجلسة وتدمير ملفات الكوكيز في المتصفح
├── init_db.php        # سكربت تهيئة وإنشاء قاعدة البيانات وإدراج المستخدمين الافتراضيين
├── style.css          # ملف التنسيق البصري بأسلوب الـ Cyberpunk المظلم
├── SECURITY_REPORT.md # [جديد] التقرير الشامل لتحليل الثغرات باللغتين العربية والإنجليزية وطرق تصحيحها
└── README.md          # وثيقة دليل الاستخدام والتشغيل الحالية
```

---

## 🚀 طريقة التثبيت والتشغيل المحلي

### 1. تثبيت الحزم المطلوبة
تأكد من تنصيب محرك PHP والملحق الخاص بـ SQLite:
* **نظام Kali Linux / Debian**:
  ```bash
  sudo apt update
  sudo apt install php php-sqlite3 -y
  ```
* **نظام Windows**:
  تأكد من تنصيب PHP وتفعيل إضافات `extension=sqlite3` و `extension=pdo_sqlite` داخل ملف الإعدادات `php.ini`.

### 2. الدخول لمجلد المشروع
```bash
cd lab-pentesting
```

### 3. تهيئة قاعدة البيانات
قم بتشغيل سكربت التهيئة لإنشاء ملف قاعدة البيانات `users.db` وإدراج حسابات الأدمن الافتراضية:
```bash
php init_db.php
```

### 4. تشغيل خادم الويب المحلي
شغل سيرفر PHP المدمج على البورت `8000`:
```bash
php -S 0.0.0.0:8000
```
الآن، افتح متصفح الويب واذهب إلى العنوان لبدء التحدي:  
🔗 **`http://localhost:8000/login.php`**

---

## 🎯 السيناريوهات التعليمية وتطبيق الاختراق

### التحدي الأول: تخطي المصادقة عبر SQL Injection
تحدث الثغرة في [`login.php`](file:///c:/Users/Gouda/Desktop/lab-pentesting/login.php) بسبب دمج مدخلات المستخدم مباشرة داخل الاستعلام:
```php
$query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
```

#### 🛠️ خطوات الاختراق:
1. اذهب لصفحة تسجيل الدخول.
2. أدخل النص التالي في حقل **Operator Username**:
   ```sql
   ' OR 1=1-- -
   ```
3. اترك حقل كلمة المرور فارغاً واضغط **Authorize**.
4. **كيف يعمل الهجوم؟**: يقوم الرمز `'` بإغلاق حقل اسم المستخدم، وتجبر الجملة `OR 1=1` الاستعلام على إرجاع قيمة صحيحة دائماً (True)، بينما يقوم الرمز `--` بتعطيل وتحويل بقية الاستعلام (شرط التحقق من كلمة المرور) إلى تعليق مهمل.

---

### التحدي الثاني: تسريب البيانات عبر الأخطاء
أدخل رمزاً غير متطابق مثل `'` أو `)` في حقل اسم المستخدم واضغط **Authorize**.
* **النتيجة**: يتوقف التطبيق عن العمل ويعرض رسالة الخطأ المباشرة من محرك SQLite والتي توضح الهيكل الداخلي للجداول والأعمدة المستهدفة.

---

### التحدي الثالث: سحب قاعدة البيانات
نظراً لتواجد ملف قاعدة البيانات في المجلد الرئيسي للويب، يمكن لأي شخص تحميل الملف بالكامل وقراءة محتوياته فوراً:
```bash
curl -O http://localhost:8000/users.db
```
بمجرد تحميل الملف، سيتمكن المهاجم من فتح الجدول وقراءة كلمات المرور النصية المخزنة دون تشفير.

---

## 🛡️ دليل حماية وتأمين الكود البرمجي
لتأمين هذا التطبيق وسد الثغرات المذكورة، يجب اتباع التعليمات والحلول البرمجية الكاملة الموجودة في ملف **[SECURITY_REPORT.md](file:///c:/Users/Gouda/Desktop/lab-pentesting/SECURITY_REPORT.md)**:

1. **الاستعلامات المجهزة (Prepared Statements)**: لفصل البيانات عن هيكلية الاستعلام البرمجي.
2. **تشفير كلمات المرور**: باستخدام خوارزمية `BCRYPT` القوية عبر دالة `password_hash()`.
3. **حظر الأخطاء التفصيلية**: الاكتفاء بعرض رسائل عامة للمستخدم وتسجيل الأخطاء الفعلية محلياً.
4. **إدارة الجلسات بشكل آمن**: تجديد معرف الجلسة باستخدام `session_regenerate_id(true)` عند تسجيل الدخول الناجح.

---
*Developed & Designed with 💻 by Gouda Nasralla.*
