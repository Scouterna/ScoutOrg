<?php
/**
 * Contains BranchConfig class
 * @author Alexander Krantz
 */
namespace Org\Scoutnet;

/**
 * Configuration for a branch with troops.
 */
class BranchConfig {
    /** @var int The branch id. */
    private $branchId;

    /** @var string The branch name. */
    private $branchName;

    /** @var int[] The list of id:s of the troops that belong to the branch. */
    private $troopIds;

    /**
     * Creates a new branch configuration.
     * @see ScoutnetGroupFactory
     * @param int $id The branch id.
     * @param string $name The branch name.
     * @param int[] $troopIds The list of troop id:s.
     */
    public function __construct(int $id, string $name, array $troopIds) {
        $this->branchId = $id;
        $this->branchName = $name;
        $this->troopIds = $troopIds;
    }

    /**
     * Gets the branch id.
     * @return int
     */ 
    public function getBranchId() {
        return $this->branchId;
    }

    /**
     * Gets the branch name.
     * @return string
     */ 
    public function getBranchName() {
        return $this->branchName;
    }

    /**
     * Gets the list of id:s of the troops.
     * @return int[]
     */ 
    public function getTroopIds() {
        return $this->troopIds;
    }
}