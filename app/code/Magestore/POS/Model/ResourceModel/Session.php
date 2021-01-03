<?php


namespace Magestore\POS\Model\ResourceModel;


class Session extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('POS_session', 'id');
    }
}
