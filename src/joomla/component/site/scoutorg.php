<?php
defined('_JEXEC') or die('Restricted access');

JLoader::register('ScoutOrgHelper', JPATH_COMPONENT . '/helpers/scoutorg.php');

$controller = JControllerLegacy::getInstance('ScoutOrg');

$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));

$controller->redirect();