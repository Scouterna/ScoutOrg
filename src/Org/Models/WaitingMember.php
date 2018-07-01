<?php
/**
 * Contains WaitingMember class
 * @author Alexander Krantz
 */
namespace Org\Models;

/**
 * A member that's waiting for group placement.
 */
class WaitingMember {
    use InternalTrait;

    /** @var int */
    private $id;

    /** @var PersonInfo */
    private $personInfo;

    /** @var ContactInfo */
    private $contactInfo;

    /** @var Location */
    private $accommodation;

    /** @var Contact[] */
    private $contacts;

    /** @var string */
    private $waitingStartdate;

    /** @var string */
    private $note;

    /**
     * Creates a new waiting member
     * @internal
     * @param int $id
     */
    public function __construct(int $id) {
        $this->id = $id;
    }

    /**
     * Gets the scoutnet id.
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Gets the person info
     * @return PersonInfo
     */
    public function getPersonInfo() {
        return $this->personInfo;
    }

    /**
     * Gets the contact info.
     * @return ContactInfo
     */
    public function getContactInfo() {
        return $this->contactInfo;
    }

    /**
     * Gets the member's accommodation.
     * @return Location
     */
    public function getAccommodation() {
        return $this->accommodation;
    }

    /**
     * Gets a list of contacts.
     * @return Contact[]
     */
    public function getContacts() {
        return $this->contacts;
    }

    /**
     * Gets the date the member started waiting.
     * @return string
     */ 
    public function getWaitingStartdate() {
        return $this->waitingStartdate;
    }

    /**
     * Gets a note attached to the waiting member.
     * @return string
     */ 
    public function getNote() {
        return $this->note;
    }
}