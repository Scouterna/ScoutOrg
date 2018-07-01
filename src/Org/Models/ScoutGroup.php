<?php
/**
 * Contains ScoutGroup class
 * @author Alexander Krantz
 */
namespace Org\Models;

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
}