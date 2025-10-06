# 🎓 Student Graduation Management System (SGMS)

> *"Because spreadsheets were never meant to handle destiny."* 😎  

Welcome to the **Student Graduation Management System**, a sleek Laravel-powered dashboard that helps administrators track students’ academic and graduation status like a pro.  

Whether you're managing hundreds of students or a small elite graduating class, this system makes sure **no one slips through the cracks** (unless they skipped exams, didn’t pay fees, or just... never showed up. 🙃).

---

## 🌟 Features That’ll Make You Smile

- 🧾 **CSV Uploads:** Bulk import student data in one go. No more endless manual entries.  
- 🔍 **Smart Search:** Real-time results as you type — find students faster than they can say “graduation gown.”  
- 🎨 **Google-Style Search UI:** Clean, minimal, and beautiful — because simplicity never goes out of style.  
- 🧠 **Graduation Status Logic:** Automatically checks attendance, exams, and fees to determine who’s ready to graduate.  
- 🧩 **Cascading Reasons View:** If someone’s not graduating, you’ll know *exactly why* — neatly organized and readable.  
- 📊 **Admin Dashboard:** View summaries, manage uploads, and track student progress from one intuitive panel.  

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-------------|
| Backend | Laravel 10 🐘 |
| Frontend | Blade + Bootstrap 5 🎨 |
| Database | MySQL 💾 |
| Version Control | Git 🧭 |

---

## 🚀 Setup Instructions

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/student-graduation-system.git
   cd student-graduation-system


2. **Install dependencies**

```bash
composer install
npm install && npm run dev
```


2. **Create your .env file**

```bash
cp .env.example .env
php artisan key:generate
```

3. **Set up your database**

- Create a MySQL database (e.g. sgms_db)

- Update your .env with your DB credentials

- Run migrations:

```bash
php artisan migrate
```

4. **Serve your application**

```bash
php artisan serve
```

- Then open http://localhost:8000 🚪

5. **Where to Store the Logo**

- Place your school logo in:
```bash
public/images/logo.png
```

- Then load it in your Blade file like this:
```bash
<img src="{{ asset('images/logo.png') }}" alt="School Logo" width="150">
```

6. **🤝 Contribution Guidelines**

- Fork the repo 🍴

- Create your feature branch:
git checkout -b feature/amazing-idea

- Commit your changes with epic messages 💬
git commit -m "✨ Added real-time graduation predictor"

- Push and open a pull request 🚀

7. **🧭 Vision**

This project was built with one goal:

To bring order, beauty, and intelligence into how institutions manage graduation readiness.

Because education deserves better tools — not better excuses. 😅

8. **💬 Contact**

Created with ❤️ by Odee Jairo
📧 Email: odeejairo@gmail.com
🌍 Website: hadithidigital.com

9. **🏁 License**

- This project is open-sourced under the MIT License
- Feel free to use, modify, and share responsibly.

"Graduation is not the end — it’s the beginning of debugging life’s next chapter." 💡