<?php


namespace Magestore\POS\Controller\Adminhtml\Pos;


use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Framework\App\Action\Action
{

    protected $_resultFactory;

    public function __construct(Context $context,
        PageFactory $pageFactory
    )
    {
        $this->_resultFactory = $pageFactory;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $rs = $this->_resultFactory->create();
        return $rs;
    }
}
