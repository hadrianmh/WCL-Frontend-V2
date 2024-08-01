# WCL Frontend V2

### Configuration
Define `$this->host`, `$this->port`, and `$this->endpoint` Rest API WCL v2 on `src/utils/api.php`
```
public function __construct($credential = null) {
    $this->host     = "host.docker.internal";
    $this->port     = "8082";
    $this->endpoint = "/api/v1";
    ......
}
```
Define JWT secret key on `$key` on `src/utils/jwt.php`
```
function decode_jwt($jwt) {
    try {
		$key = "33b9b3de94a42d19f47df7021954eaa8";
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
       .......
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