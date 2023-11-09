<div align="center">
    <img src="https://global.discourse-cdn.com/algolia/original/1X/e69ce77e6b76753d6d49e4a1774405275444516d.gif" alt="Cat architect gif"/>
</div>

<br>
<br>
<h1 align="center">🛠️Work in progress...🛠️</h1>

## Table of contents
* [General info](#general-info)
* [Tools](#tools)
* [Current features](#current-features)
    - [Current works](#current-works)
    - [ToDo](#todo)
* [Setup](#setup)

## General info
The application is for creating and managing workout plans. By default, it allows you to keep statistics, set and arrange diets, workout plans, and track user progress. In the future, social networking features will also be added to it, so that users can motivate each other and track progress.

Currently, it is under development.
## Tools
- PHP v8.2
- Laravel v10
- Livewire v2
- Alpine Js v3
- Tailwind v3
- PHPUnit v10
- Larastan v2
- Pint v1

## Current features
- Custom authentication system
- Custom authorization system
- Custom file upload system
- Integration of Livewire with JavaScript libraries (CKEditor 5, Filepond, SweetAlert 2, Tippy)

### Current works
- The functionality of adding exercises, along with the entire flow (acceptance, notification handling, adding to favorites, etc.)

### ToDo
- The ability to add training goals
- Arranging workouts and managing training sessions
- Arranging and managing diets/recipes/ingredients
- Integration with Open Food API
- Interface improvements/changes

## Setup
If you want to check how the current version of the app looks, you should follow these steps:

```bash
~ git clone https://github.com/CatS0up/whey.git
~ cd .\whey\
~ docker-compose up --build -d
~ docker-compose exec app php artisan key:generate
~ docker-compose exec app php artisan migrate:fresh --seed
~ composer install
~ npm install
~ npm run dev
```

The application should be available at the following address: http://localhost

**Note!** Since the project is under development, it may not always work 😉

## Inspiration
My main inspiration for creating this project were applications such as [Fito](https://themeforest.net/item/fito-fitness-laravel-admin-dashboard/29631483) and [Fitatu](https://github.com/Fitatu), as well as my own need to create a gym application tailored to my requirements.
