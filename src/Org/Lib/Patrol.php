<?php
/**
 * Contains Patrol class
 * @author Alexander Krantz
 */
namespace Org\Lib;

/**
 * A scout patrol that is in a troop.
 */
class Patrol {
    use InternalTrait;

    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /** @var Troop */
    private $troop;

    /** @var Member[] */
    private $members;

    /** @var string[] */
    private $memberRoles;
    
    /**
     * Creates a new patrol.
     * @internal
     * @param int $id
     * @param string $name
     */
    public function __construct(int $id, string $name) {
        $this->id = $id;
        $this->name = $name;
        $this->members = new \ArrayObject();
        $this->memberRoles = new \ArrayObject();
    }

    /**
     * Gets the scoutnet id.
     * @return int
     */ 
    public function getId() {
        return $this->id;
    }

    /**
     * Gets the patrol name.
     * @return string
     */ 
    public function getName() {
        return $this->name;
    }

    /**
     * Gets the troop of the patrol.
     * @return Troop
     */ 
    public function getTroop() {
        return $this->troop;
    }

    /**
     * Sets the troop.
     * @param Troop $troop
     * @return void
     */
    private function setTroop(Troop $troop) {
        $this->troop = $troop;
    }

    /**
     * Gets the patrol members.
     * @return PatrolMemberLink[]
     */ 
    public function getMembers() {
        return $this->members->getArrayCopy();
    }

    /**
     * Gets all roles of members indexed by member id.
     * @return string[]
     */
    public function getMemberRoles() {
        return $this->memberRoles->getArrayCopy();
    }
    
    /**
     * Adds a member and its patrol role.
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
}