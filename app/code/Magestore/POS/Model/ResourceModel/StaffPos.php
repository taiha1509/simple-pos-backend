<?php


namespace Magestore\POS\Model\ResourceModel;


class StaffPos extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('POS_staff_pos','id');
    }
}
