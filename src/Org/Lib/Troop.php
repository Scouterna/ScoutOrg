<?php
/**
 * Contains Troop class
 * @author Alexander Krantz
 */
namespace Org\Lib;

/**
 * A troop that is in the scout group.
 */
class Troop {
    use InternalTrait;
    
    /** @var int The troop id. */
    private $id;

    /** @var string The troop name. */
    private $name;

    /** @var Branch|null The branch that the troop belongs to. */
    private $branch;

    /** @var Member[] The list of members in the troop indexed by their id. */
    private $members;

    /** @var string[] The member troop roles indexed by the member id. */
    private $memberRoles;

    /** @var Patrol[] The patrols that belong to the troop indexed by their id. */
    private $patrolsIdIndexed;

    /** @var Patrol[] The patrol that belong to the troop indexed by their name. */
    private $patrolsNameIndexed;
    
    /**
     * Creates a new troop with the specified info.
     * @internal
     * @param int $id The troop scoutnet id.
     * @param string $name The troop name.
     */
    public function __construct(int $id, string $name) {
        $this->id = $id;
        $this->name = $name;
        $this->members = array();
        $this->memberRoles = array();
        $this->patrolsIdIndexed = array();
        $this->patrolsNameIndexed = array();
    }
    
    /**
     * Gets the id of the troop.
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Gets the name of the troop.
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /** 
     * Gets the branch that the troop is in.
     * @return Branch|null
     */
    public function getBranch() {
        return $this->branch;
    }

    /**
     * Sets the troop's branch.
     * @param Branch $branch
     * @return void
     */
    private function setBranch(Branch $branch) {
        $this->branch = $branch;
    }

    /**
     * Gets the list of member of the troop.
     * @return Member[]
     */
    public function getMembers() {
        return $this->members;
    }

    /**
     * Gets all roles of members indexed by member id.
     * @return string[]
     */
    public function getMemberRoles() {
        return $this->memberRoles;
    }

    /**
     * Adds a member and its troop role.
     * @param Member $member
     * @param string $role
     * @return void
     */
    private function addMember(Member $member, string $role = '') {
        $this->members[$member->getId()] = $member;
        if ($role !== '') {
            $this->memberRoles[$member->getId()] = $role;
        }
    }

    /**
     * Gets the list of patrols of the troop.
     * @param bool $idIndexed Wether to get list indexed by id or name.
     * @return Patrol[]
     */
    public function getPatrols(bool $idIndexed = false) {
        if ($idIndexed) {
            return $this->patrolsIdIndexed;
        } else {
            return $this->patrolsNameIndexed;
        }
    }

    /**
     * Adds a patrol.
     * @param Patrol $patrol
     * @return void
     */
    private function addPatrol(Patrol $patrol) {
        $this->patrolsIdIndexed[$patrol->getId()] = $patrol;
        $this->patrolsNameIndexed[$patrol->getId()] = $patrol;
    }
}