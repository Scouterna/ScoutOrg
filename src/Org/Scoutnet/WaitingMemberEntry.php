<?php
/**
 * Contains WaitingMemberEntry class
 * @author Alexander Krantz
 */
namespace Org\Scoutnet;
use Org\Lib;

/**
 * Contains fields equivalent to an
 * entry in the scoutnet waiting list.
 */
class WaitingMemberEntry {
    /**
     * @var Value The scoutnet id.
     */
    public $member_no;

    /**
     * @var Value The first name.
     */
    public $first_name;

    /**
     * @var Value The last name.
     */
    public $last_name;

    /**
     * @var Value The person number.
     */
    public $ssno;

    /**
     * @var Value A custom text with notes.
     */
    public $note;

    /**
     * @var Value The date of birth.
     */
    public $date_of_birth;

    /**
     * @var ValueAndRaw A status to show wether the member is active.
     */
    public $status;

    /**
     * @var Value The creation date of the account.
     */
    public $created_at;

    /**
     * @var Value The confirmation date of the membership.
     */
    public $confirmed_at;

    /**
     * @var ValueAndRaw The scout group the person is a member in.
     */
    public $group;

    /** @var Value The date the member started waiting for a place. */
    public $waiting_since;

    /**
     * @var ValueAndRaw The gender of the person.
     */
    public $sex;

    /**
     * @var Value The primary address.
     */
    public $address_1;

    /**
     * @var Value The postcode to one of the addresses.
     * (Probably the primary, who knows)
     */
    public $postcode;

    /**
     * @var Value The town for the postcode.
     */
    public $town;

    /**
     * @var Value The country where the person lives.
     */
    public $country;

    /**
     * @var Value The primary email.
     */
    public $email;

    /**
     * @var Value The name of the person's mother.
     */
    public $contact_mothers_name;

    /**
     * @var Value The email of the person's mother.
     */
    public $contact_email_mum;

    /**
     * @var Value The mobile phone number of the person's mother.
     */
    public $contact_mobile_mum;

    /**
     * @var Value The telephone number of the person's mother.
     */
    public $contact_telephone_mum;

    /**
     * @var Value The name of the person's father.
     */
    public $contact_fathers_name;

    /**
     * @var Value The email of the person's father.
     */
    public $contact_email_dad;

    /**
     * @var Value The mobile phone number of person's father.
     */
    public $contact_mobile_dad;

    /**
     * @var Value The telephone number of the person's father.
     */
    public $contact_telephone_dad;

    /**
     * @var Value Wether the person's parent or relative has a leader interest.
     */
    public $contact_leader_interest;

    /**
     * @var Value Unknown. May be wether instant messaging is enabled.
     */
    public $contact_instant_messaging;

    /**
     * Creates a new waiting member entry from a scoutnet entry.
     * @param object $entry
     */
    public function __construct($entry) {
        foreach ($entry as $dataFieldName => $dataField) {
            if (isset($dataField->raw_value)) {
                $this->{$dataFieldName} = new ValueAndRaw($dataField);
            } else {
                $this->{$dataFieldName} = new Value($dataField);
            }
        }
    }

    /** 
     * Gets a Lib\WaitingMember instance of this object.
     * @return Lib\WaitingMember
     */
    public function getMember() {
        $member = new Lib\WaitingMember((int)$this->member_no->value,
            $this->getPersonInfo(),
            $this->getContactInfo(),
            $this->getAccommodation(),
            $this->getContacts(),
            $this->waiting_since->value,
            $this->note !== null ? $this->note : '',
            $this->contact_leader_interest == "Ja");
        return $member;
    }

    /**
     * Gets the person info of the waiting member as a new instance.
     * @return Lib\PersonInfo
     */
    private function getPersonInfo() {
        $personInfo = new Lib\PersonInfo($this->first_name->value,
            $this->last_name->value,
            $this->ssno->value,
            $this->sex->value,
            $this->date_of_birth->value);
        return $personInfo;
    }

    /**
     * Gets the contact info of the waiting member as a new instance.
     * @return Lib\ContactInfo
     */
    private function getContactInfo() {
        $phoneNumbers = [];
        $emailAddresses = [];
        if ($this->email !== NULL) {
            $emailAddresses[] = $this->email->value;
        }
        return new Lib\ContactInfo($phoneNumbers, $emailAddresses);
    }

    /**
     * Gets the living address and postal info of the waiting
     * member as a new instance.
     * @return Lib\Location
     */
    private function getAccommodation() {
        return new Lib\Location($this->address_1->value, $this->postcode->value, $this->town->value);
    }

    /**
     * Gets the list of contacts of the waiting member
     * as new instances.
     * @return Lib\Contact[]
     */
    private function getContacts() {
        $contacts = [];
        // Create contact 1
        if ($this->contact_mothers_name !== NULL) {
            $phoneNumbers = [
                $this->contact_mobile_mum->value,
            ];
            $emails = [
                $this->contact_email_mum->value,
            ];
            $contactInfo = new Lib\ContactInfo($phoneNumbers, $emails);
            $contacts[] = new Lib\Contact($this->contact_mothers_name->value, $contactInfo);
        }
        // Create contact 2
        if ($this->contact_fathers_name !== NULL) {
            $phoneNumbers = [
                $this->contact_mobile_dad->value,
            ];
            $emails = [
                $this->contact_email_dad->value,
            ];
            $contactInfo = new Lib\ContactInfo($phoneNumbers, $emails);
            $contacts[] = new Lib\Contact($this->contact_fathers_name->value, $contactInfo);
        }
        return $contacts;
    }
}