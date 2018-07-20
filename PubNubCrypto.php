<?php

/*
    class PubNubCrypto 实现 PubNubCryptoCore
    
    实现方法：
    
      encrypt($plainText)
      
      decrypt($cipherText, $logger = null)
*/

namespace PubNub;


use Monolog\Logger;
use PubNub\Exceptions\PubNubResponseParsingException;

// PubNubCrypto 继承 PubNubCryptoCore 
class PubNubCrypto extends PubNubCryptoCore {

    // 实现加密方法
    public function encrypt($plainText) {
        // hash 调用 sha256 加密方式，对密钥进行加密
        $shaCipherKey = hash("sha256", $this->cipherKey);
        // 截图 32 位长度的 cipher
        $paddedCipherKey = substr($shaCipherKey, 0, 32);
        // 调用 openssl_encrypt 对 $plainText 进行 aes-256-cbc 加密
        $encrypted = openssl_encrypt($plainText, 'aes-256-cbc', $paddedCipherKey, OPENSSL_RAW_DATA,
            $this->initializationVector);
        // 进行 base64_encode($encrypted)
        $encode = base64_encode($encrypted);

        return $encode;
    }

    public function decrypt($cipherText, $logger = null) {
    
        // 使用匿名函数，赋值给变量 $logError
        $logError = function ($message) use ($logger) {
            if ($logger !== null && $logger instanceof Logger) {
                $logger->error($message);
            }
        };

        if (is_array($cipherText)) {
            if (array_key_exists("pn_other", $cipherText)) {
                $cipherText = $cipherText["pn_other"];
            } else {
                if (is_array($cipherText)) {
                    $logError("Decryption error: message is not a string or object");
                    throw new PubNubResponseParsingException("Decryption error: message is not a string");
                } else {
                    $logError("Decryption error: pn_other object key missing: " . $cipherText);
                    throw new PubNubResponseParsingException("Decryption error: pn_other object key missing");
                }
            }
        } else if (!is_string($cipherText)) {
            $logError("Decryption error: message is not a string: " . $cipherText);
            throw new PubNubResponseParsingException("Decryption error: message is not a string or object");
        }

        if (strlen($cipherText) === 0){
            $logError("Decryption error: message is empty");
            throw new PubNubResponseParsingException("Decryption error: message is empty");
        }

        $shaCipherKey = hash("sha256", $this->cipherKey);
        $paddedCipherKey = substr($shaCipherKey, 0, 32);

        $decrypted = openssl_decrypt($cipherText, 'aes-256-cbc', $paddedCipherKey, 0,
            $this->initializationVector);

        if ($decrypted === false) {
            $logError("Decryption error: " . openssl_error_string());
            throw new PubNubResponseParsingException("Decryption error: " . openssl_error_string());
        }

        $unPadded = $this->unPadPKCS7($decrypted, 16);

        return $this->tryToJsonDecode($unPadded);
        /*
        $result = json_decode($unPadded);

        if ($result === null) {
            return $unPadded;
        } else {
            return $result;
        }
        */
    }

}
