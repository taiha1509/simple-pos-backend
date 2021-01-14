<?php


namespace Magestore\POS\Model;


use Magento\Framework\DataObject;

class ItemsOrder extends DataObject implements \Magestore\POS\Api\Data\ItemsOrderInterface
{
    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->getData('id');
    }

    /**
     * @inheritDoc
     */
    public function setId($id)
    {
        $this->setData('id',$id);
    }

    /**
     * @inheritDoc
     */
    public function getQty()
    {
        return $this->getData('qty');
    }

    /**
     * @inheritDoc
     */
    public function setQty($qty)
    {
        $this->setData('qty', $qty);
    }

    /**
     * @inheritDoc
     */
    public function getPrice()
    {
        return $this->getData('price');
    }

    /**
     * @inheritDoc
     */
    public function setPrice($price)
    {
        $this->setData('price', $price);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->getData('name');
    }

    /**
     * @inheritDoc
     */
    public function setName($name)
    {
        $this->setData('name', $name);
    }

    /**
     * @inheritDoc
     */
    public function getSku()
    {
        return $this->getData('sku');
    }

    /**
     * @inheritDoc
     */
    public function setSku($sku)
    {
        $this->setData('sku', $sku);
    }
}
