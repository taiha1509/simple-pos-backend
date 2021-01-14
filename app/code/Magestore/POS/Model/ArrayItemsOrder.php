<?php


namespace Magestore\POS\Model;


class ArrayItemsOrder implements \Magestore\POS\Api\Data\ArrayItemsOrderInterface
{

    protected $data;

    /**
     * @inheritDoc
     */
    public function setData($data)
    {
        array_push($this->data, $data);
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function clearData()
    {
        $this->data = array();
    }
}
