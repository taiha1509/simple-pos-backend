<?php


namespace Magestore\POS\Model\ResourceModel;


class Staff extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
       $this->_init('POS_staff', 'id');
    }
}
