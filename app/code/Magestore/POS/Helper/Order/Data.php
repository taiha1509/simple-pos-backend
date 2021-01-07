<?php
namespace Magestore\POS\Helper\Order;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_storeManager;
    protected $_product;
    protected $cartRepositoryInterface;
    protected $cartManagementInterface;
    protected $customerFactory;
    protected $customerRepository;
    protected $order;
    protected $shippingRate;


    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\Product $product,
     * @param \Magento\Quote\Api\CartRepositoryInterface $cartRepositoryInterface,
     * @param \Magento\Quote\Api\CartManagementInterface $cartManagementInterface,
     * @param \Magento\Customer\Model\CustomerFactory $customerFactory,
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
     * @param \Magento\Sales\Model\Order $order
     * @param \Magento\Quote\Model\Quote\Address\Rate $shippingRate
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Product $product,
        \Magento\Quote\Api\CartRepositoryInterface $cartRepositoryInterface,
        \Magento\Quote\Api\CartManagementInterface $cartManagementInterface,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Sales\Model\Order $order,
        \Magento\Quote\Model\Quote\Address\Rate $shippingRate
    ) {
        $this->_storeManager = $storeManager;
        $this->_product = $product;
        $this->cartRepositoryInterface = $cartRepositoryInterface;
        $this->cartManagementInterface = $cartManagementInterface;
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->order = $order;
        $this->shippingRate = $shippingRate;
        parent::__construct($context);
    }

    /**
     * Create Order On Your Store
     *
     * @param array $orderData
     * @return array
     *
     */
    public function createMageOrder($orderData) {
        $store=$this->_storeManager->getStore();
        $websiteId = $this->_storeManager->getStore()->getWebsiteId();

        //save customer
        $customer=$this->customerFactory->create();
        $customer->setWebsiteId($websiteId);
        $customer->loadByEmail($orderData['email']);// load customet by email address

        if(!$customer->getEntityId()){
            //If not avilable then create this customer
            $customer->setWebsiteId($websiteId)
                ->setStore($store)
                ->setFirstname($orderData['shipping_address']['firstname'])
                ->setLastname($orderData['shipping_address']['lastname'])
                ->setEmail($orderData['email'])
                ->setPassword($orderData['email']);
            $customer->save();
        }
        $cartId = $this->cartManagementInterface->createEmptyCart(); //Create empty cart
        $quote = $this->cartRepositoryInterface->get($cartId); // load empty cart quote
        $quote->setStore($store);
        // if you have already buyer id then you can load customer directly

        $customer= $this->customerRepository->getById($customer->getEntityId());
        $quote->setCurrency();
        $quote->assignCustomer($customer); //Assign quote to customer

//        var_dump($quote->getCustomerIsGuest());
//        die;

        //add items in quote
        foreach($orderData['items'] as $item){
            $product=$this->_product->load($item['product_id']);
            $product->setPrice($item['price']);
            $quote->addProduct($product, intval($item['qty']));
        }

        //Set Address to quote
        $quote->getBillingAddress()->addData($orderData['shipping_address']);
        $quote->getShippingAddress()->addData($orderData['shipping_address']);

        // Collect Rates and Set Shipping & Payment Method
        $this->shippingRate
            ->setCode('freeshipping_freeshipping')
            ->getPrice(1);
        $shippingAddress=$quote->getShippingAddress();
        $shippingAddress->setCollectShippingRates(true)
            ->collectShippingRates()
            ->setShippingMethod('freeshipping_freeshipping'); //shipping method
        $quote->getShippingAddress()->addShippingRate($this->shippingRate);
        $quote->setPaymentMethod('checkmo'); //payment method
        $quote->setInventoryProcessed(false); //not effetc inventory
//        $quote->setReservedOrderId('000000001');
        // Set Sales Order Payment
        $quote->getPayment()->importData(['method' => 'checkmo']);
        $quote->save(); //Now Save quote and your quote is ready

        // Collect Totals
        $quote->collectTotals();

        // Create Order From Quote
        $quote = $this->cartRepositoryInterface->get($quote->getId());

        $orderId = $this->cartManagementInterface->placeOrder($quote->getId());
        $order = $this->order->load($orderId);

        $order->setEmailSent(0);
        $increment_id = $order->getRealOrderId();

        if($order->getEntityId()){
            $result['order_id'] = $order->getRealOrderId();
        }else{
            $result=['error'=>1,'msg'=>'Your custom message'];
        }
        return $result;
    }
}


