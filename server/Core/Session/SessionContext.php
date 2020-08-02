<?php

namespace Core\Session;

use Core\Domain\Base\BaseObject;

class SessionContext extends BaseObject {

    private static bool $exists = false;

    public static function create(): bool {
        if (!self::$exists) {
            self::$exists = session_start();
        }
        return self::$exists;
    }

}
