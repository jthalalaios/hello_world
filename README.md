
Docker:

1. Install docker: https://www.docker.com/
2. Make sure your user belongs to docker’s group, else you have to run the commands with sudo
3. Install docker-compose 

HelloWorld – stack:

Some info about docker-compose.yml before running the commands below. 1. docker-compose.yml version is 3.8 and it has 3 services inside: • php-hello_world container name, which is building from the Dockerfile on the path that it is referenced (php version 8.3-fpm the latest support version till now). • mysql-hello_world container name, which is pulling the mysql’s image from the docker hub (8.2 version). • mysql-hello_world container name, which is building from the Dockerfile on the path that it is referenced , pulling the latest version from docker hub. It is very useful for firewall – rules into to 80 and 443 port numbers (HTTP & HTTPS).

Lets encrypt service is not putted into the docker-compose.yml cause I don’t have domains names to make the certifications. Moreover, on the same path that there is the docker-compose.yml file there is a hidden file named .env and it has the environment variables to run the docker stack. Also, there is a hidden file named .gitignore that it is used to keep away from pushing to git files or folders that we don’t want it.

Let’s get started with the movieWorld- stack:

1. Make sure the port numbers (5346 for the outside port for mysql and the 9002 for the outside outside port of symfony are not used else the containers will not up).
2. Create a docker’s network if it does not exists: docker network create hello-world-network
3. Build: docker-compose build –no-cache
4. Up the containers: docker-compose up -d

After docker's containers are up for the movieWorld’s stack: 
1. Go inside movieworld-api container with the following command: docker exec -it php-hello_world bash
2. 2. Run composer installation for symfony and libraries:
      a) composer create-project symfony/skeleton .  (with the dot)
      b) composer require robmorgan/phinx
      c) composer require symfony/console
      d) composer update
      e) php vendor/bin/phinx migrate 
      
3. If the phixn.php files does not exit type the command: php vendor/bin/phinx init
4. Run the migration with the command : php vendor/bin/phinx migrate 
5. The hello_world project can be run with the command: php bin/console HelloWorld 1000 200
6) Note: All above commands need to be run inside the php-hello_world container