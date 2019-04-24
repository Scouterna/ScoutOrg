<?php

define('_JEXEC', 1);
define('JPATH_BASE', '/var/www/html/');
require_once JPATH_BASE . 'includes/defines.php';
require_once JPATH_BASE . 'includes/framework.php';

// Create the Application
global $app;
$app = JFactory::getApplication('site');