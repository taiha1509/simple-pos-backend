<?php


namespace Magestore\POS\Api;

use Magestore\POS\Api\Data\ProductResultsInterface;

interface ProductRepositoryInterface
{
    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Catalog\Api\Data\ProductSearchResultsInterface
     */
    public function getListProductVisibleOnPos(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * @param int[] $id
     * @return ProductResultsInterface[]
     */
    public function getAdditionalInformation(array $id);

}
