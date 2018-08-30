<?php
namespace Org\Scoutnet;
use \Org\Lib;

class CustomListsFactory implements Lib\ICustomListsProvider {
    private $scoutnet;

    public function __construct(ScoutnetController $scoutnet) {
        $this->scoutnet = $scoutnet;
    }
    
    public function getCustomLists(Lib\ScoutGroup $scoutGroup) {
        $customLists = [];
        $customListEntrys = $this->scoutnet->getCustomLists();
        if ($customListEntrys) {
            $customMemberListIds = [];
            foreach ($customListEntrys as $customListEntry) {
                $customList = $customListEntry->getCustomList();
                $customListMembers = $this->scoutnet->getCustomListMembers($customList->getId());
                if ($customListMembers) {
                    foreach ($customListMembers as $entry) {
                        $customList->addMember($scoutGroup->getMembers()[$entry->member_no->value]);
                    }
                }
                foreach ($customListEntry->rules as $customListRuleEntry) {
                    $customSubList = $customListRuleEntry->getCustomList();
                    $customList->addSubList($customSubList);
                    $customSubListMembers = $this->scoutnet->getCustomListMembers(
                        $customList->getId(), $customSubList->getId());
                    if ($customSubListMembers) {
                        foreach ($customSubListMembers as $entry) {
                            $customSubList->addMember($scoutGroup->getMembers()[$entry->member_no->value]);
                        }
                    }
                }
                $customLists[] = $customList;
            }
        } else {
            return false;
        }
        return $customLists;
    }
}