<?php


namespace Magestore\POS\Model;


class OrderRepository implements \Magestore\POS\Api\OrderRepositoryInterface
{
    protected $searchResultsInterface;

    protected $orderCollectionFactory;

    protected $staffInterface;

    protected $requestInterface;

    protected $orderHelperFactory;

    protected $orderFactory;


    /**
     * OrderRepository constructor.
     * @param \Magento\Framework\Api\SearchResultsInterface $searchResultsInterface
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     * @param \Magestore\POS\Api\Data\StaffInterface $staffInterface
     * @param \Magento\Framework\App\RequestInterface $requestInterface
     * @param \Magestore\POS\Helper\Order\DataFactory $orderHelperFactory
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     */
    public function __construct(
        \Magento\Framework\Api\SearchResultsInterface $searchResultsInterface,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magestore\POS\Api\Data\StaffInterface $staffInterface,
        \Magento\Framework\App\RequestInterface $requestInterface,
        \Magestore\POS\Helper\Order\DataFactory $orderHelperFactory,
        \Magento\Sales\Model\OrderFactory $orderFactory
    )
    {
        $this->searchResultsInterface = $searchResultsInterface;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->requestInterface = $requestInterface;
        $this->staffInterface = $staffInterface;
        $this->orderHelperFactory = $orderHelperFactory;
        $this->orderFactory = $orderFactory;
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
