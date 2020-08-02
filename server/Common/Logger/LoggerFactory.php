<?php


namespace Common\Logger;

use DateTime;
use DateTimeZone;
use Service\UserService;

class LoggerFactory {

    public static function createLogger(string $class): Logger {
        return new Logger($class);
    }

}


class Logger implements ILogger {

    private string $class;
    private string $path;
    private const DEBUG = 'DEBUG';
    private const INFO = 'INFO';
    private const WARNING = 'WARNING';
    private const ERROR = 'ERROR';
    private UserService $userService;

    public function __construct(string $class) {
        $this->userService = UserService::getInstance();
        $this->class = $class;
        $now = date('j_n_Y');
        $this->createFolderIfNoneExists($now);
        $this->path = $_SERVER['DOCUMENT_ROOT'] . '/logs/' . $now . '/log_' . $now . '.log';
    }

    private function format(string $level, string $message): string {
        $dateTimeNow = new DateTime('now', new DateTimeZone('UTC'));
        $user = $this->userService->getAuthenticatedUser();
        $username = $user == null ? ' - ' : $user->getUserName();
        return '[' . $level . ']-[' . $dateTimeNow->format(DateTime::RFC850) . '].-[' . $this->class . ']-[IP ' . $this->getIP() . ']-[USER: ' . $username . ' ]-[ACTION: ' . $message . ']' . PHP_EOL;
    }

    private function createFolderIfNoneExists(string $now) {
        $log_filename = $_SERVER['DOCUMENT_ROOT'] . '/logs/' . $now;
        if (!file_exists($log_filename)) {
            mkdir($log_filename, 0777, true);
        }
    }

    private function getIP(): string {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];//shared network address
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR']; //proxy address
        }
        return $_SERVER['REMOTE_ADDR']; //remote address
    }

    /**
     * Creates a log message on log level INFO.
     * @param string $message
     */
    public function info(string $message) {
        file_put_contents($this->path, $this->format(self::INFO, $message), FILE_APPEND);
    }

    /**
     * Creates a log message on log level WARNING.
     * @param string $message
     */
    public function warning(string $message) {
        file_put_contents($this->path, $this->format(self::WARNING, $message), FILE_APPEND);
    }

    /**
     * Creates a log message on log level ERROR.
     * @param string $message
     */
    public function error(string $message) {
        file_put_contents($this->path, $this->format(self::ERROR, $message), FILE_APPEND);
    }

    /**
     * Creates a log message on log level DEBUG.
     * @param string $message
     */
    public function debug(string $message) {
        file_put_contents($this->path, $this->format(self::DEBUG, $message), FILE_APPEND);
    }
}


