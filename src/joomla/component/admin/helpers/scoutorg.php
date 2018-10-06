<?php

class ScoutOrgHelper {
    /**
     * Generates a sidebar for all pages.
     * @param string $view The view name. Is hardcoded.
     * @return string
     */
    public static function addSubMenu(string $view) {
        JHtmlSidebar::addEntry(JText::_('COM_SCOUTORG_ADMIN_SCOUTNETBRANCHES'), 
            'index.php?option=com_scoutorg&view=scoutnetbranches',
            $view == 'scoutnetbranches');
        JHtmlSidebar::addEntry(JText::_('COM_SCOUTORG_ADMIN_SCOUTNETTROOPS'),
            'index.php?option=com_scoutorg&view=scoutnettroops',
            $view == 'scoutnettroops');
        return JHtmlSidebar::render();
    }
}