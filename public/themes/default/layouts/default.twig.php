<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{ Theme.get('title') }}</title>
        <meta charset="utf-8">
        <meta name="keywords" content="{{ Theme.get('keywords') }}">
        <meta name="description" content="{{ Theme.get('description') }}">
        <meta name="description" content="{{ Theme.get('author') }}">
        {{ Theme.asset().styles() }}
        {{ Theme.asset().scripts() }}
    </head>
    <body>
        {{ Theme.partial('header') }}

        <div class="container">
            {{ Theme.content() }}
        </div>

        {{ Theme.partial('footer') }}

        {{ Theme.asset().container('footer').scripts() }}
    </body>
</html>