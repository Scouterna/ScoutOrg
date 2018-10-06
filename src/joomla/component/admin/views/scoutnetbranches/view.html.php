<?php
/**
 * @package Joomla.Administrator
 * @subpackage com_scoutorg
 */

class ScoutOrgViewScoutnetBranches extends JViewLegacy {
    /**
	 * Display the branches view
	 * @param string $tpl
	 * @return void
	 */
	function display($tpl = null) {
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		$this->addToolbar();
		$this->sidebar = ScoutOrgHelper::addSubMenu('scoutnetbranches');

		parent::display($tpl);

		$this->setDocument();
	}

	private function setDocument() {
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_SCOUTORG_ADMINISTRATION'));
		$document->addScript(__DIR__.'/submitbutton.js');
	}

	private function addToolbar() {	
		JToolBarHelper::title(JText::_('COM_SCOUTORG_ADMINISTRATION'), 'generic.png');
		JToolBarHelper::addNew('scoutnetbranch.add');
		JToolBarHelper::editList('scoutnetbranch.edit');
		JToolBarHelper::deleteList('', 'scoutnetbranches.delete');
	}
}