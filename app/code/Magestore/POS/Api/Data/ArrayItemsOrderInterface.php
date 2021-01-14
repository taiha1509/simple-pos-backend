<?php


namespace Magestore\POS\Api\Data;


interface ArrayItemsOrderInterface
{
    /**
     * @param \Magestore\POS\Api\Data\ItemsOrderInterface[] $data
     * @return void
     */
    public function setItems($data);

    /**
     * @return \Magestore\POS\Api\Data\ItemsOrderInterface[]
     */
    public function getItems();
}


