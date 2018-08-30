<?php
/**
 * Contains MemberConfig class
 * @author Alexander Krantz
 */
namespace Org\Composite;

/**
 * Member configuration with an attached role.
 */
class MemberConfig {
    /** @var int The member id. */
    private $id;

    /** @var string The member role. */
    private $role;

    /**
     * Creates a new member config to be used in patrol and troop configs.
     * @param int $id
     * @param string $role
     */
    public function __construct(int $id, string $role) {
        $this->member = $id;
        $this->role = $role;
    }

    /**
     * Gets the member id.
     * @return int
     */ 
    public function getId() {
        return $this->id;
    }

    /**
     * Gets the member role.
     * @return string
     */ 
    public function getRole() {
        return $this->role;
    }
}