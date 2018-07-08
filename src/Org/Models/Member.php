<?php
/**
 * Contains Member class
 * @author Alexander Krantz
 */
namespace Org\Models;

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

    /** @var TroopMemberLink[] */
    private $troopsIdIndexed;

    /** @var TroopMemberLink[] */
    private $troopsNameIndexed;

    /** @var PatrolMemberLink[] */
    private $patrolsIdIndexed;

    /** @var RoleGroup[] */
    private $roleGroupsIdIndexed;

    /** @var RoleGroup[] */
    private $roleGroupsNameIndexed;

    /**
     * Creates a new member.
     * @internal
     * @param int $id
     */
    public function __construct(int $id) {
        $this->id = $id;
        $this->troopsIdIndexed = new \ArrayObject();
        $this->troopsNameIndexed = new \ArrayObject();
        $this->patrolsIdIndexed = new \ArrayObject();
        $this->roleGroupsIdIndexed = new \ArrayObject();
        $this->roleGroupsNameIndexed = new \ArrayObject();
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
     * @return TroopMemberLink[]
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
     * @return PatrolMemberLink[]
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