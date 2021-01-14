<?php
namespace Magestore\POS\Api\Data;


interface ItemsOrderInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return void
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getQty();

    /**
     * @param int $qty
     * @return void
     */
    public function setQty($qty);

    /**
     * @return float
     */
    public function getPrice();

    /**
     * @param float $price
     * @return void
     */
    public function setPrice($price);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return void
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getSku();

    /**
     * @param string $sku
     * @return void
     */
    public function setSku($sku);

    /**
     * @return \Magestore\POS\Api\Data\ItemsOrderInterface[]
     */
    public function getData();

    /**
     * @param \Magestore\POS\Api\Data\ItemsOrderInterface[]
     * @return void
     */
    public function setDate($data);

}
