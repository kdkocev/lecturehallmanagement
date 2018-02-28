# Lecture hall management system

A website that is aiming to make lecture hall schedules easier to manage

## Local Setup

* in `./config.php` change all the paths that start with `/fmi` to start with the subfolder that you're working in.
* in `./.htaccess` change all the paths that start with `/fmi` to start with the subfolter that you're working in.
* in `./config.php` set the database credentials to your local ones.
* run migrations (run the SQL in `./migrations/migrations.sql` in your local database) **Note** that different databases have a different understanding of the SQL syntax so you might have to change the sql in migrations.sql depending on your local database.
* run the website
