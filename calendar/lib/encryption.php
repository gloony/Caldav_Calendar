<?php
/**
 * A class to handle secure encryption and decryption of arbitrary data
 * Copyright http://stackoverflow.com/users/338665/ircmaxell
 * http://stackoverflow.com/questions/5089841/php-2-way-encryption-i-need-to-store-passwords-that-can-be-retrieved
 *
 *
 * Note that this is not just straight encryption.  It also has a few other
 *  features in it to make the encrypted data far more secure.  Note that any
 *  other implementations used to decrypt data will have to do the same exact
 *  operations.
 *
 * Security Benefits:
 *
 * - Uses Key stretching
 * - Hides the Initialization Vector
 * - Does HMAC verification of source data
 *
 */
class Encryption {

    /**
     * @var string $PassPhrase The mcrypt cipher to use for this instance
     */
    private static $PassPhrase = null;

    /**
     * @var string $PassIV The mcrypt cipher to use for this instance
     */
    private static $PassIV = null;

    /**
     * Encrypt the supplied data using the supplied key
     *
     * @param string $PassPhrase The data to encrypt
     * @param string $PassIV  The key to encrypt with
     *
     * @returns string The encrypted data
     */
    public function __construct($PassPhrase, $PassIV){
        $this->PassPhrase = $PassPhrase;
        $this->PassIV = $PassIV;
    }

    /**
     * Encrypt the supplied data using the supplied key
     *
     * @param string $data The data to encrypt
     *
     * @returns string The encrypted data
     */
    public static function encrypt($String){
        $Method = 'AES-256-CBC';
        $Key = hash('sha256', static::$PassPhrase);
        $IV = substr(hash('sha256', static::$PassIV), 0, 16);
        return openssl_encrypt($String, $Method, $Key, 0, $IV);
    }

    /**
     * Decrypt the data with the provided key
     *
     * @param string $data The encrypted datat to decrypt
     *
     * @returns string|false The returned string if decryption is successful
     *                           false if it is not
     */
    public static function decrypt($String){
        $Method = 'AES-256-CBC';
        $Key = hash('sha256', static::$PassPhrase);
        $IV = substr(hash('sha256', static::$PassIV), 0, 16);
        return openssl_decrypt($String, $Method, $Key, 0, $IV);
        // return '2DmfW-ikG6C-XRTGW-fsBpp-sTBHz';
    }
}
