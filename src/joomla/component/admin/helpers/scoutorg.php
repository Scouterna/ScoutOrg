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

    /**
     * Evaluates fieldtype name for given fieldtype
     * @param string $field field type name
     * @return string|false
     */
    public static function evalFieldname(string $field) {
        if ($field === 'org-id') {
            return JText::_('COM_SCOUTORG_USERPROFILEFIELD_FIELD_ID');
        } elseif ($field === 'org-fullname') {
            return JText::_('COM_SCOUTORG_USERPROFILEFIELD_FIELD_FULLNAME');
        } elseif ($field === 'org-firstname') {
            return JText::_('COM_SCOUTORG_USERPROFILEFIELD_FIELD_FIRSTNAME');
        } elseif ($field === 'org-lastname') {
            return JText::_('COM_SCOUTORG_USERPROFILEFIELD_FIELD_LASTNAME');
        } elseif ($field === 'org-age') {
            return JText::_('COM_SCOUTORG_USERPROFILEFIELD_FIELD_AGE');
        } elseif ($field === 'org-birthdate') {
            return JText::_('COM_SCOUTORG_USERPROFILEFIELD_FIELD_BIRTHDATE');
        } elseif ($field === 'org-gender') {
            return JText::_('COM_SCOUTORG_USERPROFILEFIELD_FIELD_GENDER');
        } elseif ($field === 'org-ssno') {
            return JText::_('COM_SCOUTORG_USERPROFILEFIELD_FIELD_SSNO');
        } elseif ($field === 'org-home') {
            return JText::_('COM_SCOUTORG_USERPROFILEFIELD_FIELD_HOME');
        } elseif ($field === 'org-emails') {
            return JText::_('COM_SCOUTORG_USERPROFILEFIELD_FIELD_EMAILS');
        } elseif ($field === 'org-telnrs') {
            return JText::_('COM_SCOUTORG_USERPROFILEFIELD_FIELD_TELNRS');
        } elseif ($field === 'org-startdate') {
            return JText::_('COM_SCOUTORG_USERPROFILEFIELD_FIELD_STARTDATE');
        } elseif ($field === 'org-contacts') {
            return JText::_('COM_SCOUTORG_USERPROFILEFIELD_FIELD_CONTACTS');
        } elseif ($field === 'org-troops') {
            return JText::_('COM_SCOUTORG_USERPROFILEFIELD_FIELD_TROOPS');
        } elseif ($field === 'org-rolegroups') {
            return JText::_('COM_SCOUTORG_USERPROFILEFIELD_FIELD_ROLEGROUPS');
        } elseif ($field === 'org-code') {
            return JText::_('COM_SCOUTORG_USERPROFILEFIELD_FIELD_CODE');
        } else {
            return false;
        }
    }
}