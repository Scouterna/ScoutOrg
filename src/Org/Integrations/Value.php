<?php
namespace Org\Integrations;

/**
 * Equivalent to a scoutnet api field with only a value.
 */
class Value {
    /** @var string */
    public $value;

    public function __toString() {
        return $this->value;
    }
}