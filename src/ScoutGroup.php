<?php
namespace Org;
require_once(__DIR__.'/Lib.php');

class ScoutGroup {
    private $id;
    private $members;
    private $troops;
    private $roleGroups;

    /**
     * Creates a new ScoutGroup with the specified structure.
     * @param int $id The scout group id.
     * @param Member[] $memberList The list of members in the scout group.
     * @param Troop[] $troopList The list of troops in the scout group.
     * @param RoleGroup[] $roleList The list of roles and their groups.
     */
    public function __construct(int $id, array $members, array $troops, array $roleGroups) {
        $this->id = $id;
        $this->members = $members;
        $this->troops = $troops;
        $this->roleGroups = $roleGroups;
    }

    /**
     * Gets the scout group id.
     * @return int A scoutnet group id.
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Gets the list of members of the group.
     * @return Member[] A list of members.
     */
    public function getMembers() {
        return $this->members;
    }

    /**
     * Gets the list of troops of the group.
     * @return Troop[] A list of troops.
     */
    public function getTroops() {
        return $this->troops;
    }

    /**
     * Gets the list of role groups of the group.
     * @return RoleGroup[] A list of role groups.
     */
    public function getRoleGroups() {
        return $this->roleGroups;
    }
}