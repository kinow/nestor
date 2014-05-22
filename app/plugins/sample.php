<?php 

Event::listen('home', function($data) 
{
	return $data + 123;
});