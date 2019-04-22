<?php

class ScoutOrgRouter extends JComponentRouterView {
    public function __construct($app = null, $menu = null) {
        $profile = new JComponentRouterViewconfiguration('userprofile');
        $profile->setKey('id')->setNestable();
        $this->registerView($profile);
        $customLists = new JComponentRouterViewconfiguration('customlists');
        $customLists->setKey('id')->setNestable()->setParent($customLists, 'id');
        $this->registerView($customLists);

        parent::__construct($app, $menu);

        $this->attachRule(new JComponentRouterRulesMenu($this));
        $this->attachRule(new JComponentRouterRulesStandard($this));
        $this->attachRule(new JComponentRouterRulesNomenu($this));
    }

    public function getUserprofileSegment($id, $query) {
        return array_reverse(array('root', $id), true);
    }

    public function getUserprofileId($segment, $query) {
        return $segment;
    }

    public function getCustomlistsSegment($id, $query) {
        jimport('scoutorg.loader');
        $scoutorg = ScoutOrgLoader::load();
        $lists = $scoutorg->getCustomLists(true);
        $ids = explode('.', $id);
        $path = array();
        $result = $this->traverseListsPath(null, $lists, $ids, $path);
        if ($result === null) {
            return false;
        }
        array_unshift($path, 'root');
        return array_reverse($path, true);
    }

    public function getCustomlistsId($segment, $query) {
        jimport('scoutorg.loader');
        $scoutorg = ScoutOrgLoader::load();
        $lists = $scoutorg->getCustomLists(true);
        $ids = explode('.', $query['id']);
        $path = array();
        $list = $this->traverseListsPath(null, $lists, $ids, $path);
        if ($list === null) {
            $subLists = $scoutorg->getCustomLists(false);
        } else {
            $subLists = $list->getSubLists(false);
        }
        $subList = $this->findList($subLists, $segment);
        if ($subList !== null) {
            if ($query['id']) {
                return $query['id'].'.'.$subList->getId();
            }
            return "{$subList->getId()}";
        }
        return false;
    }

    /**
     * Traverses custom list to find the correct
     * list corresponding to the given list of ids
     * @param \Org\Lib\CustomList[] $idList
     * @param \Org\Lib\CustomList[] $nameList
     * @param mixed[] $ids
     * @param string[] &$path
     * @return \Org\Lib\CustomList
     */
    private function traverseListsPath($parent, $lists, $ids, &$path) {
        if (empty($ids)) {
            return $parent;
        }
        $id = array_shift($ids);
        if (isset($lists[$id])) {
            $list = $lists[$id];
            array_push($path, $this->simplifyListName($list->getTitle()));
            $subLists = $list->getSubLists(true);
            return $this->traverseListsPath($list, $subLists, $ids, $path);
        }
        return null;
    }

    /**
     * Simplifies list name to one with
     * dashes instead of spaces
     * lowercase chars
     * @param string @name
     * @return string
     */
    private function simplifyListName($name) {
        $name = str_replace(' ', '-', $name);
        $name = JString::strtolower($name);
        return $name;
    }

    /**
     * Finds list in array with simplified
     * list name instead of normal isset()
     * @param \Org\Lib\CustomList[] $lists
     * @param string $name
     * @return \Org\Lib\CustomList|null
     */
    private function findList($lists, $name) {
        foreach ($lists as $list) {
            $listName = $this->simplifyListName($list->getTitle());
            if ($listName == $name) {
                return $list;
            }
        }
        return null;
    }
}