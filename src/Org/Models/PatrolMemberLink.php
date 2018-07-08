<?php
/**
 * Contains PatrolMemberLink class
 * @author Alexander Krantz
 */
namespace Org\Models;

/**
 * Link between a patrol and a member with link specific data.
 */
class PatrolMemberLink {
    /** @var Patrol */
    private $patrol;
    
    /** @var Member */
    private $member;
    
    /** @var string */
    private $role;

    /**
     * Creates a new patrol member link.
     * @param Patrol $patrol
     * @param Member $member
     * @param string $role
     */
    public function __construct(Patrol $patrol, Member $member, string $role) {
        $this->patrol = $patrol;
        $this->member = $member;
        $this->role = $role;
    }

    /**
     * Gets the patrol.
     * @return Patrol
     */ 
    public function getPatrol() {
        return $this->patrol;
    }

    /**
     * Gets the member.
     * @return Member
     */ 
    public function getMember() {
        return $this->member;
    }

    /**
     * Gets the role.
     * @return string
     */ 
    public function getRole() {
        return $this->role;
    }
}