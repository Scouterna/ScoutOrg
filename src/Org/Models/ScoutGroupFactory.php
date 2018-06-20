<?php
namespace Org\Models;
use \Org\Scoutnet\Scoutnet;

/**
 * A factory for generating a scout group from its id and api key
 * using a specific server provided with a ScoutnetController
 */
class ScoutGroupFactory {
    /** @var int */
    private $id;

    /** @var string */
    private $key;

    /** @var Scoutnet */
    private $scoutnetController;

    /**
     * Creates a new ScoutGroup factory.
     * @param int $id The scout group scoutnet id.
     * @param string $key the member list api key.
     * @param Scoutnet $scoutnetController The scoutnet link.
     */
    public function __construct(int $id, string $key, Scoutnet $scoutnetController) {
        $this->id = $id;
        $this->key = $key;
        $this->scoutnetController = $scoutnetController;
    }
    
    /**
     * Creates a scout group.
     * @return ScoutGroup
     */
    public function createObject() {
        $scoutGroup = new ScoutGroup($this->id);

        $memberEntrys = $this->scoutnetController->getMemberList($this->id, $this->key);
        foreach ($memberEntrys as $entry) {
            $newMember = new Member($entry->member_no->value);

            $newMember->startdate = $entry->confirmed_at->value;
            $newMember->troopRole = $entry->unit_role->value;

            $newMember->personInfo = $this->getPersonInfo($entry);
            $newMember->contactInfo =  $this->getContactInfo($entry);
            $newMember->accommodation = $this->getAccommodation($entry);
            $newMember->contacts = $this->getContacts($entry);

            if ($entry->group_role !== null) {
                $roleGroups = $this->getRoleGroups($scoutGroup, $entry->group_role);
                foreach ($roleGroups as $roleGroup) {
                    $newMember->roleGroupsIdIndexed[$roleGroup->id] = $roleGroup;
                    $newMember->roleGroupsNameIndexed[$roleGroup->roleName] = $roleGroup;
                    $roleGroup->members[$newMember->id] = $newMember;
                }
            }

            if ($entry->unit !== null) {
                $troop = $this->getTroop($scoutGroup, $entry->unit);
                $newMember->troop = $troop;
                $troop->members[$newMember->id] = $newMember;
            }

            if ($entry->patrol !== null) {
                $patrol = $this->getPatrol($troop, $entry->patrol);
                $newMember->patrol = $patrol;
                $patrol->members[$newMember->id] = $newMember;
            }

            $scoutGroup->members[$newMember->id] = $newMember;
        }
        
        return $scoutGroup;
    }

    /** @return RoleGroup[] */
    private function getRoleGroups(ScoutGroup $group, \Org\Scoutnet\ValueAndRaw $roleGroupsInfo) {
        $roleGroupNames = explode(', ', $roleGroupsInfo->value);
        $roleGroups = [];
        foreach (explode(',', $roleGroupsInfo->rawValue) as $index => $roleGroupId) {
            if (!isset($group->roleGroupsIdIndexed[$roleGroupId])) {
                $roleGroups[] = $group->roleGroupsIdIndexed[$roleGroupId];
            } else {
                $roleGroupName = $roleGroupNames[$index];
                $roleGroup = new RoleGroup($roleGroupId, $roleGroupName, $group);
                $group->roleGroupsIdIndexed[$roleGroupId] = $roleGroup;
                $group->roleGroupsNameIndexed[$roleGroupName] = $roleGroup;
                $roleGroups[] = $roleGroup;
            }
        }
        return $roleGroups;
    }

    /** @return Troop */
    private function getTroop(ScoutGroup $group, \Org\Scoutnet\ValueAndRaw $troopInfo) {
        if (isset($group->troopsIdIndexed[$troopInfo->rawValue])) {
            return $group->troopsIdIndexed[$troopInfo->rawValue];
        } else {
            $troop = new Troop($troopInfo->rawValue, $troopInfo->value, $group);
            $group->troopsIdIndexed[$troopInfo->rawValue] = $troop;
            $group->troopsNameIndexed[$troopInfo->value] = $troop;
            return $troop;
        }
    }

    /** @return Patrol */
    private function getPatrol(Troop $troop, \Org\Scoutnet\ValueAndRaw $patrolInfo) {
        if (isset($troop->patrolsIdIndexed[$patrolInfo->rawValue])) {
            return $troop->patrolsIdIndexed[$patrolInfo->rawValue];
        } else {
            $patrol = new Patrol($patrolInfo->rawValue, $patrolInfo->value);
            $troop->patrolsIdIndexed[$patrolInfo->rawValue] = $patrol;
            $troop->patrolsNameIndexed[$patrolInfo->value] = $patrol;
            $patrol->troop = $troop;
            return $patrol;
        }
    }

    /** @return PersonInfo */
    private function getPersonInfo(\Org\Scoutnet\MemberEntry $entry) {
        $personInfo = new PersonInfo($entry->first_name,
            $entry->last_name,
            $entry->ssno,
            $entry->sex,
            $entry->date_of_birth);
        return $personInfo;
    }

    /** @return ContactInfo */
    private function getContactInfo(\Org\Scoutnet\MemberEntry $entry) {
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
    private function getAccommodation(\Org\Scoutnet\MemberEntry $entry) {
        return new Location($entry->address_1, $entry->postcode, $entry->town);
    }

    /** @return Contact[] */
    private function getContacts(\Org\Scoutnet\MemberEntry $entry) {
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