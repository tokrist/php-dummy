<?php

namespace app\core;

use app\core\database\DBModel;

abstract class UserModel extends DBModel {

    abstract public function getDisplayName():string;
}