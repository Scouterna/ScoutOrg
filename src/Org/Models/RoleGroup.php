<?php
/**
 * Contains RoleGroup class
 * @author Alexander Krantz
 */
namespace Org\Models;

/**
 * A group for a special role in the scout group
 */
class RoleGroup {
    use InternalTrait;

    /** @var int */
    private $id;

    /** @var string */
    private $roleName;

    /** @var Member[] */
    private $members;

    /**
     * Creates a new RoleGroup with the specified role.
     * @internal
     * @param int $id
     * @param string $roleName
     */
    public function __construct(int $id, string $roleName) {
        $this->id = $id;
        $this->roleName = $roleName;
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
}
