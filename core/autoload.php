<?php

@ob_start();
@ob_implicit_flush(0);

if (!defined('E_DEPRECATED'))
{
    @error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
    @ini_set('error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE);
}
else
{
    @error_reporting(E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE);
    @ini_set('error_reporting', E_ALL ^ E_WARNING ^ E_DEPRECATED ^ E_NOTICE);
}

@ini_set('display_errors', true);
@ini_set('html_errors', false);

define('_CEXEC', 1);

if (file_exists(BASE_DIR . '/vendor/autoload.php')) include_once BASE_DIR . '/vendor/autoload.php';
if (file_exists(BASE_DIR . '/core/core.php'))
{
    include_once BASE_DIR . '/core/core.php';
}
else
{
    die('Core not found.');
}