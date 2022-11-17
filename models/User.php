<?php

namespace app\models;

use app\core\Application;
use app\core\UserModel;

class User extends UserModel {
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $confirmPassword = '';

    public static function tableName(): string {
        return 'users';
    }

    public function attributes(): array {
        return ['username', 'email', 'password'];
    }

    public function getDisplayName(): string {
        return Application::$app->auth->decryptData($this->username);
    }

    public function labels(): array {
        return [
            'username' => "Username",
            'email' => "Email address",
            'password' => "Password",
            'confirmPassword' => "Password confirmation",
        ];
    }

    public function save(): bool {
        $this->email = Application::$app->auth->encryptData($this->email);
        $this->username = Application::$app->auth->encryptData($this->username);
        $this->password = Application::$app->auth->passwordHash($this->password);
        return parent::save();
    }

    public function rules(): array {
        return [
            'username' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [self::RULE_UNIQUE, 'class' => self::class], 'attribute'],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8]],
            'confirmPassword' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']],
        ];
    }
}