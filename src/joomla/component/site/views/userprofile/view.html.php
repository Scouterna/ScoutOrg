<?php

class ScoutorgViewUserprofile extends JViewLegacy {
    /** @var \Org\Lib\Member */
    protected $member;

    public function display($tpl) {
        jimport('scoutorg.loader');
        $members = ScoutOrgLoader::load()->getScoutGroup()->getMembers();

        $input = JFactory::getApplication()->input;

        $memberId = $input->get('id');

        if ($memberId === null) {

        }

        parent::display($tpl);
    }

    private function getMemberId() {
        $input = JFactory::getApplication()->input;
        $memberId = $input->get('id', null);
        if ($memberId === null) {
            $user = JFactory::getUser();
            if ($user->guest) {
                return null;
            }
            return $user->id;
        }
        return $memberId;
    }
}