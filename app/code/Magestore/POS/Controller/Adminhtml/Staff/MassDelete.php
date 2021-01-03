<?php
namespace Magestore\POS\Controller\Adminhtml\Staff;
use Magento\Backend\App\Action\Context;
use Magestore\POS\Model\ResourceModel\Staff\CollectionFactory;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\Controller\ResultFactory;
use Magestore\POS\Model\ResourceModel\Staff\Collection;

/**
 * Class MassDelete
 * @package Magestore\POS\Controller\Adminhtml\Staff
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
        var_dump(1);
        die;
        foreach ($collection as $staff) {
            $staff->delete();
            $count ++ ;
        }
        if($count > 0){
            $this->messageManager->addSuccess('You have deleted %1 staff', $count);
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath($this->getComponentRefererUrl());

        return $resultRedirect;
    }
}
