<?php

namespace thecodeholic\phpmvc;

use thecodeholic\phpmvc\database\DbModel;

abstract class UserModel extends DbModel {
    abstract public function getDisplayName(): string;
}