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
            <div class='row-fluid'>
                {{ Theme.breadcrumb.render() }}
            </div>
        </div>

        <div class="container">
        	{% if Session.has('success') %}
			<div class="alert alert-success">
			    <button type="button" class="close" data-dismiss="alert">&times;</button>
			    <p>{{ Session.get('success') }}</p>
			</div>
			{% endif %}
            {% if Session.has('warning') %}
            <div class="alert alert-block">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h4>Warning!</h4>
                <p>{{ Session.get('warning') }}</p>
            </div>
            {% endif %}
            {% if Session.has('error') %}
            <div class="alert alert-error">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <p>{{ Session.get('error') }}</p>
            </div>
            {% endif %}

            {% if Theme.getContentArguments()['success'] is defined %}
                {% for successes in Theme.getContentArguments()['successes'].all() %}
                <div class="alert alert-error">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <p>{{ success }}</p>
                </div>
                {% endfor %}
            {% endif %}

            {% if Theme.getContentArguments()['warnings'] is defined %}
                {% for warning in Theme.getContentArguments()['warnings'].all() %}
                <div class="alert alert-error">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <p>{{ warning }}</p>
                </div>
                {% endfor %}
            {% endif %}

            {% if Theme.getContentArguments()['errors'] is defined %}
                {% for error in Theme.getContentArguments()['errors'].all() %}
                <div class="alert alert-error">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <p>{{ error }}</p>
                </div>
                {% endfor %}
            {% endif %}

            {% if errors is defined %}
                {% for error in errors.all() %}
                <div class="alert alert-error">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <p>{{ error }}</p>
                </div>
                {% endfor %}
            {% endif %}

            {{ Theme.content() }}

            {{ Theme.partial('footer') }}
        </div>

        {{ Theme.asset().container('footer').scripts() }}
    </body>
</html>