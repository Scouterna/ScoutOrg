<?php
/**
 * Contains InernalTrait trait
 * @author Alexander Krantz
 */
namespace Org\Models;

/**
 * A replacement for making namespace private members.
 * Should only be used inside \Org\Models
 * @internal
 */
trait InternalTrait {
    /**
     * Sets a private field only if the
     * setting class is in the Models namespace.
     * @internal
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, $value) {
        $callstack = debug_backtrace(0, 2);
        $class = $callstack[1]['class'];
        if (strpos($class, 'Org\\Models\\') === 0) {
            $this->$name = $value;
        }
    }

    /**
     * Gets a private field only if the
     * getting class is in the Models namespace.
     * @internal
     * @param string $name
     * @return mixed
     */
    public function __get(string $name) {
        if (isset($this->$name)) {
            $callstack = \debug_backtrace(0, 2);
            $class = $callstack[1]['class'];
            if (strpos($class, 'Org\\Models\\') === 0) {
                return $this->$name;
            }
        }
    }
}