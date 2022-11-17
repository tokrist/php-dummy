<?php

namespace app\core\database;

use app\core\Application;
use app\core\Model;

abstract class DBModel extends Model {
    abstract public static function tableName():string;

    abstract public function attributes(): array;

    public static function primaryKey(): string {
        return 'id';
    }

    public function save(): bool {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => ":$attr", $attributes);

        $statement = self::prepare("INSERT INTO $tableName (".implode(',', $attributes).") VALUES (".implode(',', $params).") ");

        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }

        $statement->execute();
        return true;
    }

    public static function findOne($location) {
        $tableName = static::tableName();
        $attributes = array_keys($location);
        $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");

        foreach($location as $key => $item) {
            $statement->bindValue(":$key", $item);
        }

        $statement->execute();
        return $statement->fetchObject(static::class);
    }

    public static function prepare($sql): bool|\PDOStatement {
        return Application::$app->db->pdo->prepare($sql);
    }
}