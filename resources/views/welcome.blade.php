<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="Nestor-QA, Open Source test management server">
    <meta name="author" content="Nestor-QA team, Bruno P. Kinoshita, Peter Florijn">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="{{ asset('/js/libs/jquery.fancytree/dist/skin-lion/ui.fancytree.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/css/nestor.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet" type="text/css">
    <title>Nestor-QA</title>
</head>
<body>
    <br/>
    <div class="ui container" id="nestor-app">

        <div class="header" id="header"></div>
        <br/>
        <div class="ui breadcrumb" id="breadcrumb"></div>
        <hr class="ui hidden divider" />

        <div class="ui message" id="header-alert" style="display:none;">
          <i class="close icon"></i>
          <div class="header">
            Error
        </div>
        <p>Error message
        </p></div>

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
