<?php

namespace Service;

use Core\Domain\User\User;
use Data\Repository\UserRepository;

class UserService {

    private const USER_ID = 'userId';
    private UserRepository $userRepository;
    private static $instance = false;

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new UserService(UserRepository::getInstance());
        }
        return self::$instance;
    }

    private function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function authenticate(string $userName, string $password): bool {
        $user = $this->userRepository->getUserByUserName($userName);
        if ($user != null && $user->getPassword() == hash('sha512', $userName . '|' . $password)) {
            $_SESSION[self::USER_ID] = $user->getId();
            return true;
        }
        return false;
    }

    public function signOut() {
        session_destroy();
    }

    public function isAuthenticated(): bool {
        return isset($_SESSION[self::USER_ID]);
    }

    public function getAuthenticatedUser(): ?User {
        if (isset($_SESSION[self::USER_ID])) {
            return $this->userRepository->getUserById($_SESSION[self::USER_ID]);
        }
        return null;
    }

    public function getUserById(int $id): ?User {
        return $this->userRepository->getUserById($id);
    }

}
