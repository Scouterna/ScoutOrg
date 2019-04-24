<?php
/**
 * @package Joomla.Administrator
 * @subpackage com_scoutorg
 */

class ScoutOrgViewTroops extends JViewLegacy {
    /**
	 * Display the troops view
	 * @param string $tpl
	 * @return void
	 */
	function display($tpl = null) {
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');

		$this->addToolbar();
		$this->sidebar = ScoutOrgHelper::addSubMenu('troops');

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
		JToolBarHelper::addNew('troop.add');
		JToolBarHelper::editList('troop.edit');
		JToolBarHelper::deleteList('', 'troops.delete');
	}
}