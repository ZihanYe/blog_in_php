# blog_in_php
A simple blog web application in PHP

It supports user login and admin login. Admin and authors can post blogs.

## Usage

To run it locally, we need XAMPP (MySQL, Apache). Use phpMyAdmin to create database ```blog```, which contains tables:

- posts
- topic
- post_topic
- users
- contacts

```blog.sql``` contains MySQL template to create the needed database structures.

Use [composer](https://getcomposer.org/) to install requirements. Running ```composer install``` would install the dependencies.

Finally visit ```http://localhost/blog_in_php/index.php```.
