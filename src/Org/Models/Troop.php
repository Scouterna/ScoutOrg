<?php
/**
 * Contains Troop class
 * @author Alexander Krantz
 */
namespace Org\Models;

/**
 * A troop that is in the scout group.
 */
class Troop {
    use InternalTrait;
    
    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /** @var Branch */
    private $branch;

    /** @var TroopMemberLink[] */
    private $members;

    /** @var Patrol[] */
    private $patrolsIdIndexed;

    /** @var Patrol[] */
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
        $this->members = new \ArrayObject();
        $this->patrolsIdIndexed = new \ArrayObject();
        $this->patrolsNameIndexed = new \ArrayObject();
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
     * @return Branch
     */
    public function getBranch() {
        return $this->branch;
    }

    /**
     * Gets the list of member of the troop.
     * @return TroopMemberLink[]
     */
    public function getMembers() {
        return $this->members->getArrayCopy();
    }

    /**
     * Gets the list of patrols of the troop.
     * @param bool $idIndexed Wether to get list indexed by id or name.
     * @return Patrol[]
     */
    public function getPatrols(bool $idIndexed = false) {
        if ($idIndexed) {
            return $this->patrolsIdIndexed->getArrayCopy();
        } else {
            return $this->patrolsNameIndexed->getArrayCopy();
        }
    }

    /**
     * Gets the troop leader (Avdelningsledare) or <code>null</code> if not found.
     * @return Member|null
     */
    public function getTroopLeader() {
        foreach ($this->members as $memberLink) {
            if ($memberLink->getRole() == 'Avdelningsledare') {
                return $memberLink->getMember();
            }
        }
        return null;
    }

    /**
     * Gets all leaders in the troop. All results will have a troop role.
     * @return TroopMemberLink[]
     */
    public function getLeaders() {
        $leaders = [];
        foreach ($this->members as $memberLink) {
            if ($memberLink->getRole() !== null) {
                $leaders[] = $memberLink;
            }
        }
        return $leaders;
    }

    /**
     * Gets all scouts in the troop.
     * @return Member[]
     */
    public function getScouts() {
        $scouts = [];
        foreach ($this->members as $memberLink) {
            if ($memberLink->getRole() === null) {
                $scouts[] = $memberLink->getMember();
            }
        }
        return $scouts;
    }
}