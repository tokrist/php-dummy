<?php

namespace app\core\exception;

use Exception;
use app\core\Application;

class ForbiddenException extends Exception {
    protected $message = 'You don\'t have permission to access this page';
    protected $code = 403;
}