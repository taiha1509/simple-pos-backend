<?php


namespace Magestore\POS\Model;


use Magento\Framework\App\ObjectManager;

class OrderRepository implements \Magestore\POS\Api\OrderRepositoryInterface
{
    protected $searchResultsInterface;

    protected $orderCollectionFactory;

    protected $staffInterface;

    protected $requestInterface;

    protected $orderHelperFactory;

    protected $orderFactory;

    protected $itemsOrderInterfaceFactory;

    protected $productFactory;

    protected $productCollectionFactory;

    protected $arrayItemsOrderInterfaceFactory;



    /**
     * OrderRepository constructor.
     * @param \Magento\Framework\Api\SearchResultsInterface $searchResultsInterface
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     * @param \Magestore\POS\Api\Data\StaffInterface $staffInterface
     * @param \Magento\Framework\App\RequestInterface $requestInterface
     * @param \Magestore\POS\Helper\Order\DataFactory $orderHelperFactory
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \Magestore\POS\Api\Data\ItemsOrderInterfaceFactory $itemsOrderInterfaceFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magestore\POS\Api\Data\ArrayItemsOrderInterfaceFactory $arrayItemsOrderInterfaceFactory
     */
    public function __construct(
        \Magento\Framework\Api\SearchResultsInterface $searchResultsInterface,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magestore\POS\Api\Data\StaffInterface $staffInterface,
        \Magento\Framework\App\RequestInterface $requestInterface,
        \Magestore\POS\Helper\Order\DataFactory $orderHelperFactory,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magestore\POS\Api\Data\ItemsOrderInterfaceFactory $itemsOrderInterfaceFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magestore\POS\Api\Data\ArrayItemsOrderInterfaceFactory $arrayItemsOrderInterfaceFactory
    )
    {
        $this->searchResultsInterface = $searchResultsInterface;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->requestInterface = $requestInterface;
        $this->staffInterface = $staffInterface;
        $this->orderHelperFactory = $orderHelperFactory;
        $this->orderFactory = $orderFactory;
        $this->itemsOrderInterfaceFactory = $itemsOrderInterfaceFactory;
        $this->productFactory = $productFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->arrayItemsOrderInterfaceFactory = $arrayItemsOrderInterfaceFactory;
    }

    /**
     * @inheritDoc
     */
    public function getAllByPosId($pos_id)
    {
        $token = $this->requestInterface->getHeader('Authorization');
        $id = $this->requestInterface->getHeader('Id');

        // if unAuthorize -> return for object null
        if(!$this->staffInterface->authorize($id, $token)){
            return $this->searchResultsInterface;
        }

        $orderCollection = $this->orderCollectionFactory->create();
        $orderCollection->addFieldToFilter('pos_id', array('eq', $pos_id));

        $this->searchResultsInterface->setTotalCount($orderCollection->getSize());
        $this->searchResultsInterface->setItems($orderCollection->getData());
        return $this->searchResultsInterface;

    }

    /**
     * @inheritDoc
     */
    public function getAdditionalInfo($list_id){
        $product = $this->productFactory->create();
        $productCollection = $this->productCollectionFactory->create();
        $orderCollection = $this->orderCollectionFactory->create();
        $item_info = array();
        $itemOrder = $this->itemsOrderInterfaceFactory->create();
        $orderCollection->addFieldToFilter('entity_id', array('in', $list_id));

        $ids = array();

        foreach($orderCollection as $item){
            $itemArray = $item->getAllItems();
            foreach($itemArray as $element){
                array_push($ids, $element->getItemId());
            }
        }
        $productCollection->addAttributeToSelect('*');
        $productCollection->addFieldToFilter('entity_id', array('in', $ids));

        $result = array();

//        $arrayItemsOrderInterface = $this->arrayItemsOrderInterfaceFactory->create();

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $arrayItemsOrderInterface = $objectManager->create(\Magestore\POS\Api\Data\ArrayItemsOrderInterface::class);

        foreach($orderCollection as $item){
            $itemArray = $item->getAllItems();
            foreach($itemArray as $element){
                $id = $element->getItemId();
                foreach ($productCollection as $pro){
                    if($pro->getId() == $id){
                        $itemOrder->setId($element->getItemId());
                        $itemOrder->setQty($element->getQtyOrdered());
                        $itemOrder->setPrice($element->getPrice());
                        $itemOrder->setName($pro->getName());
                        $itemOrder->setSku($pro->getSku());
                        array_push($item_info, $itemOrder);
//                        return $item_info;
                    }
                }

            }
            $arrayItemsOrderInterface->setItems($item_info);
            array_push($result, $arrayItemsOrderInterface);
//            $arrayItemsOrderInterface->clearData();
            $item_info=array();
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function createOrder($data, $pos_id)
    {
//        [
//            "currency_id" : "USD",
//            "email" : "rogers@trueplus.vn",
//            "shipping_address" =>[
//        "firstname"	   => "jhon",
//        'lastname'	   => 'Deo',
//        'street' => 'xxxxx',
//        'city' => 'xxxxx',
//        'country_id' => 'US',
//        'region' => 'Idaho',
//        'postcode' => '43244',
//        'region_id' => '1',
//        'telephone' => '52332',
//        'fax' => '32423',
//        'save_in_address_book' => 1
//    ],
//            'items'=> [
//        ['product_id'=>'4','qty'=>1, 'price' => 34]
//    ]
//]
//        var_dump($data);
//        var_dump($pos_id);
//        die;
//        $order = $this->requestInterface->getPostValue();
        $orderHelper = $this->orderHelperFactory->create();
        $order =[
            'currency_id'  => 'USD',
            'email'        => 'rogers@trueplus.vn', //buyer email id
            'shipping_address' =>[
                'firstname'	   => 'jhon', //address Details
                'lastname'	   => 'Deo',
                'street' => 'xxxxx',
                'city' => 'xxxxx',
                'country_id' => 'US',
                'region' => 'Idaho',
                'postcode' => '43244',
                'region_id' => '1',
                'telephone' => '52332',
                'fax' => '32423',
                'save_in_address_book' => 1
            ],
            'items'=> [ //array of product which order you want to create
                ['product_id'=>'2047','qty'=>1, 'price' => 120],
                ['product_id'=>'4','qty'=>1, 'price' => 34]
            ]
        ];


        return $orderHelper->createMageOrder($data, $pos_id);
    }
}
