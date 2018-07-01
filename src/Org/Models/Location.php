<?php
/**
 * Contains Location class
 * @author Alexander Krantz
 */
namespace Org\Models;

/**
 * Contains accommodation info about a member.
 */
class Location {
    /** @var string */
    private $address;

    /** @var string */
    private $postCode;
    
    /** @var string */
    private $postTown;

    /**
     * Creates a new location.
     * @internal
     * @param string $address The living address.
     * @param string $postCode The swedish post code of the address.
     * @param string $postTown The town of the post code.
     */
    public function __construct(string $address, string $postCode, string $postTown) {
        $this->address = $address;
        $this->postCode = $postCode;
        $this->postTown = $postTown;
    }

    /**
     * Gets the address of the location.
     * @return string
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * Gets the post code of the location.
     * @return string
     */
    public function getPostCode() {
        return $this->postCode;
    }

    /**
     * Gets the post town of the location.
     * @return string
     */
    public function getPostTown() {
        return $this->postTown;
    }
}