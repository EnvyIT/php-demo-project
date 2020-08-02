<?php


namespace Service;

use Core\Domain\Article\Article;
use Core\Domain\ShoppingList\ShoppingList;
use Data\Repository\ArticleRepository;
use Data\Repository\ShoppingListRepository;

class ShoppingListService {

    private static $instance = false;
    private ShoppingListRepository $shoppingListRepository;
    private ArticleRepository $articleRepository;

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new ShoppingListService(ShoppingListRepository::getInstance(), ArticleRepository::getInstance());
        }
        return self::$instance;
    }

    private function __construct(ShoppingListRepository $shoppingListRepository, ArticleRepository $articleRepository) {
        $this->shoppingListRepository = $shoppingListRepository;
        $this->articleRepository = $articleRepository;
    }

    public function newShoppingList(int $userId): int {
        return $this->shoppingListRepository->createShoppingList($userId);
    }

    public function addArticle(string $name, int $quantity, float $maxPrice, int $shoppingListId) {
        $this->articleRepository->add(new Article(null, $name, $quantity, $maxPrice, $shoppingListId, false));
    }

    public function getShoppingListById(int $shoppingListId): ShoppingList {
        return $this->shoppingListRepository->getShoppingListById($shoppingListId);
    }

    public function deleteArticle(int $articleId) {
        $this->articleRepository->setArticleDeleted($articleId);
    }

    public function publish(int $shoppingListId, string $dueDate, string $listName) {
        $this->shoppingListRepository->publish($shoppingListId, $dueDate, $listName);
    }

    public function getAllHelpSeekerListsByState(int $ownerId, string $state): array {
        return $this->shoppingListRepository->getOwnerShoppingListsByState($ownerId, $state);
    }

    public function getAllListsByOwnerId(int $ownerId): array {
        return $this->shoppingListRepository->getShoppingListsByOwnerId($ownerId);
    }

    public function getAllVolunteerListsByState(int $volunteerId, string $state): array {
        return $this->shoppingListRepository->getVolunteerShoppingListsByState($volunteerId, $state);
    }

    public function getAllShoppingListsByState(string $state): array {
        return $this->shoppingListRepository->getAllListsByState($state);
    }

    public function deleteList(int $shoppingListId) {
        $this->shoppingListRepository->setShoppingListDeleted($shoppingListId);
    }

    public function takeOverList(int $shoppingListId, int $volunteerId) {
        $this->shoppingListRepository->takeOverShoppingList($shoppingListId, $volunteerId);
    }

    public function toggleArticleCheck(int $articleId, bool $checked) {
        $this->articleRepository->setArticleChecked($articleId, $checked);
    }

    public function updateListState(int $shoppingListId, string $listState) {
        $this->shoppingListRepository->updateListState($shoppingListId, $listState);
    }

    public function setTotalPrice(int $shoppingListId, ?float $totalPrice) {
        $this->shoppingListRepository->setTotalPrice($shoppingListId, $totalPrice);
    }

    public function updateListName(int $shoppingListId, string $listName) {
        $this->shoppingListRepository->updateListName($shoppingListId, $listName);
    }

    public function updateDueDate(int $shoppingListId, string $dueDate) {
      $this->shoppingListRepository->updateDueDate($shoppingListId, $dueDate);
    }

}
