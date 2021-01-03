<?php


namespace Magestore\POS\Model;


use Magento\Framework\Api\SortOrder;
use Magestore\POS\Api\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{

    protected $productCollectionFactory;

    protected $searchResultsFactory;

    protected $staffInterfaceFactory;

    protected $requestInterface;

    public function __construct(
        \Magento\Catalog\Api\Data\ProductSearchResultsInterfaceFactory $searchResultsFactory,
        \Magestore\POS\Api\Data\StaffInterface $staffInterfaceFactory,
        \Magento\Framework\App\RequestInterface $requestInterface,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
    )
    {
        $this->searchResultsFactory = $searchResultsFactory;
        $this->staffInterfaceFactory = $staffInterfaceFactory;
        $this->requestInterface = $requestInterface;
        $this->productCollectionFactory = $productCollectionFactory;
    }

    /**
     * @inheritDoc
     */
    public function getListProductVisibleOnPos(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $searchResult = $this->searchResultsFactory->create();
        $token = $this->requestInterface->getHeader('Authorization');
        $id = $this->requestInterface->getHeader('Id');

        // if unauthorized then return object search result with null
        if(!$this->staffInterfaceFactory->authorize($id, $token)){
            return $searchResult;
        }

        $collection = $this->productCollectionFactory->create();

        //only get product having property isVisibleOnPOS = 1
        $collection->addAttributeToFilter('isVisibleOnPOS', array('eq' => 1));

        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType();
                $collection->addAttributeToSelect('*')
                    ->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());

        $sortOrdersData = $searchCriteria->getSortOrders();
        if ($sortOrdersData) {
            foreach ($sortOrdersData as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }

        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());

        return $searchResult;

    }
}
