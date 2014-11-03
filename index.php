<?php
// Set your Zend Framework library path(s) here - default is the master lib/ directory 
set_include_path('.'.PATH_SEPARATOR . './library'
    .PATH_SEPARATOR.'./application/models/'
    .PATH_SEPARATOR.get_include_path());      

include './application/bootstrap.php';

// Specify your config section here
$configSection = getenv('ZF_CONFIG') ? getenv('ZF_CONFIG') : 'dev';
$bootstrap = new Bootstrap($configSection);
$bootstrap->runApp();
