<?php
/**
 * Contains InernalTrait trait
 * @author Alexander Krantz
 */
namespace Org\Lib;

/**
 * A replacement for making namespace private members.
 * Should only be used inside \Org
 * @internal
 */
trait InternalTrait {
    /**
     * Sets a private field only if the
     * setting class is in the Org namespace.
     * @internal
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, $value) {
        $callstack = debug_backtrace(0, 2);
        $class = $callstack[1]['class'];
        if (strpos($class, 'Org\\') === 0) {
            $this->$name = $value;
        }
    }

    /**
     * Gets a private field only if the
     * getting class is in the Org namespace.
     * @internal
     * @param string $name
     * @return mixed
     */
    public function __get(string $name) {
        if (isset($this->$name)) {
            $callstack = \debug_backtrace(0, 2);
            $class = $callstack[1]['class'];
            if (strpos($class, 'Org\\') === 0) {
                return $this->$name;
            }
        }
    }

    /**
     * Calls a private method only if the
     * calling class is in the Org namespace.
     * @internal
     * @param string $name
     * @param array $args
     * @return mixed
     */
    public function __call(string $name, array $args) {
        if (method_exists($this, $name)) {
            $callstack = \debug_backtrace(0, 2);
            $class = $callstack[1]['class'];
            if (strpos($class, 'Org\\') === 0) {
                return \call_user_func_array([$this, $name], $args);
            }
        }
    }
}