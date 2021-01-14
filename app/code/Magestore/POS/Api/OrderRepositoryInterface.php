<?php


namespace Magestore\POS\Api;


use Magento\Framework\Api\SearchResultsInterface;
use Magestore\POS\Api\Data\ArrayItemsOrderInterface;
use Magestore\POS\Api\Data\ItemsOrderInterface;

interface OrderRepositoryInterface
{

    /**
     * @param int $pos_id
     * @return SearchResultsInterface
     */
    public function getAllByPosId($pos_id);

    /**
     * @param mixed $data
     * @param int $pos_id
     * @return int
     */
    public function createOrder($data, $pos_id);

    /**
     * @param int[] $list_id
     * @return ArrayItemsOrderInterface[]
     */
    public function getAdditionalInfo($list_id);
}
