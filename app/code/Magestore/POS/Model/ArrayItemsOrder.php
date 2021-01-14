<?php


namespace Magestore\POS\Model;


use Magento\Framework\DataObject;

class ArrayItemsOrder extends DataObject implements  \Magestore\POS\Api\Data\ArrayItemsOrderInterface
{

    public $data = [];

    /**
     * @inheritDoc
     */
    public function setItems($data)
    {
        $this->setData('items', $data);
    }

    /**
     * @inheritDoc
     */
    public function getItems()
    {
        return $this->getData('items');
    }
}
