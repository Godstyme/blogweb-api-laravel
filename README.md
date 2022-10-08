## BLOGWEB API LARAVEL
This project provides the following features:
- CRUD system
- Possible entities: ['admin','Posts','Comments','bloggers']
- Use a mailing library to send mail on registration.
- Use a mailing library to send in a forget password URL for resetting password
- Use JWT to authenticate every role based endpoint
- Use a solid principle design
# Database ERD
- https://drawsql.app/teams/godstime/diagrams/webblogapi

# Screenshot of some Endpoints
Dsipay All Post Endpoint            |  Login  Endpoint |  Make a Post Endpoint
:-------------------------:|:-------------------------:|:-------------------------:
![Screenshot](resources/assets/imgs/allpost.png)  |  ![Screenshot](resources/assets/imgs/login.png) | ![Screenshot](resources/assets/imgs/post.png)

## Quick Setup ⚙️
First, clone repo and install all dependencies.
```sh
$ git clone https://github.com/Godstyme/blogweb-api-laravel
$ cd blogweb-api-laravel
$ composer install
```
Setup database by creating a database called `webblog` in your `phpMyAdmin`. This example uses the `webblog` database as configured in the app `.env` file.
We have to use a migration command to prepare the database migration classes for the target tables.

```sh
$ php artisan migrate
$ php artisan serve
``` 

Don't forget to star the project :)