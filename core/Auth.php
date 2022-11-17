<?php

namespace app\core;

class Auth {
    const ENCRYPT_METHOD = 'AES-256-CBC';

    private string $hashKey;

    public function __construct(string $hashKey){
        $this->hashKey = $hashKey;
    }

    /*
     * Authentication functions
     * These are for Authentication purposes, one for password encryption,
     * and one for general data encryption and decryption
     *
     * @param string $input is clear text
     * @return string the encrypted/decrypted data
     */

    public function passwordHash(string $input):string {
        return password_hash($input, PASSWORD_ARGON2ID, ['memory_cost' => 2048, 'time_cost' => 4, 'threads' => 3]);
    }

    public function encryptData(string $input):string {
        $iv = substr(hash('sha256', $this->hashKey), 0, 16);
        return base64_encode(openssl_encrypt($input, self::ENCRYPT_METHOD, $this->hashKey, 0, $iv));
    }

    public function decryptData(string $input):string {
        $iv = substr(hash('sha256', $this->hashKey), 0, 16);
        return openssl_decrypt(base64_decode($input), self::ENCRYPT_METHOD, $this->hashKey, 0, $iv);
    }
}