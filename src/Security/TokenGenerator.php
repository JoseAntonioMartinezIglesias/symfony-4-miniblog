<?php

namespace App\Security;

class TokenGenerator {

    const ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    /**
     * @param int $length
     * @return string
     */
    public function getRandomSecureToken(int $length): string {
        
        $maxNumber = strlen(self::ALPHABET);
        $token = '';

        for ($i = 0; $i < $length; $i++) {
            $token .= self::ALPHABET[random_int(0, $maxNumber - 1)];
        }
        return $token;
        
    }

}