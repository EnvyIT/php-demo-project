<?php

namespace Data\Repository;

use Core\Domain\User\User;
use Data\Database\DatabaseContext;
use Data\Mapper\UserMapper;


class UserRepository {

  private DatabaseContext $database;

  private static $instance = false;

  public static function getInstance() {
    if (!self::$instance) {
      self::$instance = new UserRepository();
    }
    return self::$instance;
  }

  private function __construct() {
    $this->database = DatabaseContext::getInstance();
  }

  public function getUserByUserName(string $userName): ?User {
    $this->database->openConnection();
    $userResultSet = $this->database->query("SELECT * FROM user u where u.user_name = ? AND u.deleted = false", array($userName));
    if ($this->database->isEmpty($userResultSet)) {
      return null;
    }
    $roleResultSet = $this->database->query("SELECT * FROM role r where r.id = (SELECT u.role_id FROM user u where u.user_name = ?)", array($userName));
    $user = UserMapper::map($userResultSet->fetch(), $roleResultSet->fetch());
    $this->database->closeConnection($roleResultSet);
    return $user;
  }

  public function getUserById(int $id): ?User {
    $this->database->openConnection();
    $userResultSet = $this->database->query("SELECT * FROM user u where u.id = ? AND u.deleted = false", array($id));
    if ($this->database->isEmpty($userResultSet)) {
      return null;
    }
    $roleResultSet = $this->database->query("SELECT * FROM role r where r.id = (SELECT u.role_id FROM user u where u.id = ?)", array($id));
    $user = UserMapper::map($userResultSet->fetch(), $roleResultSet->fetch());
    $this->database->closeConnection($roleResultSet);
    return $user;
  }

}
