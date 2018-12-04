<?php

class ScoutOrgViewCustomlists extends JViewLegacy {
    /** @var \Org\Lib\CustomList[] */
    protected $lists;
    
    public function display($tpl = null) {
        jimport('scoutorg.loader');
        $this->lists = ScoutOrgLoader::load()->getCustomLists();

        parent::display($tpl);
    }
}