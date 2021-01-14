<?php


namespace Magestore\POS\Model\ResourceModel\Staff_Pos;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('\Magestore\POS\Model\StaffPos', '\Magestore\POS\Model\ResourceModel\StaffPos');
        parent::_construct();
    }

    /**
     * @param int $id
     * @return array
     */
    public function getPosByStaffId($id){
        $condition = 'main_table.pos_id = m2POS_pos.id';
        $this->getSelect()->join(
            $this->getTable('m2POS_pos'),
            $condition
        )->where('staff_id = '.$id);

        return $this->getData();
    }

    /**
     * check if a record is exist then return it else return null
     * @param array $data
     * @return mixed
     */
    public function checkPosOfStaff($data){

        foreach ($this->getData() as $item){
            if($data['staff_id'] == $item['staff_id'] && $data['pos_id'] == $item['pos_id']){
                return $item;
            }
        }
        return null;
    }

}
