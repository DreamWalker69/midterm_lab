# Twitter Clone - Social Media Application

A full-featured Twitter-like social media application built with Laravel 11, featuring user authentication, tweet management, likes, and user profiles.

![Laravel](https://img.shields.io/badge/Laravel-11.x-red)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-blue)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.x-38bdf8)

## 📋 Table of Contents

- [About](#about)
- [Features](#features)
- [Technologies Used](#technologies-used)
- [Installation](#installation)
- [Database Setup](#database-setup)
- [Usage](#usage)
- [Screenshots](#screenshots)
- [Project Structure](#project-structure)
- [Credits](#credits)

## 🎯 About

This is a Twitter-like social media application that allows users to share short messages (tweets), interact with other users' content through likes, and manage their profiles. Built as a demonstration of modern web development practices using the Laravel framework.

## ✨ Features

### User Authentication
- ✅ User registration with name, email, and password
- ✅ Secure login and logout functionality
- ✅ Password hashing and security
- ✅ Protected routes (only authenticated users can tweet and like)
- ✅ Session management with "Remember Me" option

### Tweet Management
- ✅ Create tweets (max 280 characters)
- ✅ Real-time character counter
- ✅ Display all tweets from all users on homepage
- ✅ Tweets ordered by newest first
- ✅ Edit your own tweets with "edited" indicator
- ✅ Delete your own tweets with confirmation
- ✅ Tweet timestamps with human-readable format

### Like System
- ✅ Like/unlike any tweet (including your own)
- ✅ Visual indicator for liked tweets
- ✅ Real-time like count display
- ✅ One user can only like a tweet once
- ✅ Database constraint to prevent duplicate likes

### User Profile
- ✅ Dedicated profile page for each user
- ✅ Display user's name and join date
- ✅ List of all user's tweets
- ✅ Total tweet count
- ✅ Total likes received across all tweets
- ✅ Profile avatar with user initials

### Technical Implementation
- ✅ Proper database migrations with foreign key relationships
- ✅ Eloquent models with defined relationships
- ✅ RESTful routing structure
- ✅ Form validation on all inputs
- ✅ Clean MVC architecture
- ✅ Blade templating with components
- ✅ Responsive design (mobile and desktop)
- ✅ Modern UI with Tailwind CSS
- ✅ Flash messages for user feedback

## 🛠️ Technologies Used

- **Backend:** Laravel 11.x
- **Frontend:** Blade Templates, Tailwind CSS
- **Database:** MySQL/SQLite
- **Authentication:** Laravel's built-in authentication
- **Icons:** Font Awesome 6.4
- **PHP:** 8.2+

## 📥 Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- MySQL or SQLite
- Node.js and NPM (for asset compilation)

### Step-by-Step Installation

1. **Clone the repository**
   ```bash
   git clone <your-repository-url>
   cd twitter-like_app
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Create environment file**
   ```bash
   # On Windows
   copy .env.example .env
   
   # On Mac/Linux
   cp .env.example .env
   ```

4. **Generate application key**
   ```bash
   php artisan key:generate
   ```

5. **Configure your database**
   
   Open `.env` file and set your database credentials:
   
   **For MySQL:**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=twitter_clone
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```
   
   **For SQLite (easier for development):**
   ```env
   DB_CONNECTION=sqlite
   # Remove or comment out other DB_ variables
   ```

6. **Create the database**
   
   **For MySQL:**
   ```bash
   # Create database manually using MySQL client or phpMyAdmin
   CREATE DATABASE twitter_clone;
   ```
   
   **For SQLite:**
   ```bash
   # On Windows
   New-Item database\database.sqlite
   
   # On Mac/Linux
   touch database/database.sqlite
   ```

## 🗄️ Database Setup

1. **Run migrations**
   ```bash
   php artisan migrate
   ```

   This will create the following tables:
   - `users` - Store user accounts
   - `tweets` - Store all tweets with user relationship
   - `likes` - Store tweet likes with unique constraint
   - `cache` - For application caching
   - `jobs` - For queue management

2. **Seed the database (optional)**
   
   You can create test data manually by:
   - Registering new accounts through the web interface
   - Creating tweets as different users
   - Liking tweets to test the like system

## 🚀 Usage

1. **Start the development server**
   ```bash
   php artisan serve
   ```

2. **Access the application**
   
   Open your browser and visit: `http://localhost:8000`

3. **Create an account**
   - Click "Sign Up" in the navigation
   - Fill in your name, email, and password
   - Click "Sign Up" to create your account

4. **Start tweeting**
   - After logging in, you'll see the homepage with a tweet form
   - Type your message (max 280 characters)
   - Click "Tweet" to post

5. **Interact with tweets**
   - Click the heart icon to like/unlike tweets
   - Click the edit icon on your own tweets to edit them
   - Click the trash icon on your own tweets to delete them

6. **View profiles**
   - Click on any user's name to view their profile
   - See their tweets, total tweet count, and likes received

## 📸 Screenshots

### Homepage
The main feed showing all tweets from all users with the ability to create new tweets (for authenticated users).

### User Profile
Profile page displaying user information, statistics, and all their tweets.

### Registration/Login
Clean and simple authentication forms with validation.

### Tweet Actions
Like, edit, and delete functionality with visual feedback.

## 📁 Project Structure

```
twitter-like_app/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AuthController.php      # Authentication logic
│   │       ├── TweetController.php     # Tweet CRUD operations
│   │       ├── LikeController.php      # Like/unlike functionality
│   │       └── ProfileController.php   # User profile display
│   └── Models/
│       ├── User.php                    # User model with relationships
│       ├── Tweet.php                   # Tweet model with relationships
│       └── Like.php                    # Like model
├── database/
│   └── migrations/
│       ├── *_create_users_table.php
│       ├── *_create_tweets_table.php   # Tweets table migration
│       └── *_create_likes_table.php    # Likes table migration
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php           # Main layout template
│       ├── auth/
│       │   ├── register.blade.php      # Registration form
│       │   └── login.blade.php         # Login form
│       ├── tweets/
│       │   ├── index.blade.php         # Homepage/tweet feed
│       │   └── edit.blade.php          # Tweet edit form
│       └── profile/
│           └── show.blade.php          # User profile page
└── routes/
    └── web.php                         # Application routes
```

## 🎓 Credits

### Developer
This project was developed as a comprehensive demonstration of Laravel web application development.

### AI Tools Used
- **GitHub Copilot** - Used for code completion and suggestions throughout the development process
  - Helped with controller method implementation
  - Assisted in writing Blade template syntax
  - Provided suggestions for Eloquent relationships
  - Helped optimize database queries

- **ChatGPT/Claude** - Used for planning and documentation
  - Helped plan the application architecture
  - Assisted in writing this README documentation
  - Provided guidance on Laravel best practices
  - Helped troubleshoot issues during development

### Technologies & Frameworks
- **Laravel Framework** - The PHP framework that powers this application
- **Tailwind CSS** - For responsive and modern UI design
- **Font Awesome** - For beautiful icons throughout the interface

### Learning Resources
- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Laravel Eloquent Relationships](https://laravel.com/docs/eloquent-relationships)

## 📝 License

This project is open-source and available under the MIT License.

## 🔒 Security

- All passwords are hashed using Laravel's built-in hashing
- CSRF protection on all forms
- SQL injection prevention through Eloquent ORM
- XSS protection through Blade templating
- Input validation on all user inputs

## 🤝 Contributing

This is an educational project, but suggestions and improvements are welcome!

## 📧 Contact

For questions or feedback about this project, please create an issue in the repository.

---

**Note:** This is a demonstration project created for educational purposes. For production use, additional features like email verification, password reset, rate limiting, and more comprehensive testing should be implemented.

