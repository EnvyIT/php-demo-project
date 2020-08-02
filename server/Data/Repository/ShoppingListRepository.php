<?php


namespace Data\Repository;


use Common\Logger\LoggerFactory;
use Core\Domain\ShoppingList\ShoppingList;
use Core\Domain\ShoppingList\ShoppingListState;
use Data\Database\DatabaseContext;
use Data\Mapper\ArticleMapper;
use Data\Mapper\ShoppingListMapper;
use Exception;
use PDOStatement;

class ShoppingListRepository {

  private DatabaseContext $database;

  private static $instance = false;

  public static function getInstance() {
    if (!self::$instance) {
      self::$instance = new ShoppingListRepository();
    }
    return self::$instance;
  }

  private function __construct() {
    $this->database = DatabaseContext::getInstance();
  }

  public function createShoppingList(int $userId): int {
    $this->database->openConnection();
    $resultSet = $this->database->query("INSERT INTO ShoppingList (owner_id, volunteer_id ,name, total ,due_date, state, deleted) VALUES(?, ?, ?, ? ,?, ?, ?)",
        array($userId, null, null, null, null, ShoppingListState::UNPUBLISHED, false));
    $id = $this->database->getLastInsertedId();
    $this->database->closeConnection($resultSet);
    return $id;
  }

  public function getShoppingListById(int $shoppingListId): ShoppingList {
    $this->database->openConnection();
    $resultSet = $this->database->query("SELECT * FROM ShoppingList s where s.id = ? AND s.deleted = false",
        array($shoppingListId));
    $shoppingList = ShoppingListMapper::map($resultSet->fetch());
    if ($shoppingList == null) {
      throw new Exception("ShoppingList null!");
    }

    $resultSet = $this->database->query("SELECT * FROM Article a WHERE  a.shopping_list_id = ? AND a.deleted = false ORDER BY a.id DESC",
        array($shoppingListId));
    while ($row = $resultSet->fetch()) {
      $article = ArticleMapper::map($row);
      if ($article == null) {
        throw new Exception("Article null!");
      }
      $article->setShoppingListId($shoppingListId);
      $shoppingList->addArticle($article);
    }
    $this->database->closeConnection($resultSet);
    return $shoppingList;
  }

  public function publish(int $shoppingListId, string $dueDate, string $listName) {
    $date = date("Y-m-d", strtotime($dueDate));
    $this->database->openConnection();
    $this->database->beginTransaction();
    $resultSet = null;
    try {
      $resultSet = $this->database->query("UPDATE ShoppingList s SET s.state = ? WHERE  s.id = ?", array(ShoppingListState::NEW, $shoppingListId));
      $resultSet = $this->database->query("UPDATE ShoppingList s SET s.due_date = ? WHERE  s.id = ?", array($date, $shoppingListId));
      $resultSet = $this->database->query("UPDATE ShoppingList s SET s.name = ? WHERE  s.id = ?", array($listName, $shoppingListId));
      $this->database->commit();
    } catch (Exception $exception) {
      LoggerFactory::createLogger('ShoppingListRepository')->error('publish() ' . $exception->getMessage());
      $this->database->rollBack();
      throw new Exception("Could not publish - " . $exception->getMessage());
    } finally {
      $this->database->closeConnection($resultSet);
    }
  }

  public function setShoppingListDeleted(int $shoppingListId) {
    $this->database->openConnection();
    $resultSet = $this->database->query("UPDATE ShoppingList s SET s.deleted = ? WHERE  s.id = ?", array(true, $shoppingListId));
    $this->database->closeConnection($resultSet);
  }

  public function getOwnerShoppingListsByState(int $ownerId, string $state): array {
    $this->database->openConnection();
    $resultSet = $this->database->query("SELECT * FROM ShoppingList s WHERE s.owner_id = ? AND s.state = ? AND s.deleted = false", array($ownerId, $state));
    return $this->database->isEmpty($resultSet) ? [] : $this->includeArticles($resultSet);
  }

  public function getAllListsByState(string $state): array {
    $this->database->openConnection();
    $resultSet = $this->database->query("SELECT * FROM ShoppingList s WHERE s.state = ? AND s.deleted = false", array($state));
    return $this->database->isEmpty($resultSet) ? [] : $this->includeArticles($resultSet);
  }

  /**
   * @param $shoppingListResultSet
   * @return array
   */
  private function includeArticles(PDOStatement $shoppingListResultSet): array {
    $shoppingLists = [];
    while ($row = $shoppingListResultSet->fetch()) {
      $shoppingList = ShoppingListMapper::map($row);
      $articleResultSet = $this->database->query("SELECT * FROM Article a WHERE  a.shopping_list_id = ? AND a.deleted = false ORDER BY a.id DESC", array($shoppingList->getId()));
      while ($row = $articleResultSet->fetch()) {
        $article = ArticleMapper::map($row);
        $article->setShoppingListId($shoppingList->getId());
        $shoppingList->addArticle($article);
      }
      array_push($shoppingLists, $shoppingList);
    }
    $this->database->closeConnection($shoppingListResultSet);
    return $shoppingLists;
  }

