<?php


namespace Core\Domain\Role;


use JsonSerializable;

class RoleType implements JsonSerializable {

    const HELP_SEEKER = 1;
    const VOLUNTEER = 2;
    const ADMIN = 128;

    public function jsonSerialize() {
        return ['HELP_SEEKER' => self::HELP_SEEKER, 'VOLUNTEER' => self::VOLUNTEER, 'ADMIN' => self::ADMIN];
    }
}
