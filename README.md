# Battle simulation game

A basic battle simulation game between a hero and a monster. It's a simple demo app that is using some common OOP
patterns. The code tries to keep most of the logic in the domain layer
(a proof that most of the business logic can be part of the domain and not leak any logic in the outer layers). For the
UI it includes a Vue SPA version (the /ui folder) and a basic html version (css, jquery, html all in one file :
/index.html).

## Requires:

1. PHP ~7.4
2. composer package manager
3. node.js (v14.x) and npm if you want to run the Vue version of the UI

## Usage:

1. Clone this repository
2. `cd` in the repository directory
3. Run `composer install`
4. Run `php consoleApp.php` to run the console version of the battle simulation. To run new simulations, just rerun the
   command for every new simulation.
5. Run `php -S 127.0.0.1:8080 webService.php` to run the web service with the basic UI. Open a browser and go to
   localhost:8080. To run new simulation refresh the page.
6. Optional, if you want to run the tests, run: `./vendor/bin/phpunit tests`
7. To run the app with the Vue UI, `cd` to the /ui folder and run `npm install`
   to install all dependencies, after that run `npm run build` to build the Vue app. After that `cd` into the repository
   root folder and run
   `php -S 127.0.0.1:8080 -t ./ui/dist webService.php`. Open a browser and go to localhost:8080. To run new simulation
   refresh the page.