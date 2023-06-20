<?php

namespace app\core\exception;

use Exception;

class UnauthorizedException extends Exception {
    protected $message = 'You are not authorized to access this page';
    protected $code = 401;
}