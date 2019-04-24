<?php

class ScoutOrgViewCustomlists extends JViewLegacy {
    /** @var \Org\Lib\CustomList[] */
    protected $lists;

    /** @var \Org\Lib\CustomList */
    protected $list;

    /** @var string */
    protected $path;
    
    public function display($tpl = null) {
        jimport('scoutorg.loader');

        $scoutorg = ScoutOrgLoader::load();

        $path = array();
        $this->list = $this->getList($scoutorg, $path);
        $this->path = implode('.', $path);

        if ($this->list === null) {
            $this->lists = $scoutorg->getCustomLists(true);
        }

        parent::display($tpl);
    }
    
    /**
     * Gets custom list with input id.
     * @param \Org\Lib\ScoutOrg $scoutorg
     */
    private function getList($scoutorg, &$path) {
        $lists = $scoutorg->getCustomLists(true);

        $input = JFactory::getApplication()->input;
        $ids = explode('.', $input->get('id', null));
        if ($ids === null) {
            return null;
        }

        return $this->traverseListsPath(null, $lists, $ids, $path);
    }

    /**
     * Traverses custom list to find the correct
     * list corresponding to the given list of ids
     * @param \Org\Lib\CustomList $parent
     * @param \Org\Lib\CustomList[] $idList
     * @param mixed[] $ids
     */
    private function traverseListsPath($parent, $lists, $ids, &$path) {
        if (empty($ids)) {
            return $parent;
        }
        $id = array_shift($ids);
        if (isset($lists[$id])) {
            $list = $lists[$id];
            array_push($path, $list->getId());
            $subLists = $list->getSubLists(true);
            return $this->traverseListsPath($list, $subLists, $ids, $path);
        }
        return null;
    }
}