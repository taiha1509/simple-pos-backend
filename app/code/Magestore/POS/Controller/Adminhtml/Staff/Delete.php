<?php


namespace Magestore\POS\Controller\Adminhtml\Staff;


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
        $staff_id = $this->getRequest()->getParam('id');
        $staff = $this->_objectManager->create(\Magestore\POS\Model\Staff::class);
        $staff->load($staff_id);
        $staff->delete();
        $this->messageManager->addSuccessMessage('deleted a staff');
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('*/staff/index');
        return $resultRedirect;

    }
}
