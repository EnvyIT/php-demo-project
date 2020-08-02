<?php

namespace Data\Mapper;

use Core\Domain\Role\Role;
use PDORow;

class RoleMapper {

    public function map(PDORow $shoppingListRow): ?Role {
        if ($shoppingListRow) {
            return new Role($shoppingListRow['id'], $shoppingListRow['name'], $shoppingListRow['code']);
        }
        return null;
    }

}
