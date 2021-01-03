<?php


namespace Magestore\POS\Model;


class StaffPos extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Magestore\POS\Model\ResourceModel\StaffPos::class);
        parent::_construct();
    }


}
