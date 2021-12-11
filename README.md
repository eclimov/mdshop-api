# mdshop-api [![eclimov](https://circleci.com/gh/eclimov/mdshop-api.svg?style=svg)](https://circleci.com/gh/eclimov/mdshop-api)

## Upgrade PHP 7 to PHP 8 on Ubuntu
https://devanswers.co/how-to-upgrade-from-php-7-x-to-php-8-on-ubuntu-apache/

## Remove all Docker containers, images, volumes, - everything
`docker system prune` or `docker system prune -a`

## REST API with Symfony 5
https://digitalfortress.tech/tutorial/rest-api-with-symfony-and-api-platform/
https://api-platform.com/docs/core/jwt/
https://api-platform.com/docs/core/security/
https://api-platform.com/docs/core/subresources/

## File Upload with API Platform and Symfony
https://digitalfortress.tech/php/file-upload-with-api-platform-and-symfony/

`bin/console make:fixtures` - make a new fixture class
`bin/console security:hash-password`- hash a plain password

# bin/console doctrine:schema:drop --force && bin/console doctrine:schema:update --force && bin/console doctrine:fixtures:load -n
### Load mysql data
Within mysql container: `mysql> use mdshop; source /application/public/uploads/mysqlbackups/data.sql;`

1. On VM:  
   `chmod -R 777 config/jwt/`
   `chmod -R 777 public/uploads/`

# https://kags.me.ke/post/do-create-static-website/
# https://blog.khophi.co/deploy-to-digitalocean-from-circleci-overcome-permission-denied/

# Database Backups
1. `crontab -e`
2. `@weekly cd /root/mdshop-api/ && docker-compose exec mysql bash -c "mysqldump --no-create-info -u root -p'$(grep -oP '^MYSQL_ROOT_PASSWORD=\K.*' .env.local)' $(grep -oP '^MYSQL_DATABASE=\K.*' .env.local) > './public/uploads/mysqlbackups/$(date +%d-%m-%Y).sql'"`
