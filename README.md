# Battle simulation game
A basic battle simulation game between a hero and a monster. It's a simple demo app that is using some common OOP patterns. The code tries to keep most of the logic in the domain layer (a proof that most of the business logic can be part of the domain and not leak any logic in the outer layers).

## Requires:
1. PHP ~7.3 (tested with 7.4 but should work with 7.3)
2. composer package manager

## Usage:
1. Clone this repository
2. `cd` in the repository directory
3. Run `composer install`
4. Run `php consoleApp.php` to run the console version of the battle simulation. To run new simulations, just rerun the command for every new simulation.
5. Run `php -S 0.0.0.0:8080 webApp.php`. Open a browser and go to localhost:8080. To run new simulation refresh the page.
6. Optional, if you want to run the tests, run: `php ./vendor/bin/phpunit tests`