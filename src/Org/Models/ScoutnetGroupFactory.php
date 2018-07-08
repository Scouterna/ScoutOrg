<?php
/**
 * Contains ScoutnetGroupFactory class
 * @author Alexander Krantz
 */
namespace Org\Models;
use \Org\Scoutnet;

/**
 * A factory for generating a scout group from scoutnet.
 */
class ScoutnetGroupFactory implements IScoutGroupProvider {
    /** @var \Org\Scoutnet\ScoutnetController */
    private $scoutnet;

    /** @var BranchConfig[] */
    private $branchConfigs;

    /**
     * Creates a new scout group factory.
     * @param \Org\Scoutnet\ScoutnetController $scoutnet The scoutnet link.
     * @param BranchConfig[] $branchConfigs
     */
    public function __construct(Scoutnet\ScoutnetController $scoutnet,
                                array $branchConfigs = []) {
        $this->scoutnet = $scoutnet;
        $this->branchConfigs = $branchConfigs;
    }
    
    /**
     * Creates a scout group.
     * @return ScoutGroup|false 
     * A scout group if successfull, <code>false</code> if not.
     */
    public function getScoutGroup() {
        $scoutGroup = new ScoutGroup($this->scoutnet->getGroupId());

        if (($memberEntrys = $this->scoutnet->getMemberList()) === false) {
            return false;
        }
        foreach ($memberEntrys as $entry) {
            $newMember = new Member($entry->member_no->value);

            $newMember->startdate = $entry->confirmed_at->value;

            // Member specific info.
            $newMember->personInfo = $this->getPersonInfo($entry);
            $newMember->contactInfo =  $this->getContactInfo($entry);
            $newMember->accommodation = $this->getAccommodation($entry);
            $newMember->contacts = $this->getContacts($entry);

            // Create role groups
            if ($entry->group_role !== null) {
                $roleGroups = $this->getRoleGroups($scoutGroup, $entry->group_role);
                foreach ($roleGroups as $roleGroup) {
                    $newMember->roleGroupsIdIndexed[intval($roleGroup->id)] = $roleGroup;
                    $newMember->roleGroupsNameIndexed[$roleGroup->roleName] = $roleGroup;
                    $roleGroup->members[intval($newMember->id)] = $newMember;
                }
            }

            // Create troop(s) (avdelningar)
            if ($entry->unit !== null) {
                $troop = $this->getTroop($scoutGroup, $entry->unit);
                $troopMemberLink = new TroopMemberLink(
                    $troop,
                    $newMember,
                    $entry->unit_role !== null ? $entry->unit_role : '');
                $newMember->troopsIdIndexed[intval($troop->id)] = $troopMemberLink;
                $newMember->troopsNameIndexed[$troop->name] = $troopMemberLink;
                $troop->members[intval($newMember->id)] = $troopMemberLink;
            }

            // Create patrol(s)
            if ($entry->patrol !== null) {
                $patrol = $this->getPatrol($troop, $entry->patrol);
                $patrolMemberLink = new PatrolMemberLink($patrol, $newMember, '');
                $newMember->patrolsIdIndexed[$patrol->id] = $patrolMemberLink;
                $patrol->members[$newMember->id] = $patrolMemberLink;
            }

            $scoutGroup->members[$newMember->id] = $newMember;
        }

        // Create branches
        foreach ($this->branchConfigs as $branchConfig) {
            $newBranch = new Branch($branchConfig->getBranchId(), $branchConfig->getBranchName());

            if (isset($scoutGroup->branchesIdIndexed[$branchConfig->getBranchId()])) {
                $newBranch = $scoutGroup->branchesIdIndexed[$branchConfig->getBranchId()];
            } else {
                $scoutGroup->branchesIdIndexed[$branchConfig->getBranchId()] = $newBranch;
                $scoutGroup->branchesNameIndexed[$branchConfig->getBranchName()] = $newBranch;
            }
            
            foreach ($branchConfig->getTroopIds() as $troopId) {
                if (isset($newBranch->troopsIdIndexed[$troopId])) {
                    continue;
                }
                if (!isset($scoutGroup->troopsIdIndexed[$troopId])) {
                    continue;
                }
                $troop = $scoutGroup->troopsIdIndexed[$troopId];
                if ($troop->branch !== null) {
                    continue;
                }
                $newBranch->troopsIdIndexed[$troopId] = $troop;
                $newBranch->troopsNameIndexed[$troop->name] = $troop;
                $troop->branch = $newBranch;
            }
        }

        // Create custom member lists from scoutnet custom lists.
        $customLists = $this->scoutnet->getCustomLists();
        if ($customLists !== false) {
            foreach ($customLists as $customList) {
                $newCustomList = new CustomList($customList->id, $customList->title, $customList->description);
                $customListMembers = $this->scoutnet->getCustomListMembers($customList->id);
                foreach ($customListMembers as $member) {
                    $newCustomList->members[intval($member->member_no->value)] = $scoutGroup->members[intval($member->member_no->value)];
                }
                foreach ($customList->rules as $customListRule) {
                    $newCustomSubList = new CustomSubList($customListRule->id, $customListRule->title);
                    $customListRuleMembers = $this->scoutnet->getCustomListMembers($customList->id, $customListRule->id);
                    foreach ($customListRuleMembers as $member) {
                        $newCustomSubList->members[intval($member->member_no->value)] = $scoutGroup->members[intval($member->member_no->value)];
                    }
                    $newCustomList->subListsIdIndexed[intval($customListRule->id)] = $newCustomSubList;
                    $newCustomList->subListsTitleIndexed[$customListRule->title] = $newCustomSubList;
                }
                $scoutGroup->customListsIdIndexed[intval($customList->id)] = $newCustomList;
                $scoutGroup->customListsTitleIndexed[$customList->title] = $newCustomList;
            }
        }
        
        return $scoutGroup;
    }

