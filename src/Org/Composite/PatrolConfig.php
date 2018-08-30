<?php
/**
 * Contains PatrolConfig class
 * @author Alexander Krantz
 */
namespace Org\Composite;

/**
 * Patrol configuration for building a patrol in a troop.
 */
class PatrolConfig {
    /** @var int The patrol id. */
    private $id;

    /** @var string The patrol name. */
    private $name;

    /** @var MemberConfig[] The patrol members. */
    private $members;

    /**
     * Creates a new patrol config.
     * @param int $id
     * @param string $name
     * @param MemberConfig[] $members
     */
    public function __construct(int $id, string $name, array $members) {
        $this->id = $id;
        $this->name = $name;
        $this->members = $members;
    }

    /**
     * Gets the patrol id.
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
     * Gets the list of member ids.
     * @return MemberConfig[]
     */ 
    public function getMembers() {
        return $this->members;
    }
}