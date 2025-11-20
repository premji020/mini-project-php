# 🔍 Job Finder - Recruitment Management System

![Project Status](https://img.shields.io/badge/status-completed-success)
![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.0-7952B3?logo=bootstrap&logoColor=white)

**Job Finder** is a full-stack web application that bridges the gap between talent and opportunity. It serves as a centralized platform where Employers can manage recruitment and Job Seekers can find and apply for opportunities in real-time.

---

## 📖 Table of Contents
- [About the Project](#-about-the-project)
- [Key Features](#-key-features)
- [System Architecture](#-system-architecture)
- [Tech Stack](#-tech-stack)
- [Database Schema](#-database-schema)
- [Installation & Setup](#-installation--setup)
- [Folder Structure](#-folder-structure)
- [Future Scope](#-future-scope)

---

## 📝 About the Project
The traditional hiring process is often chaotic, involving scattered emails and lack of transparency. **Job Finder** solves this by providing a structured digital environment. The system implements **Role-Based Access Control (RBAC)** to distinguish between **Employers** (who manage the hiring lifecycle) and **Job Seekers** (who apply and track progress).

The application is built using **Core PHP** for backend logic, adhering to a clean procedural coding style, and utilizes **MySQL** for relational data management.

---

## 🌟 Key Features

### 👨‍💼 For Employers
* **Dashboard:** A private control center to view active job postings.
* **Job Management (CRUD):** Post new jobs, edit existing details, and delete filled positions.
* **Applicant Tracking:** View a list of candidates for specific jobs.
* **Decision Making:** Accept ("Shortlist") or Reject candidates, which instantly updates the applicant's status.

### 🕵️ For Job Seekers
* **Advanced Search:** Filter jobs by keywords, job titles, or company names.
* **Application System:** Apply to jobs with a custom cover letter.
* **Real-Time Status:** Track application progress (Pending ➝ Shortlisted/Rejected).
* **Bookmarks:** Save interesting jobs to a personal wishlist for later.

---

## 🏗 System Architecture
The project follows a **3-Tier Architecture**:
1.  **Presentation Layer:** HTML5, CSS3, and Bootstrap 5 handle the user interface.
2.  **Application Layer:** PHP scripts manage authentication, session handling, and business logic.
3.  **Data Layer:** MySQL stores user credentials, job details, and application records.



[Image of three tier architecture diagram presentation layer application layer data layer]


---

## 💻 Tech Stack

| Component | Technology | Description |
| :--- | :--- | :--- |
| **Frontend** | HTML5, Bootstrap 5 | Responsive UI and Layouts |
| **Backend** | PHP (Procedural) | Server-side logic & Auth |
| **Database** | MySQL | Relational Data Storage |
| **Server** | Apache (XAMPP) | Local Development Server |

---

## 🗄 Database Schema

The system uses a relational database named `job_finder` with 4 main tables:

1.  **`users`**: Stores ID, Name, Email, Password (Hashed), and Role.
2.  **`jobs`**: Stores Job Details, Salary, Description, and links to `employer_id`.
3.  **`applications`**: Links `user_id` to `job_id` with status and cover letter.
4.  **`bookmarks`**: Stores saved jobs for seekers.

---

## ⚙️ Installation & Setup

Follow these steps to run the project locally:

### 1. Prerequisites
* Install [XAMPP](https://www.apachefriends.org/) (or WAMP/MAMP).

### 2. Database Configuration
1.  Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
2.  Create a new database named **`job_finder`**.
3.  Import the `job_finder.sql` file provided in this repository.

### 3. Project Setup
1.  Navigate to your XAMPP root folder (usually `C:\xampp\htdocs\`).
2.  Clone or Download this repository into a folder named `job-finder`.
    ```bash
    git clone [https://github.com/yourusername/job-finder.git](https://github.com/yourusername/job-finder.git)
    ```

### 4. Verify Connection
Open `db/db.php` and ensure credentials match your local SQL setup:
```php
$host = 'localhost';
$user = 'root';
$pass = ''; // Default XAMPP password is empty
$db   = 'job_finder';
```
### 5. Run
Open your browser and visit: http://localhost/job-finder/
```
/job-finder
├── /db
│   └── db.php               # Database connection & Session start
├── /employer
│   ├── dashboard.php        # Employer control panel
│   ├── job_create.php       # Post new jobs
│   ├── job_edit.php         # Edit existing jobs
│   └── job_delete.php       # Delete jobs logic
├── /jobseeker
│   ├── applications.php     # Application history & status
│   └── saved.php            # Bookmarked jobs
├── /css
│   └── style.css            # Custom styles
├── /js
│   └── main.js              # Custom scripts
├── index.php                # Homepage & Search
├── login.php                # User Authentication
├── register.php             # Account Creation
├── logout.php               # Session Cleanup
└── job_details.php          # Job view & Apply logic
```
