<?php


namespace Magestore\POS\Model;


use Magento\Framework\Api\SortOrder;
use Magestore\POS\Api\Data\ProductResultsInterface;
use Magestore\POS\Api\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{

    protected $productCollectionFactory;

    protected $searchResultsFactory;

    protected $staffInterfaceFactory;

    protected $requestInterface;

    protected $productResultsInterfaceFactory;

    protected $stockFactory;

    protected $configurable;


    public function __construct(
        \Magento\Catalog\Api\Data\ProductSearchResultsInterfaceFactory $searchResultsFactory,
        \Magestore\POS\Api\Data\StaffInterface $staffInterfaceFactory,
        \Magento\Framework\App\RequestInterface $requestInterface,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magestore\POS\Api\Data\ProductResultsInterfaceFactory $productResultsInterfaceFactory,
        \Magestore\POS\Model\StockFactory $stockFactory,
        \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $configurable
    )
    {
        $this->searchResultsFactory = $searchResultsFactory;
        $this->staffInterfaceFactory = $staffInterfaceFactory;
        $this->requestInterface = $requestInterface;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productResultsInterfaceFactory = $productResultsInterfaceFactory;
        $this->stockFactory = $stockFactory;
        $this->configurable = $configurable;
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

        $searchResult->setTotalCount($this->getTotalCountDisplayedProduct());

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
//        $searchResult->setTotalCount($collection->getSize());
        return $searchResult;

    }


    /**
     * @return int
     */
    public function getTotalCountDisplayedProduct(){
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToFilter('isVisibleOnPOS', array('eq' => 1));
        $count = 0;
        foreach ($productCollection as $item) {
            $parent_id = $this->configurable->getParentIdsByChild($item->getId());
            if(!isset($parent_id[0]))
                $count ++;
        }
        return $count;
    }

    /**
     * @inheritDoc
     */
    public function getAdditionalInformation(array $id)
    {
        $result = array();
        $stock = $this->stockFactory->create();
        foreach ($id as $product_id){
            $product = $this->productResultsInterfaceFactory->create();
            $qty = $stock->getStockQty($product_id);
            $product->setQty($qty);
            $parent_id = $this->configurable->getParentIdsByChild($product_id);
            if(isset($parent_id[0]))
                $product->setParentId($parent_id[0]);
            else
                $product->setParentId($product_id);
            array_push($result, $product);
        }

        return $result;
    }



}
