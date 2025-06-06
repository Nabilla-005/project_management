
# 📁 Project Management System - README

## 🧩 Entity Relationship Diagram (ERD)

Berikut adalah ERD dari sistem Project Management System yang kami kembangkan:

![Image](https://github.com/user-attachments/assets/8e6528b9-11f2-477e-ab2d-2f5e2a2b2ca6)

Diagram ini mencakup entitas:
- *Users* (dengan role seperti Admin, Project Manager, Team Member)
- *Projects*
- *Tasks*
- *Comments*
- *Task Assignments*
- *Permissions* & *Roles* (menggunakan Spatie Laravel-Permission)


## 🔄 Alur Sistem

1. *User Registrasi/Login*  
   - Autentikasi menggunakan *JWT Token*
   - Jika token valid, user diarahkan ke dashboard

2. *Role & Permission*  
   - Role default: Admin, Project Manager, Team Member  
   - Setiap role memiliki izin akses berbeda (manage users, create project, assign task, dsb.)

3. *Manajemen Proyek & Tugas*  
   - Admin/Project Manager membuat proyek
   - Project Manager membuat & meng-assign tugas ke anggota
   - Anggota tim dapat memperbarui status dan menambahkan komentar

4. *Dashboard*  
   - Menampilkan jumlah proyek, total tugas, statistik progres
   - Ditampilkan sesuai role user

5. *Komentar & Kolaborasi*  
   - Setiap tugas mendukung diskusi antar anggota tim
   - Dapat melampirkan file (opsional)

6. *Fitur Tambahan*  
   - Deploy ke Heroku/Server Gratis (hindari server yang berbayar)
   - Terdapat Proses Fetch API
   - Implementasi dalam bentuk Kanban Board (drag and drop task)
   - Notifikasi Realtime (Laravel echo + pusher)
   - Visualisasi Proyek menggunakan Chart JS

---

## 🚀 Cara Deployment

### 🌐 Backend Laravel - Railway

1. *Buat Project di Railway*
   - Buka [https://railway.app](https://railway.app)
   - Pilih New Project → Deploy from GitHub Repo

2. *Set Environment Variables*
   - Tambahkan variabel dari .env file Laravel, contohnya:
     
     APP_NAME=Laravel
     APP_ENV=production
     APP_KEY=...
     DB_CONNECTION=mysql
     DB_HOST=containers-us-west-xxx.railway.app
     DB_PORT=...
     DB_DATABASE=...
     DB_USERNAME=...
     DB_PASSWORD=...
     JWT_SECRET=...
     

3. *Migrate & Seed Database*
   Jalankan perintah berikut di Railway terminal:
   ```bash
   php artisan migrate --seed
