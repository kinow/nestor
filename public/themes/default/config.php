<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Inherit from another theme
    |--------------------------------------------------------------------------
    |
    | Set up inherit from another if the file is not exists,
    | this is work with "layouts", "partials", "views" and "widgets"
    |
    | [Notice] assets cannot inherit.
    |
    */

    'inherit' => null, //default

    /*
    |--------------------------------------------------------------------------
    | Listener from events
    |--------------------------------------------------------------------------
    |
    | You can hook a theme when event fired on activities
    | this is cool feature to set up a title, meta, default styles and scripts.
    |
    | [Notice] these event can be override by package config.
    |
    */

    'events' => array(

        // Before event inherit from package config and the theme that call before,
        // you can use this event to set meta, breadcrumb template or anything
        // you want inheriting.
        'before' => function($theme)
        {
            // You can remove this line anytime.
            $theme->setTitle('Nestor-QA Test Management');

            // Breadcrumb template.
            // $theme->breadcrumb()->setTemplate('
            //     <ul class="breadcrumb">
            //     @foreach ($crumbs as $i => $crumb)
            //         @if ($i != (count($crumbs) - 1))
            //         <li><a href="{{ $crumb["url"] }}">{{ $crumb["label"] }}</a><span class="divider">/</span></li>
            //         @else
            //         <li class="active">{{ $crumb["label"] }}</li>
            //         @endif
            //     @endforeach
            //     </ul>
            // ');
        },

        // Listen on event before render a theme,
        // this event should call to assign some assets,
        // breadcrumb template.
        'beforeRenderTheme' => function($theme)
        {
            // You may use this event to set up your assets.
            // $theme->asset()->usePath()->add('core', 'core.js');
            // $theme->asset()->add('jquery', 'vendor/jquery/jquery.min.js');
            // $theme->asset()->add('jquery-ui', 'vendor/jqueryui/jquery-ui.min.js', array('jquery'));

            // Partial composer.
            // $theme->partialComposer('header', function($view)
            // {
            //     $view->with('auth', Auth::user());
            // });
        },

        // Listen on event before render a layout,
        // this should call to assign style, script for a layout.
        'beforeRenderLayout' => array(

            'default' => function($theme)
            {
                // $theme->asset()->usePath()->add('ipad', 'css/layouts/ipad.css');
            }

        ),

        'asset' => function($asset) {
        	// CSS
        	$asset->add('bootstrap', 'themes/default/assets/css/bootstrap.min.css');
        	$asset->add('bootstrap-responsive', 'themes/default/assets/css/bootstrap-responsive.min.css');
        	$asset->add('fancytree', 'themes/default/assets/css/plugins/fancytree/skin-lion/ui.fancytree.css');
        	$asset->add('style', 'themes/default/assets/css/style.css');
			// JS
        	$asset->add('jquery2', 'themes/default/assets/js/jquery-2.0.3.min.js');
        	$asset->add('jquery-cookie', 'themes/default/assets/js/plugins/jquery.cookie.js');
        	$asset->add('jquery-ui-custom', 'themes/default/assets/js/plugins/jqueryui/jqueryui.custom.js');
        	$asset->add('fancytree', 'themes/default/assets/js/plugins/fancytree/jquery.fancytree.min.js');
        	$asset->add('fancytree-childcounter', 'themes/default/assets/js/plugins/fancytree/extensions/jquery.fancytree.childcounter.js');
        	$asset->add('fancytree-persist', 'themes/default/assets/js/plugins/fancytree/extensions/jquery.fancytree.persist.js');
        	$asset->add('html5', 'themes/default/assets/js/html5.js');
        	$asset->add('bootstrap', 'themes/default/assets/js/bootstrap.min.js');
        	$asset->add('bootstrap-alert', 'themes/default/assets/js/bootstrap-alert.js');
        	$asset->add('bootstrap-transition', 'themes/default/assets/js/bootstrap-transition.js');
        	$asset->add('json2', 'themes/default/assets/js/json2.js');
        	$asset->add('nestor-style', 'themes/default/assets/js/script.js');
        }

    )

);