@servers(['web' => 'www-data@162.243.246.75'])

@task('deploy', ['on' => 'web'])
    cd /var/www/nestor-qa/public_html/
    git reset --hard HEAD
    git clean -f
    git pull origin master
    composer dump-autoload
    composer update --no-dev --prefer-dist --profile -vvv
    rm -f app/database/production.sqlite
    touch app/database/production.sqlite
    php artisan migrate:install
    php artisan migrate:refresh --seed
@endtask