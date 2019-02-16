<?php

class ScoutOrgViewCustomlists extends JViewLegacy {
    /** @var \Org\Lib\CustomList[] */
    protected $lists;

    /** @var \Org\Lib\CustomList */
    protected $list;
    
    public function display($tpl = null) {
        jimport('scoutorg.loader');

        $scoutorg = ScoutOrgLoader::load();

        $this->list = $this->getList($scoutorg);

        if ($this->list === null) {
            $this->lists = $scoutorg->getCustomLists(true);
        }

        parent::display($tpl);
    }
    
    /**
     * Gets custom list with input id.
     * @param \Org\Lib\ScoutOrg $scoutorg
     */
    private function getList($scoutorg) {
        $idList = $scoutorg->getCustomLists(true);
        $nameList = $scoutorg->getCustomLists(false);

        $input = JFactory::getApplication()->input;
        $listId = $input->get('id', null);

        if ($listId === null) {
            return null;
        }

        if (isset($idList[$listId])) {
            return $idList[$listId];
        } elseif (isset($nameList[$listId])) {
            return $nameList[$listId];
        }

        return null;
    }
}