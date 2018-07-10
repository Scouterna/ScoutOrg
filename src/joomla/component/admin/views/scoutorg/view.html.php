<?php
/**
 * @package Joomla.Administrator
 * @subpackage com_scoutorg
 */

class ScoutOrgViewScoutOrg extends JViewLegacy {
    /**
	 * Display the ScoutOrg view
	 * @param string $tpl
	 * @return void
	 */
	function display($tpl = null)
	{
		// Assign data to the view
		$this->msg = 'Hello World';

		// Display the view
		parent::display($tpl);
	}
}