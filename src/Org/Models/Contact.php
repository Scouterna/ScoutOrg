<?php
namespace Org\Models;

/**
 * Contains a contact of a member.
 */
class Contact {
    private $name;
    private $contactInfo;

    /**
     * Creates a new contact.
     * @param string $name
     * @param ContactInfo $contactInfo
     */
    public function __construct(string $name, ContactInfo $contactInfo) {
        $this->name = $name;
        $this->contactInfo = $contactInfo;
    }

    /**
     * Gets the name of the contact.
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Gets the contact info of the contact.
     * @return ContactInfo
     */
    public function getContactInfo() {
        return $this->contactInfo;
    }
}