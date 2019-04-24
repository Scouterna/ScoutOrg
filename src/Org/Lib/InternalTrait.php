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