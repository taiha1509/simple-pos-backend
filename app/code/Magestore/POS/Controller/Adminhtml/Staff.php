<?php


namespace Magestore\POS\Controller\Adminhtml;


use Magento\Framework\App\ResponseInterface;

abstract class Staff extends \Magento\Backend\App\Action
{

    /**
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(\Magento\Backend\App\Action\Context $context)
    {
        parent::__construct($context);
    }
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magestore_POS::staff_manage');
    }
}
