<?php
/**
 * Contains TroopConfig class
 * @author Alexander Krantz
 */
namespace Org\Composite;

/**
 * Troop configuration for building a troop in the scout group.
 */
class TroopConfig {
    /** @var int The troop id. */
    private $id;

    /** @var string The troop name. */
    private $name;

    /** @var MemberConfig[] The troop members. */
    private $members;

    /** @var PatrolConfig[] The troop patrols. */
    private $patrols;

    /**
     * Creates a new troop config.
     * @param int $id
     * @param string $name
     * @param MemberConfig[] $members
     * @param PatrolConfig[] $patrols
     */
    public function __construct(int $id, string $name, array $members, array $patrols) {
        $this->id = $id;
        $this->name = $name;
        $this->members = $members;
        $this->patrols = $patrols;
    }

    /**
     * Gets the role group id.
     * @return int
     */ 
    public function getId() {
        return $this->id;
    }

    /**
     * Gets the role group name.
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

    /**
     * Gets the list of patrol ids.
     * @return PatrolConfig[]
     */ 
    public function getPatrols() {
        return $this->patrols;
    }
}