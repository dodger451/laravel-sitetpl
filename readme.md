Laravel site template


## Setup DB
```sql
mysql -uroot

create database sitetpl;
GRANT ALL PRIVILEGES ON sitetpl.* To sitetpl@localhost IDENTIFIED BY 'password';

create database sitetpl_testing;
grant all privileges on sitetpl_testing.* to sitetpl@localhost;

create database sitetpl_dusk;
grant all privileges on sitetpl_dusk.* to sitetpl@localhost;

flush privileges;
```

### Migrate
```sh
php artisan migrate
```

## Translations

  - frontend in subfolders ```resources/lang/**.php```
  - admin  in ```resources/lang/de.json```


`

## Code quality

```sh
php artisan phplint
php artisan phpmd
php artisan phpcs
# or for all
php artisan codequality
```
See ``config/phpmd`` and ``config/phpcs``

## Test

```sh
php artisan dusk
```
