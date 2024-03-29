
Docker:

1. Install docker: https://www.docker.com
2. Make sure your user belongs to docker’s group, else you have to run the commands with sudo
3. Install docker-compose 

HelloWorld – stack:

Some info about docker-compose.yml before running the commands below. 1. docker-compose.yml version is 3.8 and it has 3 services inside: • php-hello_world container name, which is building from the Dockerfile on the path that it is referenced (php version 8.3-fpm the latest support version till now). • mysql-hello_world container name, which is pulling the mysql’s image from the docker hub (8.2 version). • mysql-hello_world container name, which is building from the Dockerfile on the path that it is referenced , pulling the latest version from docker hub. It is very useful for firewall – rules into to 80 and 443 port numbers (HTTP & HTTPS).

Lets encrypt service is not putted into the docker-compose.yml cause I don’t have domains names to make the certifications. Moreover, on the same path that there is the docker-compose.yml file there is a hidden file named .env and it has the environment variables to run the docker stack. Also, there is a hidden file named .gitignore that it is used to keep away from pushing to git files or folders that we don’t want it.

Let’s get started with the hello_world stack:

1. Make sure the port numbers (5346 for the outside port for mysql and the 9002 for the outside outside port of symfony are not used else the containers will not up).
2. Create a docker’s network if it does not exist: docker network create hello-world-network
3. Build: docker-compose build –no-cache
4. Up the containers: docker-compose up -d

After docker's containers are up for the hello_world’s stack: 

1. Go inside to the mysql's container with the following command: docker exec -it mysql-hello_world bash
   a) type: mysql -u root -p   (password for root is: hello_world)
   b) Create the database named hello_world_db with utf8 with the following command: create database hello_world_db character set utf8mb4 collate utf8mb4_general_ci;
   
3. Go inside to the symfony's container with the following command: docker exec -it php-hello_world bash

4. 2. Run composer installation for symfony and libraries:
      a) composer create-project symfony/skeleton .  (with the dot)  Warning: In the case that the app folder is not empty then skip this step.
      b) composer require robmorgan/phinx
      c) composer require symfony/console
      d) composer update
      
5. If the phinx.php file does not exist, type the command: php vendor/bin/phinx init but you need to configurate the file for the mysql driver.

6. Run the migration with the command : php vendor/bin/phinx migrate 

7. The hello_world project can be run with the command: php bin/console HelloWorld 1000 200   (Notice: the first number: 1000 is the argument to initialize the notes of the atm and the second argument the number: 200 is the amount of notes which is wanted to be withdraw from the atm)

8) Note: All above commands need to be run inside the php-hello_world container


PHP-FPM (FastCGI Process Manager) is an alternative to FastCGI implementation of PHP with some additional features useful for sites with high traffic. It is the preferred method of processing PHP pages with NGINX and is faster than traditional CGI based methods such as SUPHP or mod_php for running a PHP script.

Links that it would be helpful:

1) https://www.php.net/supported-versions.php
2) https://docs.docker.com/compose/compose-file/compose-versioning/
3) https://symfony.com/doc/current/setup.html
4) https://stackoverflow.com/questions/61344927/when-using-docker-compose-3-8-getting-version-is-unsupported-error
