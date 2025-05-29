<?php
namespace App\Helpers;
class PassHash {

    // No need for algo, cost, or unique_salt anymore

    /**
     * Hash the password using PHP's password_hash function.
     *
     * @param string $password The password to hash.
     * @return string|false The hashed password or false on failure.
     */
    public static function hash($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verify the password against a hash using PHP's password_verify function.
     *
     * @param string $password The password to verify.
     * @param string $hash The hash to verify against.
     * @return bool True if the password matches the hash, false otherwise.
     */
    public static function check_password($password, $hash) {
        return password_verify($password, $hash);
    }

}
