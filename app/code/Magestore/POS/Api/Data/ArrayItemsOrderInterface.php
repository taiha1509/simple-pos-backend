<?php


namespace Magestore\POS\Api\Data;


interface ArrayItemsOrderInterface
{
    /**
     * @param \Magestore\POS\Api\Data\ItemsOrderInterface[] $data
     * @return void
     */
    public function setData($data);

    /**
     * @return array
     */
    public function getData();

    /**
     * @return void
     */
    public function clearData();
}


