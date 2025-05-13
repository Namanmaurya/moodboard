# 🧠 Daily MoodBoard

**Daily MoodBoard** is a simple, fun social web app where users can share their daily moods using emojis, text, and images — like a mini Instagram for your emotions.

---

## 🚀 Features

- 🔐 User authentication (login/register)
- 😊 Share mood with emoji, message & image
- ❤️ Like and 💬 comment on mood posts
- 📄 View user profiles and their mood timeline
- 🔁 Realtime updates (likes/comments shown instantly)
- 📱 Mobile-responsive with Bootstrap
- 🚪 Logout button in the navbar
- 📁 Image upload support with file validation

---

## 🛠️ Technologies Used

- **Frontend**: HTML5, CSS3, Bootstrap 5, JavaScript
- **Backend**: PHP 8+
- **Database**: MySQL (via `db.php` connection)
- **Server Requirements**: Apache/Nginx, PHP, MySQL

---

## 📂 Folder Structure

/daily-moodboard
│
├── index.php # Main feed of all moods
├── post.php # Mood submission form
├── profile.php # View a user's profile and their moods
├── like.php # Handle like/unlike requests
├── comment.php # Submit comments
├── login.php # Login page
├── register.php # Registration page
├── logout.php # Ends the session
│
├── /uploads # Folder for uploaded images
│
├── /includes
│ ├── db.php # Database connection file
│ ├── header.php # Navbar with links and logout
│ └── footer.php # Page footer
│
└── README.md # You're here!


## 🧑‍💻 Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/daily-moodboard.git
cd daily-moodboard
2. Configure the Database
Import the provided SQL file or run the following manually:


CREATE DATABASE moodboard;

USE moodboard;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255)
);

CREATE TABLE moods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    mood_emoji VARCHAR(10),
    message TEXT,
    image VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE moods_likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    mood_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (mood_id) REFERENCES moods(id)
);

CREATE TABLE moods_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mood_id INT,
    user_id INT,
    comment TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (mood_id) REFERENCES moods(id)
);
3. Configure db.php
Edit /includes/db.php to match your MySQL credentials:


<?php
$conn = mysqli_connect("localhost", "root", "", "moodboard");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
4. Set Folder Permissions
Ensure /uploads/ folder is writable so image uploads can be stored.

🖼️ Screenshots
Add screenshots here showing:

Homepage with moods

Post mood page

Profile page

Like and comment actions

🤝 Contribution
Have suggestions or want to improve features? Feel free to fork, clone, and submit a pull request.

📜 License
MIT License – Use this freely for learning, fun projects, or portfolio work.

🙌 Acknowledgements
Built with love using:

PHP & MySQL

Bootstrap 5

Emojis 😄


