<?php
namespace Org\Models;

/**
 * Contains info about a scout member
 */
class Member {
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

    /** @var string */
    private $troopRole;

    /** @var Troop */
    private $troop;

    /** @var Patrol */
    private $patrol;

    /** @var RoleGroup[] */
    private $roleGroupsIdIndexed;

    /** @var RoleGroup[] */
    private $roleGroupsNameIndexed;

    public function __construct(int $id) {
        $this->id = $id;
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
     * Gets the member's role in their troop.
     * @return string
     */
    public function getTroopRole() {
        return $this->troopRole;
    }

    /**
     * Gets the member's troop.
     * @return Troop
     */
    public function getTroop() {
        return $this->troop;
    }

    /**
     * Gets the member's patrol
     * @return Patrol
     */
    public function getPatrol() {
        return $this->patrol;
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

    public function __set(string $name, $value) {
        $callstack = debug_backtrace(0, 2);
        $class = $callstack[1]['class'];
        if ($class === 'Org\\Models\\ScoutGroupFactory') {
            $this->$name = $value;
        }
    }

    public function __get(string $name) {
        if (isset($this->$name)) {
            $callstack = debug_backtrace(0, 2);
            $class = $callstack[1]['class'];
            if ($class === 'Org\\Models\\ScoutGroupFactory') {
                return $this->$name;
            }
        }
    }
}