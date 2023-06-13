# Hockey tournament imitation

## Installation
```
composer update
cp .env.sample .env
# edit the .env with your db info
bin/console make:migration
bin/console doctrine:migrations:migrate
```
## Run the test
```
cp .env.test.sample .env.test
# add db into to .env.test.sample
bin/console doctrine:migrations:migrate -e test
vendor/bin/codecept run Games
```