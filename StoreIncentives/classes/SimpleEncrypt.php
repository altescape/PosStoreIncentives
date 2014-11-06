<?php
/**
 * Created by michaelwatts
 * Date: 02/05/2014
 * Time: 15:44
 */

namespace StoreIncentives;


class SimpleEncrypt {

    protected $text_to_be_encrypted;

    function __construct($text_to_be_encrypted)
    {
        $this->text_to_be_encrypted = $text_to_be_encrypted;
    }

    /**
     * @return string
     */
    public function simpleEncrypt()
    {
        $salt ='098123132iidjais';
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $this->text_to_be_encrypted, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }

    function __toString()
    {
        return $this->simpleEncrypt();
    }


} 