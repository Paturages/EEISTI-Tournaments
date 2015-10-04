# EEISTI-Tournaments
Tournament signup interface in PHP (Laravel Lumen). This is an early mostly working alpha which still needs refining.

## Requirements
This project is built on the [Laravel Lumen](http://lumen.laravel.com/) framework. As such, it requires **PHP >=5.4**.  
To build this project, use `composer` to get all the dependencies. As with Laravel/Lumen projects, you will have to create a new server entry on Nginx or a VirtualHost on Apache (after properly editing the .htaccess under `/public`) pointing to the public folder.  

Next step: the `.env` file. An example has been provided in `.env.example`: you should hook up your own credentials and rename the file to `.env`.  

Afterwards, create the database with `/resources/db/create.sql`. I haven't gotten around automating the creation yet, we'll see how that goes. This has been tested with SQLite 3.  

You might want to create some games that people can sign up for: *well*, the administration interface... isn't done yet, so you will have to insert games manually via SQL for now.

## Features

- API for CRUD operations on entries
- Main interface in one page with jQuery interactivity (taking advantage of the API)
- *Light* interface rendered with PHP (has its own controllers)
- Sign up confirmation by e-mail
- Entry "authentication" for editing, deleting and confirming

## To do

- Administration interface
- Cleaning up the jQuery code (lots of ugliness)
- Adding custom fields feature
- Probably clean up the PHP code too
- Tests
- Other features than signing up ?
