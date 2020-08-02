<?php


namespace Common\Util;

class ValidationUtils {


    /**
     * Returns true if any value is not set, otherwise false.
     * @param array $values
     * @return bool
     */
    public static function anyNotSet(array $values): bool {
        foreach ($values as $value) {
            if (!isset($value) || empty($value)) {
                return true;
            }
        }
        return false;
    }

    /**
     *  Returns true if any value is not of type float, otherwise false.
     * @param array $values
     * @return bool
     */
    public static function anyNoFloat(array $values): bool {
        foreach ($values as $value) {
            if (!is_int($value) || !is_float($value)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns true if any value is not of type int, otherwise false.
     * @param array $values
     * @return bool
     */
    public static function anyNoInt(array $values): bool {
        foreach ($values as $value) {
            if (!is_int($value)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns true if any value is not of type bool, otherwise false.
     * @param array $values
     * @return bool
     */
    public static function anyNoBool(array $values): bool {
        foreach ($values as $value) {
            if (!is_bool($value)) {
                return true;
            }
        }
        return false;
    }


}
