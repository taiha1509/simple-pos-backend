<?php


namespace Magestore\POS\Helper\Authorization\Staff;

class Auth
{
    protected $_encryptorInterface;

    /**
         * @param \Magento\Framework\Encryption\EncryptorInterface $encryptorInterface
     */
    public function __construct(
        \Magento\Framework\Encryption\EncryptorInterface $encryptorInterface
    )
    {
        $this->_encryptorInterface = $encryptorInterface;
    }

    /**
     * @param string $password
     * @return string
     */
    public function getHashPassword($password){
        $hash = $this->_encryptorInterface->getHash($password);
        return $hash;
    }

    /**
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function verifyPassword($password, $hash){
        return $this->_encryptorInterfaceFactory->validateHash($password, $hash);
    }

}
