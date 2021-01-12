<?php


namespace Magestore\POS\Model;


use Magento\Framework\Api\SortOrder;
use Magestore\POS\Api\Data\ProductResultsInterface;
use Magestore\POS\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Type as ProductType;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableType;

class ProductRepository implements ProductRepositoryInterface
{

    protected $productCollectionFactory;

    protected $searchResultsFactory;

    protected $staffInterfaceFactory;

    protected $requestInterface;

    protected $productResultsInterfaceFactory;

    protected $stockFactory;

    protected $configurable;

    protected $getSalableQuantityDataBySku;

    protected $productFactory;


    public function __construct(
        \Magento\Catalog\Api\Data\ProductSearchResultsInterfaceFactory $searchResultsFactory,
        \Magestore\POS\Api\Data\StaffInterface $staffInterfaceFactory,
        \Magento\Framework\App\RequestInterface $requestInterface,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magestore\POS\Api\Data\ProductResultsInterfaceFactory $productResultsInterfaceFactory,
        \Magestore\POS\Model\StockFactory $stockFactory,
        \Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable $configurable,
        \Magento\InventorySalesAdminUi\Model\GetSalableQuantityDataBySku $getSalableQuantityDataBySku,
        \Magento\Catalog\Model\ProductFactory $productFactory
    )
    {
        $this->searchResultsFactory = $searchResultsFactory;
        $this->staffInterfaceFactory = $staffInterfaceFactory;
        $this->requestInterface = $requestInterface;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productResultsInterfaceFactory = $productResultsInterfaceFactory;
        $this->stockFactory = $stockFactory;
        $this->configurable = $configurable;
        $this->getSalableQuantityDataBySku = $getSalableQuantityDataBySku;
        $this->productFactory = $productFactory;
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

        var_dump($searchCriteria);
        die;

        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType();
                $collection->addAttributeToSelect('*')
                    ->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());

        $searchResult->setTotalCount($this->getTotalCountDisplayedProduct($searchCriteria));
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
    public function getTotalCountDisplayedProduct($searchCriteria){
        $productCollection = $this->productCollectionFactory->create();
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType();
                $productCollection->addAttributeToSelect('*')
                    ->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
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
            $parent_id = $this->configurable->getParentIdsByChild($product_id);
            $productModel = $this->productFactory->create();
            $productModel->load($product_id);
            if($productModel->getTypeId() == ProductType::TYPE_SIMPLE){
//                $product->setParentId($product_id);
                $sableQty = $this->getSalableQuantityDataBySku->execute($productModel->getSku());
                $qty = $sableQty[0]['qty'];
                if(isset($parent_id[0])) {
                    $product->setParentId($parent_id[0]);
                }else{
                    $product->setParentId(($product_id));
                }
            }elseif ($productModel->getTypeId() == ConfigurableType::TYPE_CODE){
                $qty = 0;
                $product->setParentId($product_id);
            }
//            $qty = $stock->getStockQty($product_id);

            $product->setQty($qty);

            array_push($result, $product);
        }

        return $result;
    }

}
