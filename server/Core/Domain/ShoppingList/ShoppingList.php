<?php

namespace Core\Domain\ShoppingList;

use Core\Domain\Article\Article;
use Core\Domain\Base\BaseEntity;
use DateTime;
use JsonSerializable;

class ShoppingList extends BaseEntity implements JsonSerializable {

    private int $ownerId;
    private ?int $volunteerId;
    private ?string $name;
    private ?float $total;
    private ?DateTime $dueDate;
    private string $state;
    private array $articles;

    public function __construct(?int $id, ?string $name, ?float $total, int $owner, ?int $volunteer, ?DateTime $dueDate, string $state) {
        parent::__construct($id, false);
        $this->ownerId = $owner;
        $this->volunteerId = $volunteer;
        $this->name = $name;
        $this->total = $total;
        $this->dueDate = $dueDate;
        $this->state = $state;
        $this->articles = [];
    }

    public function getOwnerId(): int {
        return $this->ownerId;
    }

    public function getVolunteerId(): ?int {
        return $this->volunteerId;
    }

    public function getTotal(): ?float {
        return $this->total;
    }

    public function getDueDate(): ?DateTime {
        return $this->dueDate;
    }

    public function getArticles(): array {
        return $this->articles;
    }

    public function getShoppingListState(): string {
        return $this->state;
    }

    public function isUnpublished(): bool {
        return $this->state == ShoppingListState::UNPUBLISHED;
    }

    public function isNew(): bool {
        return $this->state == ShoppingListState::NEW;
    }

    public function isInProgress(): bool {
        return $this->state == ShoppingListState::IN_PROGRESS;
    }

    public function isDone(): bool {
        return $this->state == ShoppingListState::DONE;
    }

    public function setVolunteerId(int $volunteerId) {
        return $this->volunteerId = $volunteerId;
    }

    public function setTotal(float $total) {
        return $this->total = $total;
    }

    public function setDueDate(DateTime $dueDate) {
        return $this->dueDate = $dueDate;
    }

    public function addArticle(Article $article) {
        array_push($this->articles, $article);
    }

    public function setName(string $name) {
        $this->name = $name;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function jsonSerialize() {
        return ['id' => $this->getId(), 'ownerId' => $this->ownerId, 'volunteerId' => $this->volunteerId,
            'name' => $this->name, 'total' => $this->total,
            'dueDate' => $this->ownerId, 'state' => $this->state,
            $this->getArticles()];
    }

    public function areAllArticlesChecked(): bool {
        foreach ($this->articles as $article) {
            if (!$article->isChecked()) {
                return false;
            }
        }
        return true;
    }
}
