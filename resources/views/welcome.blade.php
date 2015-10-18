<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="Nestor-QA, Open Source test management server">
    <meta name="author" content="Nestor-QA team, Bruno P. Kinoshita, Peter Florijn">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="{{ asset('/css/nestor.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet" type="text/css">
    <title>Nestor-QA</title>
</head>
<body>
<br/>
<div class="ui container">
    @include ("menu")

    <div id="page">
        <div class="ui segment">
          <p></p>
          <div class="ui active dimmer">
            <div class="ui loader"></div>
          </div>
        </div>
    </div>
</div>

</body>
<script data-main="js/main" src="{{ asset('/js/libs/requirejs/require.js') }}"></script>
</html>
