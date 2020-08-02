<?php


namespace Core\Domain\Role;

use Core\Domain\Base\BaseEntity;
use JsonSerializable;

class Role extends BaseEntity implements JsonSerializable {

    private string $name;
    private int $code;

    public function __construct(int $id, string $name, int $code) {
        parent::__construct($id, false);
        $this->name = $name;
        $this->code = $code;
    }

    public function getCode(): int {
        return $this->code;
    }

    public function getName(): int {
        return $this->name;
    }

    public function jsonSerialize() {
        return ['id' => $this->getId(), 'name' => $this->name, 'code' => $this->code];
    }
}
