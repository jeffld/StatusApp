# Onboarding Checklist Application

This is a web-based onboarding checklist system for managing tasks related to Clients, Vendors, and Brokers in a small company setting. Built with PHP, MySQL, jQuery, and Bootstrap.

## 📋 Features

- Create and manage entities (Clients, Vendors, Brokers)
- Assign tasks to roles and users
- Role-based task filtering
- Admin panel for managing roles and users
- AJAX-based UI for real-time updates
- Audit logs for task and user actions

## 🧱 Database Schema Overview

### `roles`
Stores roles like IT, Sales, CustomerService, etc.

### `users`
Stores usernames and links each user to a role.

### `tasks`
Individual onboarding tasks with a title, phase, and associated role.

### `task_users`
Assigns specific users to individual tasks.

## 🛠 Tech Stack

- **PHP**
- **MySQL**
- **Bootstrap 5**
- **jQuery**
- **AJAX**
