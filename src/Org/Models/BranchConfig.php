<?php
/**
 * Contains BranchConfig class
 * @author Alexander Krantz
 */
namespace Org\Models;

/**
 * Configuration for a branch with troops.
 */
class BranchConfig {
    /** @var int */
    private $branchId;

    /** @var string */
    private $branchName;

    /** @var int[] */
    private $troopIds;

    /**
     * Creates a new branch configuration.
     * @see ScoutnetGroupFactory
     * @param int $id The branch id.
     * @param string $name The branch name.
     * @param int[] $troops The list of troop id:s.
     */
    public function __construct(int $id, string $name, array $troops) {
        $this->branchId = $id;
        $this->branchName = $name;
        $this->troops = $troops;
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
     * Gets the list of troops in the branch.
     * @return int[]
     */ 
    public function getTroopIds() {
        return $this->troopIds;
    }
}