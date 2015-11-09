## Nestor-QA

Nestor is a quality assurance, Open Source test management server.

### Features include

- Test projects
- Test suites
- Test cases

### Running in development mode

- git clone
- install composer, php5 and php5-mcrypt
- run composer install
- php artisan key:generate and copy the $KEY
- vim .env

  APP_KEY=$KEY
  API_STANDARDS_TREE=vnd
  API_SUBTYPE=nestorqa
  API_PREFIX=api
  API_VERSION=v1
  API_NAME=Nestor-QA API
  API_CONDITIONAL_REQUEST=false
  API_STRICT=false

- php artisan serve and browse http://localhost:8000
