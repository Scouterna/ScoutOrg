<?php
namespace Org;
require_once(__DIR__.'/Lib.php');

class RoleGroup {
    private $id;
    private $roleName;
    private $members;

    /**
     * Creates a new RoleGroup witht the specified role and list of members.
     * @param string $roleName The role name of the role.
     * @param Member[] $members The members witht the role.
     */
    public function __construct(int $id, string $roleName, array $members) {
        $this->id = $id;
        $this->roleName = $roleName;
        $this->members = $members;
    }

    /**
     * Gets the role id.
     * @return int A role id.
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Gets the role name.
     * @return string A role name.
     */
    public function getRoleName() {
        return $this->roleName;
    }

    /**
     * Gets the members that have the role.
     * @return Member[] A list of members.
     */
    public function getMembers() {
        return $this->members;
    }
}