<?php
/**
 * Contains ContactInfo class
 * @author Alexander Krantz
 */
namespace Org\Models;

/**
 * Contains contact info for a member or guardian.
 */
class ContactInfo {
    /** @var string[] */
    private $phoneNumbers;

    /** @var string[] */
    private $emailAddresses;

    /**
     * Creates contact info.
     * @internal
     * @param string[] $phoneNumbers A list of phone numbers.
     * @param string[] $emailAddresses A list of email addresses.
     */
    public function __construct(array $phoneNumbers, array $emailAddresses) {
        $this->phoneNumbers = $phoneNumbers;
        $this->emailAddresses = $emailAddresses;
    }

    /**
     * Gets a list of phone numbers.
     * @return string[]
     */
    public function getPhoneNumbers() {
        return $this->phoneNumbers;
    }

    /**
     * Gets a list of email addresses.
     * @return string[]
     */
    public function getEmailAddresses() {
        return $this->emailAddresses;
    }
}