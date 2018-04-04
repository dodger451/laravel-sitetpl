Laravel site template

```sql
mysql -uroot
create database sitetpl;
create database sitetpl_testing;
grant all privileges on sitetpl.* to sitetpl@localhost;
grant all privileges on sitetpl_testing.* to sitetpl@localhost;
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