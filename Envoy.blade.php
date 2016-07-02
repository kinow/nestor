@servers(['web' =>  'root@162.243.246.75'])

@task('deploy', ['on' => 'web'])
    cd /var/www/nestor-qa/public_html
    git fetch --all
    git reset --hard origin/master
    git pull origin master
    cp .env.docker .env
    sed -i 's/=local/=prod/' .env
    touch database/database.sqlite
    composer dump-autoload
    composer update --no-dev --prefer-dist --profile -vvv --no-scripts
    php artisan migrate:refresh --force
    php artisan db:seed
@endtask
