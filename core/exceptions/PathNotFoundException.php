<?php

namespace app\core\exceptions;

class PathNotFoundException extends \Exception {
    protected $message = 'Page not found';
    protected $code = 404;
}