<?php

class ScoutOrgHelper {
    /**
     * Generates a sidebar for all pages.
     * @param string $view The view name. Is hardcoded.
     * @return string
     */
    public static function addSubMenu(string $view) {
        JHtmlSidebar::addEntry(JText::_('COM_SCOUTORG_ADMIN_BRANCHES'), 
            'index.php?option=com_scoutorg&view=branches',
            $view == 'branches');
        JHtmlSidebar::addEntry(JText::_('COM_SCOUTORG_ADMIN_TROOPS'),
            'index.php?option=com_scoutorg&view=troops',
            $view == 'troops');
        JHtmlSidebar::addEntry(JText::_('COM_SCOUTORG_ADMIN_USERPROFILEFIELDS'),
            'index.php?option=com_scoutorg&view=userprofilefields',
            $view == 'userprofilefields');
        return JHtmlSidebar::render();
    }
}