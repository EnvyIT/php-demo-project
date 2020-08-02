<?php

namespace Core\Domain\Article;

use Core\Domain\Base\BaseEntity;
use JsonSerializable;

class Article extends BaseEntity implements JsonSerializable {

    private string $name;
    private int $quantity;
    private float $maxPrice;
    private ?int  $shoppingListId;
    private bool  $checked;

    public function __construct(?int $id, string $name, int $quantity, float $maxPrice, ?int $shoppingListId, bool $checked) {
        parent::__construct($id, false);
        $this->name = $name;
        $this->quantity = $quantity;
        $this->maxPrice = $maxPrice;
        $this->shoppingListId = $shoppingListId;
        $this->checked = $checked;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getQuantity(): int {
        return $this->quantity;
    }

    public function getMaxPrice(): float {
        return $this->maxPrice;
    }

    public function getShoppingListId(): ?int {
        return $this->shoppingListId;
    }

    public function isChecked(): bool {
        return $this->checked;
    }

    public function setName(string $name) {
        return $this->name = $name;
    }

    public function setQuantity(int $quantity) {
        return $this->quantity = $quantity;
    }

    public function setMaxPrice(float $maxPrice) {
        return $this->maxPrice = $maxPrice;
    }

    public function setChecked(bool $checked) {
        $this->checked = $checked;
    }

    public function setShoppingListId(?int $shoppingListId) {
        return $this->shoppingListId = $shoppingListId;
    }

    public function jsonSerialize() {
        return ['id' => $this->getId(), 'name' => $this->name, 'quantity' => $this->quantity,
            'maxPrice' => $this->maxPrice, 'shoppingListId' => $this->shoppingListId,
            'checked' => $this->checked];
    }
}
