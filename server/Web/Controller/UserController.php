<?php

namespace Web\Controller;

use Common\Logger\Logger;
use Common\Logger\LoggerFactory;
use Common\Router\Route;
use Common\Router\Router;
use Common\Util\StringUtils;
use Common\Util\ValidationUtils;
use Core\Domain\Role\RoleType;
use Core\Domain\User\User;
use Exception;
use Service\UserService;
use Web\HTTP\HTTPResponse;


class UserController extends BaseController {

    const CONTROLLER = 'UserController';
    const LOGIN = 'login';
    const LOGOUT = 'logout';
    const USER_NAME = 'userName';
    const USER_PASSWORD = 'password';

    private UserService $userService;
    private static $instance = false;
    private Logger $logger;

    private function __construct() {
        $this->userService = UserService::getInstance();
        $this->logger = LoggerFactory::createLogger('UserController');
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new UserController();
        }
        return self::$instance;
    }

    public function getUserName(int $ownerId): string {
        $user = $this->userService->getUserById($ownerId);
        if ($user == null) {
            $this->logger->error("getUserName() failed - User was null!");
            HTTPResponse::notFound();
        }
        return $user->getUserName();
    }

    public function routingGuardHelpSeeker() {
        $this->protectedRouteFor(RoleType::HELP_SEEKER);
    }

    public function routingGuardVolunteer() {
        $this->protectedRouteFor(RoleType::VOLUNTEER);
    }

    private function protectedRouteFor(int $roleType): void {
        $user = $this->userService->getAuthenticatedUser();
        if ($user == null) {
            Router::redirect(Route::LOGIN);
        }
        if (!$user->hasAccess($roleType)) {
            Router::redirect(Route::DASHBOARD);
        }
    }

    public function getAuthenticatedUser(): ?User {
        $user = $this->userService->getAuthenticatedUser();
        if ($user == null) {
            Router::redirect(Route::LOGIN);
        }
        return $user;
    }

    public function post(): ?bool {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            throw new Exception('LoginController can only handle POST requests.');
            return null;
        } elseif (!isset($_REQUEST[self::CONTROLLER])) {
            throw new Exception(self::CONTROLLER . 'not specified.');
            return null;
        }

        $action = $_REQUEST[self::CONTROLLER];
        switch ($action) {
            case self::LOGIN:
                if (ValidationUtils::anyNotSet(array($_REQUEST[self::USER_NAME], $_REQUEST[self::USER_PASSWORD]))) {
                    $this->logger->error("LOGIN - One ore more values were not set!");
                    HTTPResponse::badRequest();
                }
                if (!$this->userService->authenticate(StringUtils::escape($_REQUEST[self::USER_NAME]), StringUtils::escape($_REQUEST[self::USER_PASSWORD]))) {
                    $this->logger->warning('Login attempt failed for Username:  ' . $_REQUEST[self::USER_NAME]);
                    HTTPResponse::unauthorized();
                }
                $this->logger->info('Login');
                Router::redirect(Route::DASHBOARD);
                break;

            case self::LOGOUT:
                $this->logger->info('Logout');
                $this->userService->signOut();
                Router::redirect(Route::LOGIN);
                break;

            default:
                break;
        }
        return false;
    }

}
