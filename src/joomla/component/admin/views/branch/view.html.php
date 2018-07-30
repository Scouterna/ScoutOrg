<?php

class ScoutOrgViewBranch extends JViewLegacy
{
    protected $form;
    protected $item;

    /**
     * Display the Branch view
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     */
    public function display($tpl = null)
    {
        // Get the Data
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }

        // Set the toolbar
        $this->addToolBar();

        // Display the template
        parent::display($tpl);

        // Set the document
        $this->setDocument();
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     *
     * @since   1.6
     */
    protected function addToolBar()
    {
        $input = JFactory::getApplication()->input;

        // Hide Joomla Administrator Main menu
        $input->set('hidemainmenu', true);

        $isNew = ($this->item->id == 0);

        JToolBarHelper::title($isNew ? JText::_('COM_SCOUTORG_MANAGER_BRANCH_NEW')
                                     : JText::_('COM_SCOUTORG_MANAGER_BRANCH_EDIT'), 'branch');
        // Build the actions for new and existing records.
        
        JToolBarHelper::apply('branch.apply', 'JTOOLBAR_APPLY');
        JToolBarHelper::save('branch.save', 'JTOOLBAR_SAVE');

        if ($isNew) {
            JToolBarHelper::cancel('branch.cancel', 'JTOOLBAR_CANCEL');
        } else {
            JToolBarHelper::cancel('branch.cancel', 'JTOOLBAR_CLOSE');
        }
    }
    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument()
    {
        $isNew = ($this->item->id == 0);
        $document = JFactory::getDocument();
        $document->setTitle($isNew ? JText::_('COM_SCOUTORG_MANAGER_BRANCH_NEW')
                                   : JText::_('COM_SCOUTORG_MANAGER_BRANCH_EDIT'));
        $document->addScript(__DIR__.'/submitbutton.js');
        JText::script('COM_SCOUTORG_ERROR_INVALIDINPUT');
    }
}
