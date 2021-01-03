<?php


namespace Magestore\POS\Api;

interface ProductRepositoryInterface
{
    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Catalog\Api\Data\ProductSearchResultsInterface
     */
    public function getListProductVisibleOnPos(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
