<?php


namespace Data\Mapper;

use Common\Logger\LoggerFactory;
use Core\Domain\ShoppingList\ShoppingList;
use DateTime;

class ShoppingListMapper {

    public static function map($shoppingListRow): ?ShoppingList {
        if ($shoppingListRow) {
            try {
                return new ShoppingList($shoppingListRow['id'], $shoppingListRow['name'], $shoppingListRow['total'],
                    $shoppingListRow['owner_id'], $shoppingListRow['volunteer_id'], new DateTime($shoppingListRow['due_date']), $shoppingListRow['state']);
            } catch (\Exception $exception) {
                LoggerFactory::createLogger("ShoppingListMapper")->error('map() failed - ' . $exception->getMessage());
            }
        }
        return null;
    }
}