    /** @return RoleGroup[] */
    private function getRoleGroups(ScoutGroup $group, Scoutnet\ValueAndRaw $roleGroupsInfo) {
        $roleGroupNames = explode(', ', $roleGroupsInfo->value);
        $roleGroups = [];
        foreach (explode(',', $roleGroupsInfo->rawValue) as $index => $roleGroupId) {
            if (isset($group->roleGroupsIdIndexed[intval($roleGroupId)])) {
                $roleGroups[] = $group->roleGroupsIdIndexed[intval($roleGroupId)];
            } else {
                $roleGroupName = $roleGroupNames[$index];
                $roleGroup = new RoleGroup($roleGroupId, $roleGroupName, $group);
                $group->roleGroupsIdIndexed[intval($roleGroupId)] = $roleGroup;
                $group->roleGroupsNameIndexed[$roleGroupName] = $roleGroup;
                $roleGroups[] = $roleGroup;
            }
        }
        return $roleGroups;
    }

    /** @return Troop */
    private function getTroop(ScoutGroup $group, Scoutnet\ValueAndRaw $troopInfo) {
        if (isset($group->troopsIdIndexed[intval($troopInfo->rawValue)])) {
            return $group->troopsIdIndexed[intval($troopInfo->rawValue)];
        } else {
            $troop = new Troop($troopInfo->rawValue, $troopInfo->value, $group);
            $group->troopsIdIndexed[intval($troopInfo->rawValue)] = $troop;
            $group->troopsNameIndexed[$troopInfo->value] = $troop;
            return $troop;
        }
    }

    /** @return Patrol */
    private function getPatrol(Troop $troop, Scoutnet\ValueAndRaw $patrolInfo) {
        if (isset($troop->patrolsIdIndexed[intval($patrolInfo->rawValue)])) {
            return $troop->patrolsIdIndexed[intval($patrolInfo->rawValue)];
        } else {
            $patrol = new Patrol($patrolInfo->rawValue, $patrolInfo->value);
            $troop->patrolsIdIndexed[intval($patrolInfo->rawValue)] = $patrol;
            $troop->patrolsNameIndexed[$patrolInfo->value] = $patrol;
            $patrol->troop = $troop;
            return $patrol;
        }
    }

    /** @return PersonInfo */
    private function getPersonInfo(Scoutnet\MemberEntry $entry) {
        $personInfo = new PersonInfo($entry->first_name,
            $entry->last_name,
            $entry->ssno,
            $entry->sex,
            $entry->date_of_birth);
        return $personInfo;
    }

    /** @return ContactInfo */
    private function getContactInfo(Scoutnet\MemberEntry $entry) {
        $phoneNumbers = [];
        if ($entry->contact_mobile_phone !== NULL) {
            $phoneNumbers[] = $entry->contact_mobile_phone;
        }
        if ($entry->contact_home_phone !== NULL) {
            $phoneNumbers[] = $entry->contact_home_phone;
        }
        if ($entry->contact_work_phone !== NULL) {
            $phoneNumbers[] = $entry->contact_work_phone;
        }
        $emailAddresses = [];
        if ($entry->contact_email !== NULL) {
            $emailAddresses[] = $entry->contact_email;
        }
        if ($entry->contact_alt_email !== NULL) {
            $emailAddresses[] = $entry->contact_alt_email;
        }
        return new ContactInfo($phoneNumbers, $emailAddresses);
    }

    /** @return Location */
    private function getAccommodation(Scoutnet\MemberEntry $entry) {
        return new Location($entry->address_1, $entry->postcode, $entry->town);
    }

    /** @return Contact[] */
    private function getContacts(Scoutnet\MemberEntry $entry) {
        $contacts = [];
        // Create contact 1
        if ($entry->contact_mothers_name !== NULL) {
            $phoneNumbers = [
                $entry->contact_mobile_mum,
            ];
            $emails = [
                $entry->contact_email_mum,
            ];
            $contactInfo = new ContactInfo($phoneNumbers, $emails);
            $contacts[] = new Contact($entry->contact_mothers_name, $contactInfo);
        }
        // Create contact 2
        if ($entry->contact_fathers_name !== NULL) {
            $phoneNumbers = [
                $entry->contact_mobile_dad,
            ];
            $emails = [
                $entry->contact_email_dad,
            ];
            $contactInfo = new ContactInfo($phoneNumbers, $emails);
            $contacts[] = new Contact($entry->contact_fathers_name, $contactInfo);
        }
        return $contacts;
    }
}