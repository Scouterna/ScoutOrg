<?php
/**
 * Contains TroopMemberLink class
 * @author Alexander Krantz
 */
namespace Org\Models;

/**
 * A link between a troop and a member.
 * Contains which role the member has in the troop.
 */
class TroopMemberLink {
    /** @var Troop */
    private $troop;

    /** @var Member */
    private $member;

    /** @var string */
    private $role;

    /**
     * Creates a new link.
     * @param Troop $troop
     * @param Member $member
     * @param string $role
     */
    public function __construct(Troop $troop, Member $member, string $role) {
        $this->troop = $troop;
        $this->member = $member;
        $this->role = $role;
    }

    /**
     * Gets the linked troop.
     * @return Troop
     */ 
    public function getTroop() {
        return $this->troop;
    }

    /**
     * Gets the linked member.
     * @return Member
     */ 
    public function getMember() {
        return $this->member;
    }

    /**
     * Gets the troop role of the member.
     * @return string
     */ 
    public function getRole() {
        return $this->role;
    }
}