<?php
/**
 * Contains BranchConfig class
 * @author Alexander Krantz
 */
namespace Org\Composite;

/**
 * Branch configuration for building a branch in the scout group.
 */
class BranchConfig {
    /** @var int The branch id. */
    private $id;

    /** @var string The branch name. */
    private $name;

    /** @var int[] The troop ids. */
    private $troops;

    /**
     * Creates a new branch config.
     * @param int $id
     * @param string $name
     * @param int[] $troops
     */
    public function __construct(int $id, string $name, array $troops) {
        $this->id = $id;
        $this->name = $name;
        $this->troops = $troops;
    }

    /**
     * Gets the branch id.
     * @return int
     */ 
    public function getId() {
        return $this->id;
    }

    /**
     * Gets the branch name.
     * @return string
     */ 
    public function getName() {
        return $this->name;
    }

    /**
     * Gets the list of troop ids.
     * @return int[]
     */ 
    public function getTroops() {
        return $this->troops;
    }
}