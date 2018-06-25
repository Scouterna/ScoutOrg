<?php
namespace Org\Models;

/**
 * A group for a special role in the scout group
 */
class RoleGroup {
    /** @var int */
    private $id;

    /** @var string */
    private $roleName;

    /** @var ScoutGroup */
    private $scoutGroup;

    /** @var Member[] */
    private $members;

    /**
     * Creates a new RoleGroup witht the specified role and list of members.
     * @param int $id The patrol id.
     * @param string $roleName The role name of the role.
     */
    public function __construct(int $id, string $name, ScoutGroup $scoutGroup) {
        $this->id = $id;
        $this->roleName = $name;
        $this->scoutGroup = $scoutGroup;
        $this->members = new \ArrayObject();
    }

    /**
     * Gets the role id.
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Gets the role name.
     * @return string
     */
    public function getRoleName() {
        return $this->roleName;
    }

    /**
     * Gets the members that have the role.
     * @return Member[]
     */
    public function getMembers() {
        return $this->members->getArrayCopy();
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
