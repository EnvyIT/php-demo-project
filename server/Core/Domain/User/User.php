<?php


namespace Core\Domain\User;

use Core\Domain\Base\BaseEntity;
use Core\Domain\Role\Role;
use DateTime;
use JsonSerializable;

class User extends BaseEntity implements JsonSerializable {

    private const EDIT_LIST_ID = "editListId";

    private string $firstName;
    private string $lastName;
    private string $userName;
    private string $password;
    private Role $role;
    private DateTime $creationDate;

    public function __construct(int $id, string $firstName, string $lastName, string $userName, string $password, DateTime $creationDate, Role $role) {
        parent::__construct($id, false);
        $this->userName = $userName;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->password = $password;
        $this->role = $role;
        $this->creationDate = $creationDate;
    }

    public function getUserName(): string {
        return $this->userName;
    }

    public function getFirstName(): string {
        return $this->firstName;
    }

    public function getLastName(): string {
        return $this->lastName;
    }

    public function getFullName(): string {
        return $this->firstName . " " . $this->lastName;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getRole() : Role {
        return $this->role;
    }

    public function getCreationDate() : DateTime {
        return $this->creationDate;
    }

    public function setFirstName(string $firstName) {
        $this->firstName = $firstName;
    }

    public function setLastName(string $lastName) {
        $this->lastName = $lastName;
    }

    public function setPassword(string $password) {
        $this->password = $password;
    }

    public function setRole(Role $role) {
        $this->role = $role;
    }

    public function __toString() {
        return $this->userName . " " . $this->firstName . " " . $this->lastName;
    }

    public function hasAccess(int $roleType): bool {
        $res = $this->getRole()->getCode() & $roleType;
        return $res == $roleType;
    }

    public function storeEditList(int $listId) {
        $_SESSION[self::EDIT_LIST_ID] = $listId;
    }

    public function getEditList(): int {
        return $_SESSION[self::EDIT_LIST_ID] ?? 0;
    }

    public function clearStore() {
        unset($_SESSION[self::EDIT_LIST_ID]);
    }

    public function jsonSerialize() {
        return ['id' => $this->getId(), 'userName' => $this->userName, 'firstName' => $this->firstName,
            'lastName' => $this->lastName, 'role' => $this->getRole()];
    }
}
