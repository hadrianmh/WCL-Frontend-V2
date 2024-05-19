# WCL Production V1

### Configuration
Setup your connection database on `src/auth/connect.php`
```
$host = 'host.docker.internal';
$user = 'root';
$pass = 'root';
$dbase = 'apps_v3';
```
and your `docker-compose.yml`

```
# MySQL Service
  db:
    .............................
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: apps_v3
      MYSQL_USER: root
      MYSQL_PASSWORD: root
```
### How to run
```
docker compose up -d --build
```
### Base URL
```
http://localhost:8080/
```