<?php


namespace Magestore\POS\Api;


use Magento\Customer\Api\Data\CustomerSearchResultsInterface;

interface CustomerRepositoryInterface
{
    /**
     * @return CustomerSearchResultsInterface
     */
    public function getAll();
}
