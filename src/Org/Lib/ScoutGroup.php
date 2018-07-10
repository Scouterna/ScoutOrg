<?php
/**
 * Contains ScoutGroup class
 * @author Alexander Krantz
 */
namespace Org\Lib;

/**
 * The whole scout group that is part of the scout organisation.
 */
class ScoutGroup {
    use InternalTrait;

    /** @var int */
    private $id;

    /** @var Member[] */
    private $members;

    /** @var Troop[] */
    private $troopsIdIndexed;

    /** @var Troop[] */
    private $troopsNameIndexed;

    /** @var Branch[] */
    private $branchesIdIndexed;

    /** @var Branch[] */
    private $branchesNameIndexed;

    /** @var RoleGroup[] */
    private $roleGroupsIdIndexed;

    /** @var RoleGroup[] */
    private $roleGroupsNameIndexed;

    /** @var CustomList[] */
    private $customListsIdIndexed;

    /** @var CustomList[] */
    private $customListsTitleIndexed;

    /**
     * Creates a new ScoutGroup with the specified id.
     * @internal
     * @param int $id The scout group id.
     */
    public function __construct(int $id) {
        $this->id = $id;
        $this->members = new \ArrayObject();
        $this->troopsIdIndexed = new \ArrayObject();
        $this->troopsNameIndexed = new \ArrayObject();
        $this->branchesIdIndexed = new \ArrayObject();
        $this->branchesNameIndexed = new \ArrayObject();
        $this->roleGroupsIdIndexed = new \ArrayObject();
        $this->roleGroupsNameIndexed = new \ArrayObject();
        $this->customListsIdIndexed = new \ArrayObject();
        $this->customListsTitleIndexed = new \ArrayObject();
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
     * Adds a member.
     * @param Member $member
     * @return void
     */
    private function addMember(Member $member) {
        $this->members[$member->getId()] = $member;
    }

    /**
     * Gets the list of branches.
     * @param bool $idIndexed Wether to get list indexed by id or name.
     * @return Branch[]
     */
    public function getBranches(bool $idIndexed = false) {
        if ($idIndexed) {
            return $this->branchesIdIndexed->getArrayCopy();
        } else {
            return $this->branchesNameIndexed->getArrayCopy();
        }
    }

    /**
     * Adds a branch.
     * @param Branch $branch
     * @return void
     */
    private function addBranch(Branch $branch) {
        $this->branchesIdIndexed[$branch->getId()] = $branch;
        $this->branchesNameIndexed[$branch->getName()] = $branch;
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
     * Adds a troop.
     * @param Troop $troop
     * @return void
     */
    private function addTroop(Troop $troop) {
        $this->troopsIdIndexed[$troop->getId()] = $troop;
        $this->troopsNameIndexed[$troop->getName()] = $troop;
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

    /** 
     * Adds a role group.
     * @param RoleGroup $roleGroup
     * @return void
     */
    private function addRoleGroup(RoleGroup $roleGroup) {
        $this->roleGroupsIdIndexed[$roleGroup->getId()] = $roleGroup;
        $this->roleGroupsNameIndexed[$roleGroup->getRoleName()] = $roleGroup;
    }

    /**
     * Gets the list of custom lists of the group.
     * @param bool $idIndexed Wether to get the list indexed by id or title.
     * @return CustomList[]
     */
    public function getCustomLists(bool $idIndexed = false) {
        if ($idIndexed) {
            return $this->customListsIdIndexed->getArrayCopy();
        } else {
            return $this->customListsTitleIndexed->getArrayCopy();
        }
    }

    /**
     * Adds a custom list.
     * @param CustomList $customList
     * @return void
     */
    private function addCustomList(CustomList $customList) {
        $this->customListsIdIndexed[$customList->getId()] = $customList;
        $this->customListsTitleIndexed[$customList->getTitle()] = $customList;
    }
}