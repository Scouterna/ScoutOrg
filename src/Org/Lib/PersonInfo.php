<?php
/**
 * Contains PersonalInfo class
 * @author Alexander Krantz
 */
namespace Org\Lib;

/**
 * Personal info about a member.
 */
class PersonInfo {
    /** @var string The person's first name. */
    private $firstname;

    /** @var string The person's last name. */
    private $lastname;

    /** @var string The person's personal number (personnummer) */
    private $ssno;

    /** @var string The person's gender. */
    private $gender;

    /** @var string The person's date of birth. */
    private $dateOfBirth;

    /**
     * Creates a new set of person info.
     * @internal
     * @param string $firstname The person's first name.
     * @param string $lastname The person's last name.
     * @param string $ssno The person's swedish personal number.
     * @param string $gender The person's gender.
     * @param string $dateOfBirth The person's date of birth
     */
    public function __construct(string $firstname, string $lastname, string $ssno, string $gender, string $dateOfBirth) {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->ssno = $ssno;
        $this->gender = $gender;
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * Gets the first name.
     * @return string
     */
    public function getFirstname() {
        return $this->firstname;
    }

    /**
     * Gets the last name.
     * @return string
     */
    public function getLastname() {
        return $this->lastname;
    }

    /**
     * Gets the personal number.
     * @return string
     */
    public function getSsno() {
        return $this->ssno;
    }

    /**
     * Gets the gender, usually 'Man' or 'Kvinna'.
     * @return string
     */
    public function getGender() {
        return $this->gender;
    }

    /**
     * Gets the date of birth.
     * @return string
     */ 
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }
}