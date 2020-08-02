<?php


namespace Core\Domain\ShoppingList;

use JsonSerializable;

class ShoppingListState implements JsonSerializable {
    const UNPUBLISHED = 'unpublished';
    const NEW = 'new';
    const IN_PROGRESS = 'in progress';
    const DONE = 'done';

    public function jsonSerialize() {
        return ['unpublished' => self::UNPUBLISHED, 'new' => self::NEW,
            'inProgress' => self::IN_PROGRESS, 'done' => self::DONE];
    }
}
