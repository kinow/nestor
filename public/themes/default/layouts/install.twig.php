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
        <div class="container">
        {% block content %}
            {{ Theme.content() }}
        </div>
        {% endblock %}
		</div>
    </body>
</html>