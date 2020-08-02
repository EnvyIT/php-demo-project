<?php

namespace Data\Mapper;

use Common\Logger\LoggerFactory;
use Core\Domain\Role\Role;
use Core\Domain\User\User;
use DateTime;

class UserMapper {

    public static function map($userRow, $roleRow): ?User {
        if ($userRow) {
            try {
                return new User($userRow['id'], $userRow['first_name'], $userRow['last_name'], $userRow['user_name'],
                    $userRow['password'], new DateTime($userRow['creation_date']),
                    new Role($roleRow['id'], $roleRow['name'], $roleRow['code']));
            } catch (\Exception $exception) {
                LoggerFactory::createLogger("UserMapper")->error('map() failed - ' . $exception->getMessage());
            }
        }
        return null;
    }

}
