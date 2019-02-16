<?php

class ScoutOrgRouter extends JComponentRouterView {
    public function __construct($app = null, $menu = null) {
        $profile = new JComponentRouterViewconfiguration('userprofile');
        $profile->setKey('id')->setNestable();
        $this->registerView($profile);
        $customLists = new JComponentRouterViewconfiguration('customlists');
        $customLists->setKey('id')->setNestable();
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
        return array_reverse(array('root', $id), true);
    }

    public function getCustomlistsId($segment, $query) {
        return $segment;
    }
}