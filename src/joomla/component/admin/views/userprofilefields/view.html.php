<?php

class ScoutorgViewUserprofilefields extends JViewLegacy {
	function display($tpl = null) {
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');

		$this->addToolbar();
		$this->sidebar = ScoutOrgHelper::addSubMenu('userprofilefields');

		parent::display($tpl);

		$this->setDocument();
	}

	private function setDocument() {
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_SCOUTORG_ADMINISTRATION'));
	}

	private function addToolbar() {	
		JToolBarHelper::title(JText::_('COM_SCOUTORG_ADMINISTRATION'), 'generic.png');
		JToolBarHelper::addNew('userprofilefield.add');
		JToolBarHelper::editList('userprofilefield.edit');
		JToolBarHelper::deleteList('', 'userprofilefields.delete');
	}
}