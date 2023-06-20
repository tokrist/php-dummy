<?php

namespace app\core\exception;

use Exception;

class NotFoundException extends Exception {
    protected $message = 'The page you are looking for is not found';
    protected $code = 404;
}