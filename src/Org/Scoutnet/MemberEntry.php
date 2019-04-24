<?php
/**
 * Contains MemberEntry class
 * @author Alexander Krantz
 */
namespace Org\Scoutnet;
use Org\Lib;

/**
 * Contains fields equivalent to the data received through
 * the scoutnet api.
 */
class MemberEntry {
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

    /**
     * @var ValueAndRaw The troop the person is a member in.
     */
    public $unit;

    /**
     * @var ValueAndRaw The patrol the person is a member in.
     */
    public $patrol;

    /**
     * @var ValueAndRaw The troop role.
     */
    public $unit_role;

    /**
     * @var ValueAndRaw A list of group roles separated by comma.
     * Values are separated by <comma space> while raw values are only
     * separated by a comma.
     */
    public $group_role;

    /**
     * @var ValueAndRaw The gender of the person.
     */
    public $sex;

    /**
     * @var Value An address that possibly means something i guess.
     */
    public $address_co;

    /**
     * @var Value The primary address.
     */
    public $address_1;

    /**
     * @var Value The secondary address.
     */
    public $address_2;

    /**
     * @var Value The Thirdary address.
     */
    public $address_3;

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
     * @var Value The secondary email.
     */
    public $contact_alt_email;

    /**
     * @var Value The mobile phone number of the person.
     */
    public $contact_mobile_phone;

    /**
     * @var Value The home phone number of the person.
     */
    public $contact_home_phone;

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
     * @var Value Wether someone that is related to the person is interested
     * in becoming a leader.
     */
    public $contact_leader_interest;

    /**
     * @var Value The email of the person's contact person.
     */
    public $contact_annan_kontaktperson_epost;

    /**
     * @var Value The name of the person's contact person.
     */
    public $contact_annan_kontaktperson_namn;

    /**
     * @var Value The phone number of the person's contact person.
     */
    public $contact_annan_kontaktperson_telefon;

    /**
     * @var Value The phone number to the person's workplace.
     */
    public $contact_work_phone;

    /**
     * @var Value another fucking email.
     */
    public $contact_email;

    /**
     * @var Value The email to the person's workplace.
     */
    public $contact_work_email;

    /**
     * @var Value The email to the person's guardian.
     */
    public $contact_guardian_email;

    /**
     * @var Value The phone number to the person's guardian.
     */
    public $contact_guardian_phone_no;

    /**
     * @var Value Unknown. May be wether instant messaging is enabled.
     */
    public $contact_instant_messaging;

    /**
     * @var Value Mobile phone number to the person's home.
     */
    public $contact_mobile_home;

    /**
     * @var Value Mobile phone number to the person.
     */
    public $contact_mobile_me;

    /**
     * @var Value Mobile phone number to the person's work.
     */
    public $contact_mobile_work;

    /**
     * @var Value The email to the person's second guardian.
     */
    public $contact_maalsmans_epost;

    /**
     * @var Value The phone number to the person's second guardian.
     */
    public $contact_maalsmans_mobil;

    /**
     * @var Value The name of the person's second guardian.
     */
    public $contact_maalsmans_namn;

    /**
     * @var Value The phone number of the person's second guardian.
     */
    public $contact_maalsmans_telefon;

    /**
     * @var Value The person's skype username
     */
    public $contact_skype_user;

    /**
     * @var Value The phone number to the person's home.
     */
    public $contact_telephone_home;

    /**
     * @var Value The phone number to the person's mobile phone.
     */
    public $contact_telephone_work;

    /**
     * @var Value The phone number to the person.
     */
    public $contact_telephone_me;

    /**
     * @var ValueAndRaw Wether the person has payed for the previous term.
     * Raw is 'paid' if paid and 'not_due_unpaid' if not paid.
     */
    public $prev_term;

    /**
     * @var Value The due date of the previous term payment.
     */
    public $prev_term_due_date;

    /**
     * @var ValueAndRaw Wether the person has payed for the current term.
     * Raw is 'paid' if paid and 'not_due_unpaid' if not paid.
     */
    public $current_term;

    /**
     * @var Value The due date for the current term payment.
     */
    public $current_term_due_date;

