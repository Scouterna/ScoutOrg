<?php
namespace Org\Models;

/**
 * The whole scout group that is part of the scout organisation
 */
class ScoutGroup {
    /** @var int */
    private $id;

    /** @var Member[] */
    private $members;

    /** @var Troop[] */
    private $troopsIdIndexed;

    /** @var Troop[] */
    private $troopsNameIndexed;

    /** @var RoleGroup[] */
    private $roleGroupsIdIndexed;

    /** @var RoleGroup[] */
    private $roleGroupsNameIndexed;

    /**
     * Creates a new ScoutGroup with the specified id.
     * @param int $id The scout group id.
     * @param Member[] $memberList The list of members in the scout group.
     * @param Troop[] $troopList The list of troops in the scout group.
     * @param RoleGroup[] $roleList The list of roles and their groups.
     */
    public function __construct(int $id) {
        $this->id = $id;
        $this->members = new \ArrayObject();
        $this->troopsIdIndexed = new \ArrayObject();
        $this->troopsNameIndexed = new \ArrayObject();
        $this->roleGroupsIdIndexed = new \ArrayObject();
        $this->roleGroupsNameIndexed = new \ArrayObject();
    }

    /**
     * Gets the scout group scoutnet id.
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Gets the list of members of the group indexed by scoutnet id.
     * @return Member[]
     */
    public function getMembers() {
        return $this->members->getArrayCopy();
    }

    /**
     * Gets the list of troops of the group.
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
     * Gets the list of role groups of the group.
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

    /** @ignore */
    public function __set(string $name, $value) {
        $callstack = debug_backtrace(0, 2);
        $class = $callstack[1]['class'];
        if ($class === 'Org\\Models\\ScoutGroupFactory') {
            $this->$name = $value;
        }
    }

    /** @ignore */
    public function __get(string $name) {
        if (isset($this->$name)) {
            $callstack = \debug_backtrace(0, 2);
            $class = $callstack[1]['class'];
            if ($class === 'Org\\Models\\ScoutGroupFactory') {
                return $this->$name;
            }
        }
    }
}