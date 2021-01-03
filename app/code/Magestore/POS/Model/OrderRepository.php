<?php


namespace Magestore\POS\Model;


class OrderRepository implements \Magestore\POS\Api\OrderRepositoryInterface
{
    protected $searchResultsInterface;

    protected $orderCollectionFactory;

    protected $staffInterface;

    protected $requestInterface;


    public function __construct(
        \Magento\Framework\Api\SearchResultsInterface $searchResultsInterface,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magestore\POS\Api\Data\StaffInterface $staffInterface,
        \Magento\Framework\App\RequestInterface $requestInterface
    )
    {
        $this->searchResultsInterface = $searchResultsInterface;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->requestInterface = $requestInterface;
        $this->staffInterface = $staffInterface;
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
//        $orderCollection->addFieldToFilter('pos_id', array('eq', $pos_id));

        $this->searchResultsInterface->setTotalCount($orderCollection->getSize());
        $this->searchResultsInterface->setItems($orderCollection->getData());
        return $this->searchResultsInterface;

    }
}
