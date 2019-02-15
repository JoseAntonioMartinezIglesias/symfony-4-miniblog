<?php

namespace App\Security;

class PasswordGenerator {

    const ALPHABET = 'abcdefghijklmnopqrstuvwxyz';
    const NUMBERS = '0123456789';

    /**
     * @param int $length_alphabet
     * @param int $length_number
     * @return string
     */
    public function getRandomPassword(int $length_alphabet, int $length_number): string {
        
        $password = '';

        for ($i = 0; $i < $length_alphabet; $i++) {
            $password .= self::ALPHABET[random_int(0, strlen(self::ALPHABET) - 1)];
        }
        
        for ($i = 0; $i < $length_number; $i++) {
            $password .= self::NUMBERS[random_int(0, 9)];
        }
        
        return ucfirst($password);
        
    }

}
