<?php
/**
 * Contains Value class
 * @author Alexander Krantz
 */
namespace Org\Scoutnet;

/**
 * Equivalent to a scoutnet api field with only a value.
 */
class Value {
    /** @var string The value */
    public $value;

    /**
     * Creates a new value from a scoutnet value.
     * @param object $value
     */
    public function __construct($value) {
        $this->value = $value->value;
    }

    /**
     * Gets the string equivalent of the class.
     * @return string
     */
    public function __toString() {
        return $this->value;
    }
}