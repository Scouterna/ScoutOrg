<?php
/**
 * Contains Contact class
 * @author Alexander Krantz
 */
namespace Org\Lib;

/**
 * Contains a contact or guardian of a member.
 */
class Contact {
    /** @var string The name of the contact person. */
    private $name;

    /** @var ContactInfo The contact info of the contact. */
    private $contactInfo;

    /**
     * Creates a new contact.
     * @internal
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