# flexmail assignment

This is an assignment from Flexmail.  
  
## Purpose :  
Make use of Dark Sky API.   
Documentation https://darksky.net/dev/docs  

The Symfony framework is used to make calls to this API.  

## Setup

* Since there is a composer.lock - file, to make sure we use the same versions of packages use in terminal 
    > composer install
    
* get your secret key for the darksky api

        https://darksky.net/dev
        
        click 'try for free'
        
* add it to the configurations

* create database and tables (update .env file)

    * set DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"
        with your credentials.
        >  php bin/console doctrine:database:create
        
        >  php bin/console make:migration
        
        > php bin/console doctrine:migrations:migrate


* load the fixtures 

    > ./bin/console app:load-cities
    
* run Unit & Functional tests (some example tests are provided)
    
    > ./bin/phpunit




## Packages :

* basic symfony as api-end

* annotation (for routing etc)

* twig (frontend)

* webpack (with basic boorstrap & jQuery)

* swagger (documentation)

* phpUnit (unit tests, )

* asset package

* profile (debug toolbar)

* monolog for logging

* debugging tools

...



