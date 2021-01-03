<?php


namespace Magestore\POS\Model\ResourceModel\Staff;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public function _construct()
    {
        $this->_init('\Magestore\POS\Model\Staff', '\Magestore\POS\Model\ResourceModel\Staff');
        parent::_construct();
    }

    protected function _beforeLoad()
    {
        return parent::_beforeLoad();
    }
}