    /**
     * Creates a new member entry from a scoutnet entry.
     * @param object $entry
     */
    public function __construct($entry) {
        foreach ($entry as $dataFieldName => $dataField) {
            $classFieldName = str_replace('-', '', $dataFieldName);
            if (isset($dataField->raw_value)) {
                $this->{$classFieldName} = new ValueAndRaw($dataField);
            } else {
                $this->{$classFieldName} = new Value($dataField);
            }
        }
    }

    /**
     * Gets the person info of the member.
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
     * Gets the contact info of the member.
     * @return Lib\ContactInfo
     */
    private function getContactInfo() {
        $phoneNumbers = [];
        if ($this->contact_mobile_phone !== NULL) {
            $phoneNumbers[] = $this->contact_mobile_phone->value;
        }
        if ($this->contact_home_phone !== NULL) {
            $phoneNumbers[] = $this->contact_home_phone->value;
        }
        if ($this->contact_work_phone !== NULL) {
            $phoneNumbers[] = $this->contact_work_phone->value;
        }
        $emailAddresses = [];
        if ($this->email !== NULL) {
            $emailAddresses[] = $this->email->value;
        }
        if ($this->contact_alt_email !== NULL) {
            $emailAddresses[] = $this->contact_alt_email->value;
        }
        return new Lib\ContactInfo($phoneNumbers, $emailAddresses);
    }

    /**
     * Gets the accommodation info from the member.
     * @return Lib\Location
     */
    private function getAccommodation() {
        return new Lib\Location($this->address_1->value, $this->postcode->value, $this->town->value);
    }

    /**
     * Gets the contacts of the member.
     * @return Lib\Contact[]
     */
    private function getContacts() {
        $contacts = [];
        // Create contact 1
        if ($this->contact_mothers_name !== NULL) {
            $phoneNumbers = [];
            if ($this->contact_mobile_mum !== NULL) {
                $phoneNumbers[] = $this->contact_mobile_mum->value;
            }
            $emails = [];
            if ($this->contact_email_mum !== NULL) {
                $emails[] = $this->contact_email_mum->value;
            }
            $contactInfo = new Lib\ContactInfo($phoneNumbers, $emails);
            $contacts[] = new Lib\Contact($this->contact_mothers_name->value, $contactInfo);
        }
        // Create contact 2
        if ($this->contact_fathers_name !== NULL) {
            $phoneNumbers = [];
            if ($this->contact_mobile_dad !== NULL) {
                $phoneNumbers[] = $this->contact_mobile_dad->value;
            }
            $emails = [];
            if ($this->contact_email_dad !== NULL) {
                $emails[] = $this->contact_email_dad->value;
            }
            $contactInfo = new Lib\ContactInfo($phoneNumbers, $emails);
            $contacts[] = new Lib\Contact($this->contact_fathers_name->value, $contactInfo);
        }
        return $contacts;
    }

    /**
     * Gets the member.
     * @return Lib\Member
     */
    public function getMember() {
        $member = new Lib\Member((int)$this->member_no->value,
            $this->getPersonInfo(),
            $this->getContactInfo(),
            $this->getAccommodation(),
            $this->getContacts(),
            $this->confirmed_at->value);
        return $member;
    }

    /** 
     * Gets the troop from the member.
     * @return Lib\Troop|null
     */
    public function getTroop() {
        if ($this->unit === null) {
            return null;
        }
        return new Lib\Troop((int)$this->unit->rawValue, $this->unit->value);
    }

    /**
     * Gets the patrol from the member.
     * @return Lib\Patrol|null
     */
    public function getPatrol() {
        if ($this->patrol === null) {
            return null;
        }
        return new Lib\Patrol((int)$this->patrol->rawValue, $this->patrol->value);
    }

    /**
     * Gets the role groups from the member.
     * @return Lib\RoleGroup[]|null
     */
    public function getRoleGroups() {
        if ($this->group_role === null) {
            return null;
        }
        $roleGroups = [];
        $roleGroupNames = explode(', ', $this->group_role->value);
        foreach (explode(',', $this->group_role->rawValue) as $index => $roleGroupId) {
            $roleGroups[$roleGroupId] = new Lib\RoleGroup($roleGroupId, $roleGroupNames[$index]);
        }
        return $roleGroups;
    }
}