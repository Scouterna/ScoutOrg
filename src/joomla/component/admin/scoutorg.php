<?php
defined('_JEXEC') or die('Restricted access');

if (!JFactory::getUser()->authorise('core.manage', 'com_scoutorg')) {
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

JLoader::register('ScoutOrgHelper', JPATH_COMPONENT . '/helpers/scoutorg.php');

$controller = JControllerLegacy::getInstance('ScoutOrg');

$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));

$controller->redirect();