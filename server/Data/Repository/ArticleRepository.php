<?php


namespace Data\Repository;


use Common\Logger\LoggerFactory;
use Core\Domain\Article\Article;
use Data\Database\DatabaseContext;

class ArticleRepository {

  private DatabaseContext $database;

  private static $instance = false;

  public static function getInstance() {
    if (!self::$instance) {
      self::$instance = new ArticleRepository();
    }
    return self::$instance;
  }

  private function __construct() {
    $this->database = DatabaseContext::getInstance();
  }

  public function add(Article $article) {
    $this->database->openConnection();
    $this->database->beginTransaction();
    $resultSet = null;
    try {
      $resultSet = $this->database->query("INSERT INTO Article(shopping_list_id, name, max_price, quantity, checked, deleted) VALUES (?, ?, ?, ?, ?, ?)",
          array($article->getShoppingListId(), $article->getName(), $article->getMaxPrice(), $article->getQuantity(), false, false));
      $this->database->commit();
    } catch (Exception $exception) {
      LoggerFactory::createLogger('ArticleRepository')->error('add() ' . $exception->getMessage());
      $this->database->rollBack();
    } finally {
      $this->database->closeConnection($resultSet);
    }
  }

  public function setArticleDeleted(int $articleId) {
    $this->database->openConnection();
    $this->database->beginTransaction();
    $resultSet = null;
    try {
      $resultSet = $this->database->query("UPDATE Article a SET a.deleted = true WHERE  a.id = ?", array($articleId));
      $this->database->commit();
    } catch (Exception $exception) {
      LoggerFactory::createLogger('ArticleRepository')->error('setArticleDeleted() ' . $exception->getMessage());
      $this->database->rollBack();
    } finally {
      $this->database->closeConnection($resultSet);
    }
  }

  public function setArticleChecked(int $articleId, bool $checked) {
    $this->database->openConnection();
    $this->database->beginTransaction();
    $resultSet = null;
    try {
      $resultSet = $this->database->query("UPDATE Article a SET a.checked = ? WHERE  a.id = ?",
          array($checked, $articleId));
      $this->database->commit();
    } catch (Exception $exception) {
      LoggerFactory::createLogger('ArticleRepository')->error('setArticleChecked() ' . $exception->getMessage());
      $this->database->rollBack();
    } finally {
      $this->database->closeConnection($resultSet);
    }
  }

}
