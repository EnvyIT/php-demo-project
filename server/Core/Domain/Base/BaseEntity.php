<?php

namespace Core\Domain\Base;

class BaseEntity extends BaseObject implements Entity {

    private ?int $id;
    private bool $deleted;

    public function __construct(?int $id, bool $deleted) {
        $this->id = intval($id);
        $this->deleted = boolval($deleted);
    }

    public function getId(): int {
        return $this->id;
    }

    public function isDeleted(): bool {
        return $this->deleted;
    }

}
