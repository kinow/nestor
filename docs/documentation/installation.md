---
layout: page
title: Installation
description: Installing Nestor
---

## Requirements

You will need [Composer](http://getcomposer.org) to download Nestor dependencies and 
[MCrypt](http://www.php.net/manual/en/mcrypt.installation.php) for Laravel.

## Installing in Apache

*WIP*

## Installing in Nginx

*WIP*

## Running Nestor with PHP built-in Web server

PHP comes with a built-in Web server since its 5.4.0 release. You can use this Web server
to test Nestor. You can choose the SQLite database and have a Nestor instance in seconds.

First download Nestor from GitHub or clone the repository into a directory (NESTOR_HOME).

    $> cd $NESTOR_HOME/
    $> composer install
    $> cd $NESTOR_HOME/public
    $> php -S 127.0.0.1:7000

Open [http://localhost:7000/](http://localhost:7000) in your browse to test Nestor.


