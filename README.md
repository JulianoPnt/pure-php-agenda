# Cohros Test

This project has PHP 7.4 FPM, Nginx and MySQL 8.0

### Prerequisites

```
Composer
Docker
Docker Compose
```

##### Note: Don't forget to check if other process are open on ports: 80, 9000, 3306. Those ports will be used by docker.

#### First way
Note: Don't forget to give a+x permission to *start.sh* file
```
Run the start script in root folder. Using: ./start.sh
```
This command will do the following steps:
* Build application
* Install COMPOSER dependencies
* Run migrations

After this you can access the project by http://localhost

#### Second way

You can run all start script manually

```shell script
1. docker-compose up --build -d
2. docker run --rm --interactive --tty -v $PWD/agenda:/app composer install
(Optional) 3. docker ps -a 
```

After this you can access the project by http://localhost

### Setup Project Database

Open mysql bash as section "Acessing database" first item.
Then use:
```shell script
 mysql -u "$MYSQL_ROOT_USER" -p"$MYSQL_ROOT_PASSWORD" "$MYSQL_DATABASE" < "db/database_initial.sql"
```

## How to use

### Requiring a package
To require a package you can use the following command
```shell script
docker run --rm --interactive --tty -v $PWD/agenda:/app composer require *author/package_name*
```
Note: Don't forget to replace *author/package_name*. 

### Acessing database

Open bash in mysql container
```shell script
sudo docker exec -it mysql bash
```

Access mysql shell

```shell script
mysql -u "$MYSQL_ROOT_USER" -p"$MYSQL_ROOT_PASSWORD"
```


## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
