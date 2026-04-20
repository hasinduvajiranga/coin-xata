<?php

namespace App\Services;

class EncryptionService
{
    private string $key;
    private string $method = 'aes-256-gcm';

    public function __construct()
    {
        $this->key = config('app.encryption_key', env('APP_KEY'));
    }

    /**
     * Encrypt a value using AES-256-GCM.
     */
    public function encrypt(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->method));
        $tag = '';
        $encrypted = openssl_encrypt(
            (string) $value,
            $this->method,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );

        return base64_encode($iv . $tag . $encrypted);
    }

    /**
     * Decrypt a value using AES-256-GCM.
     */
    public function decrypt(?string $encryptedBase64): mixed
    {
        if (!$encryptedBase64) {
            return null;
        }

        try {
            $data = base64_decode($encryptedBase64);
            $ivLength = openssl_cipher_iv_length($this->method);
            $tagLength = 16;

            $iv = substr($data, 0, $ivLength);
            $tag = substr($data, $ivLength, $tagLength);
            $encrypted = substr($data, $ivLength + $tagLength);

            return openssl_decrypt(
                $encrypted,
                $this->method,
                $this->key,
                OPENSSL_RAW_DATA,
                $iv,
                $tag
            );
        } catch (\Exception $e) {
            return null;
        }
    }
}
