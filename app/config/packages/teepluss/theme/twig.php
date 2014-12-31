<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | PHP alow in twig
    |--------------------------------------------------------------------------
    |
    | This is laravel alias to allow in twig compiler
    | The list all of methods is at /app/config/app.php
    |
    */

    'allows' => array(
    	'Nestor',

        'Auth',
        'Cache',
        'Config',
        'Cookie',
    	'Session',
        'Form',
        'HTML',
        'Input',
        'Lang',
        'Paginator',
        'Str',
        'Theme',
        'URL',
        'Validator',
        'Session'
    ),

    /*
    |--------------------------------------------------------------------------
    | PHP alow in twig
    |--------------------------------------------------------------------------
    |
    | This is laravel alias to allow in twig compiler
    | The list all of methods is at /app/config/app.php
    |
    */

    'hooks' => function($twig)
    {
        // Example add function name "demo".
        /*$function = new Twig_SimpleFunction('example', function()
        {
            $args = func_get_args();

            return "Example" . print_r($args, true);
        });

        $twig->addFunction($function);*/

    	$fns = array(
    		'print_r',
    		'var_dump',
            'var_export',
    		'isset',
    		'count',
            'current',
            'next',
            'dd'
    	);

    	foreach ($fns as $fn)
    	{
    		$fn_obj = new Twig_SimpleFunction($fn, function()
    		{
    			$value = func_get_args();
    			return print_r($value);
    		});
    		$twig->addFunction($fn_obj);
    	}
        return $twig;
    }

);