<?php
/**
 * Contains RoleGroupConfig class
 * @author Alexander Krantz
 */
namespace Org\Composite;

/**
 * Role group configuration for building a
 * role group in the scout group.
 */
class RoleGroupConfig {
    /** @var int The role group id. */
    private $id;

    /** @var string The role group name. */
    private $name;

    /** @var int[] The role group members. */
    private $members;

    /**
     * Creates a new role group config.
     * @param int $id
     * @param string $name
     * @param int[] $members
     */
    public function __construct(int $id, string $name, array $members) {
        $this->id = $id;
        $this->name = $name;
        $this->members = $members;
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
     * @return int[]
     */ 
    public function getMembers() {
        return $this->members;
    }
}