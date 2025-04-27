# To-Do List App

A straightforward and effective To-Do List Web Application designed to help you organize your tasks effortlessly. Leveraging modern web technologies, it provides a seamless and responsive experience for managing your daily activities.

## ✨ Key Features

* **Effortless Task Creation:** Add new tasks to your list quickly and easily.
* **Focused Pending View:** See only your active tasks to prioritize what needs to be done.
* **Comprehensive Task Overview:** View all your tasks, both those still pending and those you've already completed, by simply clicking the "Show All Tasks" option.
* **Simple Task Completion:** Mark tasks as completed with a single click, keeping your list up-to-date.
* **Easy Task Removal:** Delete tasks that are no longer relevant or necessary.
* **Dynamic Updates (AJAX):** Enjoy a smooth user experience with tasks being added without full page reloads.

## 🛠️ Technologies

This application is built using the following technologies:

* **Frontend:**
    * HTML5
    * CSS3
    * JavaScript (enhanced with jQuery for dynamic interactions)
* **Backend:**
    * PHP (Laravel 9 Framework)
* **Database:**
    * MySQL

## 🚀 Getting Started

Follow these simple steps to get the To-Do List App running on your local machine:

1.  **Clone the Repository:**
    ```bash
    git clone https://github.com/AdityaNarayan1203/todoApp.git
    cd todoApp
    ```

2.  **Install Dependencies:**
    ```bash
    composer install
    ```

3.  **Configure Environment:**
    * Copy the default environment file:
        ```bash
        cp .env.example .env
        ```
    * Edit the `.env` file to configure your MySQL database credentials:
        ```ini
        DB_DATABASE=todo_laravel
        DB_USERNAME=your_database_username
        DB_PASSWORD=your_database_password
        ```

4.  **Run Migrations:**
    ```bash
    php artisan migrate
    ```

5.  **Serve the Application:**
    ```bash
    php artisan serve
    ```

    Open your web browser and navigate to `http://127.0.0.1:8000` to access the application.
