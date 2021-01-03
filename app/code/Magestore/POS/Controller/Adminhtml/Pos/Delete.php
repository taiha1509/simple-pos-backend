<?php


namespace Magestore\POS\Controller\Adminhtml\Pos;


use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;

class Delete extends \Magento\Framework\App\Action\Action
{

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory

    ) {
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
    /**
     * @inheritDoc
     */
    public function execute()
    {
        $pos_id = $this->getRequest()->getParam('id');
        $pos = $this->_objectManager->create(\Magestore\POS\Model\Pos::class);
        $pos->load($pos_id);
        $pos->delete();
        $this->messageManager->addSuccessMessage('deleted a pos');
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('*/pos/index');
        return $resultRedirect;

    }
}
