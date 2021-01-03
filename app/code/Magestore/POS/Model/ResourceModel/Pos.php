<?php


namespace Magestore\POS\Model\ResourceModel;


class Pos extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('POS_pos', 'id');
    }
}
