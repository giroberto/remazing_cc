## About

Project developed as a code challenge to remazing:

Used laravel and vuejs.

Seeding:
  - Tried some different methods to optimize seeding speed, ended up generating a csv file and using psql to seed the database as it was the faster method I found.

Database:
  - Used postgresql just because it is the database I am more used to work with.

Project:
  - First I developed one route for each graph, but there was almost no performace impact on retrieving all KPIs at once.
  
  
  
  ## How to run
  
  First, check the .env file and set the number of products to be seeded, on my machine, each million took about 30 seconds
  
  I did a Makefile with all necessary commands. If you do not have make installed, run the commands no second column of each section. **Execute only one of them**
  The steps to execute are:
  
  ###  To create the image
  With make | Without make
  ------------ | -------------
  make build | cd docker <br> docker build --no-cache -t php7-alpine . <br> cd ..
 
 
 ### To install the necessary npm packages and create the datatabase
 With make | Without make
  ------------ | -------------
  make first_run | docker-compose up --no-start <br> docker-compose run web composer install <br> docker-compose run web npm install <br> docker-compose run web npm run prod 
 
 
 ### To start the php and postgresql server(you should keep this running to execute the next steps)
 With make | Without make
  ------------ | -------------
  make execute | docker-compose up
 
 
 ### To seed the database( you can run multiple times to increase the amount of data)
 With make | Without make
  ------------ | -------------
  make seed | docker-compose exec web php artisan migrate <br> docker-compose exec web php artisan db:seed


## How to run
 Make sure docker-compose is running and access http://localhost:8080, create an account and the data will be shown, you can then filter by price, reviews, rating and date.
