<?php


namespace Magestore\POS\Controller\Adminhtml\Test;

use Firebase\JWT\JWT;
use Magento\Framework\App\ResponseInterface;

class Index extends \Magento\Framework\App\Action\Action
{

    /**
     * @inheritDoc
     */
    public function execute()
    {

        $key = "example_key";
        $payload = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" => 1356999524,
            "nbf" => 1357000000
        );

        $jwt = JWT::encode($payload, $key);
        $decoded = JWT::decode($jwt, $key, array('HS256'));
        $decoded_array = (array) $decoded;
        print_r($decoded_array);
        die;

        $data = $this->_objectManager->create(\Magestore\POS\Block\Adminhtml\Staff\DataProvider\Form\Staff::class);


//        $collection = $data->getPosArray($this->_objectManager);
    }
}
