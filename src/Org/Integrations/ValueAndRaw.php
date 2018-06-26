<?php
namespace Org\Integrations;

/**
 * Equivalent to a scoutnet api data field
 * with both value and raw value.
 */
class ValueAndRaw {
    /**
     * @var string
     */
    public $rawValue;
    /**
     * @var string
     */
    public $value;
    
    public function __toString() {
        return $this->value;
    }
}