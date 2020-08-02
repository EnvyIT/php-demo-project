<?php

namespace Data\Database;

use Common\Logger\LoggerFactory;
use Exception;
use PDO;
use PDOStatement;

class DatabaseContext {

  private static $connection;
  private static $instance;

  private function __construct() {
  }

  public static function getInstance(): DatabaseContext {
    if (!isset(self::$instance)) {
      self::$instance = new DatabaseContext();
    }
    return self::$instance;
  }

  public function query($query, $params = []): PDOStatement {
    self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
      $statement = self::$connection->prepare($query);
      $statement->execute($params);
    } catch (Exception $exception) {
      LoggerFactory::createLogger('DatabaseContext')->error('query() ' . $exception->getMessage());
      die($exception->getMessage());
    }
    return $statement;
  }

  public function beginTransaction() {
    self::$connection->beginTransaction();
  }

  public function commit() {
    self::$connection->commit();
  }

  public function rollBack() {
    self::$connection->rollBack();
  }

  public function openConnection() {
    if (!isset(self::$connection)) {
      try {
        self::$connection = new PDO($_ENV["CONNECTOR"] . ':host=' . $_ENV["HOST"] . ';dbname=' . $_ENV["DATABASE"],
            $_ENV["DB_USER"], $_ENV["DB_PASS"]);
      } catch (Exception $exception) {
        LoggerFactory::createLogger('DatabaseContext')->error('openConnection() ' . $exception->getMessage());
        die("Database instance could not be created");
      }
    }
  }

  public function closeConnection(PDOStatement $cursor) {
    $cursor->closeCursor();
    self::$connection = null;
  }

  public function getLastInsertedId() {
    if (!isset(self::$connection)) {
      throw new Exception('Connection in DatbaseContext null can not get last inserted id!');
    }
    return self::$connection->lastInsertId();
  }


  public function isEmpty(PDOStatement $cursor): bool {
    return $cursor->rowCount() == 0;
  }


}
