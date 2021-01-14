<?php
namespace Magestore\POS\Controller\Adminhtml\Pos;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magestore\POS\Model\ResourceModel\Pos\Collection;
use Magestore\POS\Model\ResourceModel\Pos\CollectionFactory;


abstract class AbstractMassAction extends \Magento\Backend\App\Action
{
    protected $redirectUrl = '*/pos/index';

    protected $filter;

    protected $collectionFactory;

    public function __construct(Context $context, Filter $filter, CollectionFactory $collectionFactory)
    {
        parent::__construct($context);
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return $this
     */
    public function execute()
    {
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            return $this->massAction($collection);
        } catch (\Exception $e) {

            $this->messageManager->addError($e->getMessage());
            var_dump($e->getMessage());
            die;
            /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            return $resultRedirect->setPath($this->redirectUrl);
        }
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magestore_POS::pos_manage');
    }
    protected function getComponentRefererUrl()
    {
        return '*/pos/index';
    }
    abstract protected function massAction(Collection $collection);
}
