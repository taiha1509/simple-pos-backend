<?php


namespace Magestore\POS\Model\ResourceModel\Session;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public function _construct(){
        parent::_construct();
        $this->_init('Magestore\POS\Model\Session', 'Magestore\POS\Model\ResourceModel\Session');
    }
}
