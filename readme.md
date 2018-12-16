Laravel site template

## Setup php
```sh
 sudo apt-get install nginx

 sudo apt-get install php7.2-fpm
 sudo apt-get install php7.2-mbstring php7.2-curl php7.2-dom php7.2-xml php7.2-zip php7.2-mysql
 sudo apt-get install composer
```
## Setup DB
```sh
mysql -uroot
```

```sql
create database sitetpl;
grant all privileges on sitetpl.* to sitetpl@localhost IDENTIFIED by 'password';
```

```sql
create database sitetpl_testing;
grant all privileges on sitetpl_testing.* to sitetpl@localhost;
```

```sql
create database sitetpl_dusk;
grant all privileges on sitetpl_dusk.* to sitetpl@localhost;
```

```sql
flush privileges;
```

### Run migrations

```sh
php artisan migrate
```

## Translations

  - frontend in subfolders ```resources/lang/**.php```
  - admin  in ```resources/lang/de.json```


## Run code quality checks

Run all checks:

```sh
php artisan codequality
```

or run each check:

```sh
php artisan phplint
php artisan phpmd
php artisan phpcs

```

To configure checks, see ``config/phpmd`` and ``config/phpcs``

## Run test

```sh
php artisan dusk
```
