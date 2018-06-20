<?php
namespace Org\Models;

/**
 * A scout patrol that is in a troop
 */
class Patrol {
    /** @var int */
    private $id;

    /** @var string */
    private $name;

    /** @var Troop */
    private $troop;

    /** @var Member[] */
    private $members;
    
    public function __construct(int $id, string $name) {
        $this->id = $id;
        $this->name = $name;
        $this->members = new \ArrayObject();
    }

    /**
     * Gets the scoutnet id.
     * @return int
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the patrol name.
     * @return string
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the troop of the patrol.
     * @return Troop
     */ 
    public function getTroop()
    {
        return $this->troop;
    }

    /**
     * Gets the patrol members.
     * @return Member[]
     */ 
    public function getMembers()
    {
        return $this->members->getArrayCopy();
    }

    public function __set(string $name, $value) {
        $callstack = debug_backtrace(0, 2);
        $class = $callstack[1]['class'];
        if ($class === 'Org\\Models\\ScoutGroupFactory') {
            $this->$name = $value;
        }
    }

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