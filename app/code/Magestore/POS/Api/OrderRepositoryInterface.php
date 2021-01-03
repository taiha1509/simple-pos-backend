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
}
