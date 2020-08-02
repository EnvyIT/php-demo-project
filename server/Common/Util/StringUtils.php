<?php

namespace Common\Util;

use Core\Domain\Base\BaseObject;

class StringUtils extends BaseObject {

    /**
     * Safely escapes a string and returns the escaped one.
     * Should be used to prevent XSS-attacks.
     * @param string $string
     * @return string
     */
    public static function escape(string $string): string {
        return nl2br(htmlentities($string));
    }

  public static function normalizeFloatInput(string $input): string {
      return str_replace(',', '.', $input);
  }

}
