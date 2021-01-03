<?php


namespace Magestore\POS\Model;


class Session extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init(\Magestore\POS\Model\ResourceModel\Session::class);
        parent::_construct();
    }

    public function beforeSave()
    {
        $dateTime = new \DateTime('now');
        //var_dump($dateTime->gmtDate());
        if (!$this->getId()) {
            $this->setCreated_at($dateTime);
        } else {
            $this->setUpdated_at($dateTime);
        }

        return parent::beforeSave();
    }


    /**
     * @param int $id
     * @return array
     */
    public function findByStaffId($id){
        $collection = $this->getCollection();
        $allSession = $collection->getData();
        $result = null;
        foreach ($allSession as $item){
            if($item['staff_id'] == $id){
                $result = $item;
                break;
            }
        }
        if($result)
            return $result;
        return null;
    }

}
