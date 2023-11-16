# NeoSoft PHP Test

## Launch project in local

- install docker
- run the following commands

```bash
docker-compose up -d
docker exec -it neosoft-be bash
composer install
```

In order to migrate and seed data uncomment the following code inside  the **/public/index.php** file:
```php
$db = Connection::getInstance();
$db->migrate();    
$db->seed(); 
```