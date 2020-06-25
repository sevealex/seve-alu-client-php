<?php

namespace PayU\Alu;

class MerchantConfig
{
    /**
     * @var string
     */
    private $merchantCode;

    /**
     * @var string
     */
    private $secretKey;

    /**
     * @var string
     */
    private $platform;

    /**
     * @var boolean
     */
    private $sandbox = false;

    /**
     * @param string $merchantCode
     * @param string $secretKey
     * @param string $platform
     */
    public function __construct($merchantCode, $secretKey, $platform, $sandbox = false)
    {
        $this->merchantCode = $merchantCode;
        $this->secretKey = $secretKey;
        $this->platform = $platform;
        $this->sandbox = $sandbox;
    }

    /**
     * @return string
     */
    public function getMerchantCode()
    {
        return $this->merchantCode;
    }

    /**
     * @return string
     */
    public function getPlatform()
    {
        return strtolower($this->platform) . ($this->sandbox ? '_dev' : '');
    }

    /**
     * @return string
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }
}
