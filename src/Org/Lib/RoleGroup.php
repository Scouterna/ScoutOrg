<?php
/**
 * Contains RoleGroup class
 * @author Alexander Krantz
 */
namespace Org\Lib;

/**
 * A group for a special role in the scout group
 */
class RoleGroup {
    use InternalTrait;

    /** @var int The role id. */
    private $id;

    /** @var string The role name. */
    private $roleName;

    /** @var Member[] The list of members with the role indexed by their id. */
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
        $this->members = array();
    }

    /**
     * Adds a member.
     * @param Member $member
     * @return void
     */
    private function addMember(Member $member) {
        $this->members[$member->getId()] = $member;
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
        return $this->members;
    }
}
