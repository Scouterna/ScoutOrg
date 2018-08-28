<?php
/**
 * Contains WaitingMember class
 * @author Alexander Krantz
 */
namespace Org\Lib;

/**
 * A member that's waiting for group placement.
 */
class WaitingMember {
    use InternalTrait;

    /** @var int The member id. */
    private $id;

    /** @var PersonInfo The member's personal information. */
    private $personInfo;

    /** @var ContactInfo Contact info of the member. */
    private $contactInfo;

    /** @var Location The location where the member lives. */
    private $accommodation;

    /** @var Contact[] All contacts of the member. */
    private $contacts;

    /** @var string The date the member started waiting. */
    private $waitingStartdate;

    /** @var string The note attached to the waiting member. */
    private $note;

    /** @var bool Wether the member's parent or close relative wants to be a leader. */
    private $leaderInterest;

    /**
     * Creates a new waiting member
     * @internal
     * @param int $id
     * @param PersonInfo $personInfo
     * @param ContactInfo $contactInfo
     * @param Location $accommodation
     * @param Contact[] $contacts
     * @param string $waitingStartDate
     * @param string $note
     * @param bool $leaderInterest
     */
    public function __construct(int $id,
                                PersonInfo $personInfo,
                                ContactInfo $contactInfo,
                                Location $accommodation,
                                array $contacts,
                                string $waitingStartDate,
                                string $note,
                                bool $leaderInterest) {
        $this->id = $id;
        $this->personInfo = $personInfo;
        $this->contactInfo = $contactInfo;
        $this->accommodation = $accommodation;
        $this->contacts = $contacts;
        $this->waitingStartdate = $waitingStartDate;
        $this->note = $note;
        $this->leaderInterest = $leaderInterest;
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
     * Gets the note attached to the waiting member.
     * @return string
     */ 
    public function getNote() {
        return $this->note;
    }

    /**
     * Gets wether the member's parent or a close relative
     * is interested in becoming a leader.
     * @return bool
     */
    public function hasLeaderInterest() {
        return $this->leaderInterest;
    }
}