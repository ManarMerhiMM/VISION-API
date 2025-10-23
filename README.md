# VISION API — Laravel Backend for the Wearable Ecosystem for the Visually Impaired

**VISION (Wearable Ecosystem for the Visually Impaired)** is an integrated assistive technology platform that enhances the **mobility, autonomy, and safety** of visually impaired individuals.  
This repository hosts the **Laravel-based RESTful API backend**, serving as the **core service layer** connecting the VISION mobile application and website to a shared mySQL database.

---

## System Architecture

The VISION ecosystem consists of three main layers working together:

- **Frontend (React.js):** Web dashboard for user management, data visualization, and configuration.  
- **Mobile App (React Native):** Interfaces with the wearable system, presents real-time biometrics, and syncs with this API.  
- **Backend (Laravel API):** Central hub for authentication, data persistence, and business logic (this repo).  
- **Database (MySQL):** Stores all persistent data, accessible only through the API.  


---

## ⚙️ Features

- 🔐 **Authentication & Authorization** via Laravel Sanctum  
- 📦 **RESTful API endpoints** for user, device, and data management  
- 📊 **Data persistence** and aggregation using MySQL and Eloquent ORM  
- 🌐 **CORS + HTTPS support** for secure web and mobile integration  
- 🧠 **Centralized business logic** with clean service-oriented design  
- 🚦 **Input validation & rate limiting** for API protection  

---

## 🧰 Tech Stack

| Layer | Technology |
|-------|-------------|
| Framework | Laravel 12.15.0 |
| Database | MySQL |
| Authentication | Laravel Sanctum |
| API Format | REST (JSON) |
| Frontends | React (Web) + React Native (Mobile) |
| Version Control | Git & GitHub |

---

## 🚀 Getting Started (Local Development)

Follow these steps to run the VISION API locally:

### 1️⃣ Clone the repository
```bash
git clone https://github.com/ManarMerhiMM/VISION-API.git
cd vision-api
```
### 2️⃣ Install dependencies
```bash
composer install
```
### 3️⃣ Configure environment variables
```bash
cp .env.example .env
php artisan key:generate
```
### 4️⃣ Run migrations
```bash
php artisan migrate
```

### 5️⃣ Start the local server
```bash
php artisan serve
```

Access it at http://localhost:8000

### ⚙️ Environment Configuration (.env Example)
```bash
APP_NAME=VISION
APP_ENV=local
APP_KEY=base64:your-generated-key
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vision_db
DB_USERNAME=root
DB_PASSWORD=

SANCTUM_STATEFUL_DOMAINS=localhost:3000
SESSION_DOMAIN=localhost
```

## 🔒 Security Practices

- ✅ All endpoints protected via Laravel Sanctum
- ✅ CORS policy restricts access to known frontend and mobile origins
- ✅ HTTPS/TLS enforced for all communications
- ✅ Input validation using Laravel Form Requests
- ✅ Rate limiting on public API routes

## 🧠 System Data Flow (concerning the website and mobile application)

1. Raspberry Pi collects live processed biometric data (SPO2 and BPM).
2. React Native mobile app connects via BLE and displays the data (live).
3. Mobile app periodically sends readings to the Laravel API over HTTPS.
4. API validates, stores, and aggregates readings in MySQL.
5. React web dashboard retrieves data via the same API to display user statistics, device logs, and analytics.

## 👥 Contributors

**VISION Development Team**  
Final Year Project — Computer Engineering Department  

| Role | Name |
|------|------|
| Backend Developer | Manar Merhi |
| Frontend Developer (Web) | Malek Shibli |
| Mobile Developer (React Native) | Manar Merhi |
| Embedded Systems / Hardware | Mohammad Shaaban |
| Computer Vision and ML | Mohammad El Halabi |
| Biometric Processing | Abdulrahman Nakouzi |


### 📄 License
This project is developed for **academic** and **research** purposes.
###### © 2025 VISION Project Team — All rights reserved.
