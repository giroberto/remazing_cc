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
  
  I did a Makefile with all necessary commands. If you do not have make installed, run the second command of each section. **Execute only one of them**
  The steps to execute are
  
  ###  To create the image
  With make | Without make
  ------------ | -------------
  make build | cd docker && docker build --no-cache -t php7-alpine . && cd ..
 
 
 ### To start the php and postgresql server(you should keep this running to execute the next steps)
 With make | Without make
  ------------ | -------------
  make execute | docker-compose up
 
 
 ### To install the necessary npm packages and create the datatabase
 With make | Without make
  ------------ | -------------
  make first_run | docker-compose exec web npm install && docker-compose exec web npm run prod && docker-compose exec web php artisan migrate
 
 
 ### To seed the database
 With make | Without make
  ------------ | -------------
  make seed | docker-compose exec web php artisan seed


