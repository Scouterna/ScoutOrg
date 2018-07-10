<?php
/**
 * Contains Member class
 * @author Alexander Krantz
 */
namespace Org\Lib;

/**
 * A scout member with various personal and group info.
 */
class Member {
    use InternalTrait;

    /** @var int */
    private $id;

    /** @var PersonInfo */
    private $personInfo;

    /** @var ContactInfo */
    private $contactInfo;

    /** @var Location */
    private $accommodation;

    /** @var Contact[] */
    private $contacts;

    /** @var string */
    private $startdate;

    /** @var Troop[] */
    private $troopsIdIndexed;

    /** @var Troop[] */
    private $troopsNameIndexed;

    /** @var Patrol[] */
    private $patrolsIdIndexed;

    /** @var RoleGroup[] */
    private $roleGroupsIdIndexed;

    /** @var RoleGroup[] */
    private $roleGroupsNameIndexed;

    /**
     * Creates a new member.
     * @internal
     * @param int $id
     * @param PersonInfo $personInfo
     * @param ContactInfo $contactInfo
     * @param Location $accommodation
     * @param Contact[] $contacts
     * @param string $startDate
     */
    public function __construct(int $id,
                                PersonInfo $personInfo,
                                ContactInfo $contactInfo,
                                Location $accommodation,
                                array $contacts,
                                string $startDate) {
        $this->id = $id;
        $this->personInfo = $personInfo;
        $this->contactInfo = $contactInfo;
        $this->accommodation = $accommodation;
        $this->contacts = $contacts;
        $this->startdate = $startDate;
        $this->troopsIdIndexed = new \ArrayObject();
        $this->troopsNameIndexed = new \ArrayObject();
        $this->patrolsIdIndexed = new \ArrayObject();
        $this->roleGroupsIdIndexed = new \ArrayObject();
        $this->roleGroupsNameIndexed = new \ArrayObject();
    }

    /**
     * Adds a troop to the member.
     * @param Troop $troop
     * @return bool
     */
    private function addTroop(Troop $troop) {
        $this->troopsIdIndexed[$troop->getId()] = $troop;
        $this->troopsNameIndexed[$troop->getName()] = $troop;
    }

    /**
     * Adds a patrol to the member.
     * @param Patrol $patrol
     * @return bool
     */
    private function addPatrol(Patrol $patrol) {
        $this->patrolsIdIndexed[$patrol->getId()] = $patrol;
    }

    /**
     * Adds a role group to the member.
     * @param RoleGroup $roleGroup
     * @return bool
     */
    private function addRoleGroup(RoleGroup $roleGroup) {
        $this->roleGroupsIdIndexed[$roleGroup->getId()] = $roleGroup;
        $this->roleGroupsNameIndexed[$roleGroup->getRoleName()] = $roleGroup;
    }

    /**
     * Gets the scoutnet id.
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Gets the person info
     * @return PersonInfo
     */
    public function getPersonInfo() {
        return $this->personInfo;
    }

    /**
     * Gets the contact info.
     * @return ContactInfo
     */
    public function getContactInfo() {
        return $this->contactInfo;
    }

    /**
     * Gets the member's accommodation.
     * @return Location
     */
    public function getAccommodation() {
        return $this->accommodation;
    }

    /**
     * Gets a list of contacts.
     * @return Contact[]
     */
    public function getContacts() {
        return $this->contacts;
    }

    /**
     * Gets the date of accepted scout application.
     * @return string
     */
    public function getStartdate() {
        return $this->startdate;
    }

    /**
     * Gets the member's troops.
     * @param bool $idIndexed Wether to get list indexed by id or name.
     * @return Troop[]
     */
    public function getTroops(bool $idIndexed = false) {
        if ($idIndexed) {
            return $this->troopsIdIndexed->getArrayCopy();
        } else {
            return $this->troopsNameIndexed->getArrayCopy();
        }
    }

    /**
     * Gets the member's patrols indexed by their scoutnet id.
     * @return Patrol[]
     */
    public function getPatrols() {
        return $this->patrolsIdIndexed;
    }

    /**
     * Gets the member's scout group roles.
     * @param bool $idIndexed Wether to get list indexed by id or name.
     * @return RoleGroup[]
     */
    public function getRoleGroups(bool $idIndexed = false) {
        if ($idIndexed) {
            return $this->roleGroupsIdIndexed->getArrayCopy();
        } else {
            return $this->roleGroupsNameIndexed->getArrayCopy();
        }
    }
}