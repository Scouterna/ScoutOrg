<?php
namespace Org\Models;

/**
 * A troop that is in the scout group
 */
class Troop {
    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /** @var ScoutGroup */
    private $scoutGroup;

    /** @var Member[] */
    private $members;

    /** @var Patrol[] */
    private $patrolsIdIndexed;

    /** @var Patrol[] */
    private $patrolsNameIndexed;
    
    /**
     * Creates a new troop with the specified info.
     * @param int $id The troop scoutnet id.
     * @param string $name The troop name.
     * @param Member[] $members The list of members.
     * @param Patrol[] $patrols The list of patrols.
     */
    public function __construct(int $id, string $name, ScoutGroup $scoutGroup) {
        $this->id = $id;
        $this->name = $name;
        $this->scoutGroup = $scoutGroup;
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
     * Gets the list of member of the troop.
     * @return Member[]
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
     * Gets the troop leader (Avdelningsledare) or NULL if not found
     * @return Member|null
     */
    public function getTroopLeader() {
        foreach ($this->getMembers() as $member) {
            if ($member->getTroopRole() == 'Avdelningsledare') {
                return $member;
            }
        }
        return null;
    }

    /**
     * Gets all leaders in the troop. All results will have a troop role.
     * @return Member[]
     */
    public function getLeaders() {
        $leaders = [];
        foreach ($this->members as $member) {
            if ($member->getTroopRole() !== null) {
                $leaders[] = $member;
            }
        }
        return $leaders;
    }

    /**
     * Gest all scouts in the troop. No results will have a troop role.
     * @return Member[]
     */
    public function getScouts() {
        $scouts = [];
        foreach ($this->members as $member) {
            if ($member->getTroopRole() === null) {
                $scouts[] = $member;
            }
        }
        return $scouts;
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