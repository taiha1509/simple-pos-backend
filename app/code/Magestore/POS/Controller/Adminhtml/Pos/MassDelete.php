<?php
namespace Magestore\POS\Controller\Adminhtml\Pos;
use Magento\Backend\App\Action\Context;
use Magestore\POS\Model\ResourceModel\Pos\CollectionFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\Controller\ResultFactory;
use Magestore\POS\Model\ResourceModel\Pos\Collection;

/**
 * Class MassDelete
 * @package Magestore\POS\Controller\Adminhtml\Pos
 */
class MassDelete extends AbstractMassAction
{

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        parent::__construct($context, $filter, $collectionFactory);
    }


    /**
     * @param Collection $collection
     * @return \Magento\Framework\Controller\ResultInterface
     */
    protected function massAction(Collection $collection)
    {
        $count = 0;
        foreach ($collection as $pos) {
            $pos->delete();
            $count ++ ;
        }
        if($count > 0){
            $this->messageManager->addSuccess('You have deleted %1 pos', $count);
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath($this->getComponentRefererUrl());

        return $resultRedirect;
    }
}
