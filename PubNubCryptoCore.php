<?php
/*
    abstract class PubMNubCryptoCore
    
    方法：
    
        抽象方法 decrypt, encrypt

        普通方法 
        
        getCipherKey, 
        setCipherKey, 
        pkcs5Pad, 
        unPadPKCS7, 
        isBlank,
        tryToJsonDecode
*/

// 命名空间 PubNub
namespace PubNub;

// 使用 Monolog\Logger 日志类
use Monolog\Logger;

// 定义抽象类 PubNubCryptoCore
// cryptography 
abstract class PubNubCryptoCore{
    /** @var  string */
    // 受保护的 $cipherKey
    protected $cipherKey;
    
   
    /** @var  string */
    // 受保护的 $initializationVector
    protected $initializationVector;

    // 构造函数初始化 $this->cipherKey, $this->initializationVector
    public function __construct($key, $initializationVector = "0123456789012345")
    {
        $this->cipherKey = $key;
        $this->initializationVector = $initializationVector;
    }

    /**
     * @param string | object $cipherText
     * @param Logger | null $logger
     * @return mixed
     */
     // abstract function descrpt ($cipherText, $logger = null)
    abstract function decrypt($cipherText, $logger = null);

    /**
     * @param mixed $plainText
     * @return mixed
     */
    // abstract encrypt($plainText) 
    abstract function encrypt($plainText);

    /**
     * @return string
     */
     // 具体实现的 getCipherKey
    public function getCipherKey()
    {
        return $this->cipherKey;
    }

    /**
     * @param string $cipherKey
     */
    public function setCipherKey($cipherKey)
    {
        $this->cipherKey = $cipherKey;
    }

    public function pkcs5Pad($text, $blockSize) {
        $pad = $blockSize - (strlen($text) % $blockSize);
        return $text . str_repeat(chr($pad), $pad);
    }

    public function unPadPKCS7($data, $blockSize) {
        $length = strlen($data);
        if ($length > 0) {
            $first = substr($data, -1);

            if (ord($first) <= $blockSize) {
                for ($i = $length - 2; $i > 0; $i--)
                    if (ord($data [$i] != $first))
                        break;

                return substr($data, 0, $i + 1);
            }
        }
        return $data;
    }

    public function isBlank($word) {
        if (($word == null) || ($word == false))
            return true;
        else
            return false;
    }

    protected function tryToJsonDecode($value) {
        $result = json_decode($value);

        if ($result === null) {
            return $value;
        } else {
            return $result;
        }
    }
}
