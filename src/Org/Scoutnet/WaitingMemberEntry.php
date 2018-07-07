<?php
/**
 * Contains WaitingMemberEntry class
 * @author Alexander Krantz
 */
namespace Org\Scoutnet;

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
                $newMemberEntry->{$dataFieldName} = new ValueAndRaw($dataField);
            } else {
                $newMemberEntry->{$dataFieldName} = new Value($dataField);
            }
        }
    }
}