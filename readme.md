Laravel site template

```sql
mysql -uroot

create database sitetpl;
grant all privileges on sitetpl.* to sitetpl@localhost;

create database sitetpl_testing;
grant all privileges on sitetpl_testing.* to sitetpl@localhost;

create database sitetpl_dusk;
grant all privileges on sitetpl_dusk.* to sitetpl@localhost;

flush privileges;
```

- adopt ```.env``` file
- adopt translations in ```resources/lang/```
  - admin  in ```de.json```
  - frontend in folders ```*.php```

```sh
php artisan migrate
php artisan migrate --env=testing
```