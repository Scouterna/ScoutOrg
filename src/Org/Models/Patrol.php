<?php
/**
 * Contains Patrol class
 * @author Alexander Krantz
 */
namespace Org\Models;

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
}