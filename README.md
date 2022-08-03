# Drupal

### Drupal Settings Files
Before starting the container make sure you have an empty *default.settings.php* and *settings.php* file in the */sites/default* directory.

### Docker Compose Enviroment Variables
In this project's root directory, create a .env file to store environment variables for the the docker-compose.yml file. Then set the values for the variables by replacing *\<value\>* with the desired values.
```
MYSQL_DATABASE=<value>
MYSQL_USER=<value>
MYSQL_PASSWORD=<value>
MYSQL_ROOT_PASSWORD=<value>
VIRTUAL_PORT=<value>
```
### Accessing MySQL Container
When setting up a new Drupal website use the MySQL's container name from the *docker-compose.yml* files for the hostname.