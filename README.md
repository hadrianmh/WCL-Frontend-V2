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
      ...........................
      MYSQL_DATABASE: apps_v3
      MYSQL_USER: wcl
      MYSQL_PASSWORD: wcl
      MYSQL_ROOT_HOST: '%'
      MYSQL_ROOT_PASSWORD_EXPIRE: 'false'
      MYSQL_ONETIME_PASSWORD: 'false'
      MYSQL_INITDB_SKIP_TZINFO: 'true'
```
### How to run
```
docker compose up -d --build
```
### Base URL
```
http://localhost:8080/
```