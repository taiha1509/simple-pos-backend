<?php


namespace Magestore\POS\Model;

use Magento\Customer\Api\Data\CustomerSearchResultsInterfaceFactory;
use Magento\Framework\App\RequestInterface;
class CustomerRepository implements \Magestore\POS\Api\CustomerRepositoryInterface
{
    protected $requestInterface;

    protected $searchResultFactory;

    protected $customerCollectionFactory;

    protected $staffFactory;

    protected $customerRepositoryInterface;

    protected $searchCriteria;

    /**
     * CustomerRepository constructor.
     * @param RequestInterface $requestInterface
     * @param CustomerSearchResultsInterfaceFactory $searchResultFactory
     * @param \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory
     * @param StaffFactory $staffFactory
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     */
    public function __construct(
        RequestInterface $requestInterface,
        CustomerSearchResultsInterfaceFactory $searchResultFactory,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory,
        StaffFactory $staffFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria

    )
    {
        $this->requestInterface = $requestInterface;
        $this->searchResultFactory = $searchResultFactory;
        $this->customerCollectionFactory = $customerCollectionFactory;
        $this->staffFactory = $staffFactory;
        $this->customerRepositoryInterface = $customerRepositoryInterface;
        $this->searchCriteria = $searchCriteria;
    }

    /**
     * @inheritDoc
     */
    public function getAll()
    {
        $staff = $this->staffFactory->create();
        $staff_id = $this->requestInterface->getHeader('Id');
        $token = $this->requestInterface->getHeader('Authorization');
        $searchResult = $this->searchResultFactory->create();
        if (!$staff->authorize($staff_id, $token)) {
            return $searchResult;
        }

//        $customerCollection = $this->customerCollectionFactory->create();
//        $searchResult->setTotalCount($customerCollection->getSize());
//        $searchResult->setItems($customerCollection->getItems());
//        return $searchResult;

        $searchResult = $this->customerRepositoryInterface->getList($this->searchCriteria);
        return $searchResult;

    }
}
