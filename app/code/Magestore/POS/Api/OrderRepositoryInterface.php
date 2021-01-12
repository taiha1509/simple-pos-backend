<?php


namespace Magestore\POS\Api;


use Magento\Framework\Api\SearchResultsInterface;

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
}