  public function getVolunteerShoppingListsByState(int $volunteerId, string $state): array {
    $this->database->openConnection();
    $resultSet = $this->database->query("SELECT * FROM ShoppingList s WHERE s.volunteer_id = ? AND s.state = ? AND s.deleted = false ORDER BY s.due_date", array($volunteerId, $state));
    return $this->database->isEmpty($resultSet) ? [] : $this->includeArticles($resultSet);
  }

  public function takeOverShoppingList(int $shoppingListId, int $volunteerId) {
    $this->database->openConnection();
    $resultSet = $this->database->query("SELECT s.volunteer_id FROM ShoppingList s WHERE s.id = ? AND s.deleted = false", array($shoppingListId));
    $id = intval($resultSet->fetch()['volunteer_id']);
    if ($id != 0) {
      throw new Exception("List already assigned!");
    }
    $resultSet->closeCursor();
    $this->database->beginTransaction();
    try {
      $resultSet = $this->database->query("UPDATE ShoppingList s SET s.volunteer_id = ? WHERE  s.id = ?", array($volunteerId, $shoppingListId));
      $resultSet = $this->database->query("UPDATE ShoppingList s SET s.state = ? WHERE  s.id = ?", array(ShoppingListState::IN_PROGRESS, $shoppingListId));
      $this->database->commit();
    } catch (Exception $exception) {
      LoggerFactory::createLogger('ShoppingListRepository')->error('takeOverShoppingList() ' . $exception->getMessage());
      $this->database->rollBack();
    } finally {
      $this->database->closeConnection($resultSet);
    }
  }

  public function getShoppingListsByOwnerId(int $ownerId): array {
    $this->database->openConnection();
    $resultSet = $this->database->query("SELECT * FROM ShoppingList s WHERE s.owner_id = ? AND s.deleted = false ORDER BY s.state, s.due_date", array($ownerId));
    return $this->database->isEmpty($resultSet) ? [] : $this->includeArticles($resultSet);
  }

  public function updateListState(int $shoppingListId, string $listState) {
    $this->database->openConnection();
    $this->database->beginTransaction();
    $resultSet = null;
    try {
      $resultSet = $this->database->query("UPDATE ShoppingList s SET s.state = ? WHERE  s.id = ?", array($listState, $shoppingListId));
      $this->database->commit();
    } catch (Exception $exception) {
      LoggerFactory::createLogger('ShoppingListRepository')->error('updateListState() ' . $exception->getMessage());
      $this->database->rollBack();
    } finally {
      $this->database->closeConnection($resultSet);
    }
  }


  public
  function setTotalPrice(int $shoppingListId, ?float $totalPrice) {
    $this->database->openConnection();
    $this->database->beginTransaction();
    $resultSet = null;
    try {
      $resultSet = $this->database->query("UPDATE ShoppingList s SET s.total = ? WHERE  s.id = ?", array($totalPrice, $shoppingListId));
      $this->database->commit();
    } catch (Exception $exception) {
      LoggerFactory::createLogger('ShoppingListRepository')->error('setTotalPrice() ' . $exception->getMessage());
      $this->database->rollBack();
    } finally {
      $this->database->closeConnection($resultSet);
    }
  }


public
function updateListName(int $shoppingListId, string $listName) {
  $this->database->openConnection();
  $this->database->beginTransaction();
  $resultSet = null;
  try {
    $resultSet = $this->database->query("UPDATE ShoppingList s SET s.name = ? WHERE  s.id = ?", array($listName, $shoppingListId));
    $this->database->commit();
  } catch (Exception $exception) {
    LoggerFactory::createLogger('ShoppingListRepository')->error('updateListName() ' . $exception->getMessage());
    $this->database->rollBack();
  } finally {
    $this->database->closeConnection($resultSet);
  }
}

  public function updateDueDate(int $shoppingListId, string $dueDate) {
    $this->database->openConnection();
    $this->database->beginTransaction();
    $resultSet = null;
    try {
      $resultSet = $this->database->query("UPDATE ShoppingList s SET s.due_date = ? WHERE  s.id = ?", array($dueDate, $shoppingListId));
      $this->database->commit();
    } catch (Exception $exception) {
      LoggerFactory::createLogger('ShoppingListRepository')->error('updateDueDate() ' . $exception->getMessage());
      $this->database->rollBack();
    } finally {
      $this->database->closeConnection($resultSet);
    }
  }

}
