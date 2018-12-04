<?php

class ScoutorgControllerUserprofile extends JControllerBase {
    public function execute($task) {
        $view = $this->getView('userprofile', 'html');
        $view->setModel($this->getModel('userprofilefields'));
        return parent::execute($task);
    }
}