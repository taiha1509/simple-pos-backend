<?php


namespace Magestore\POS\Block\Adminhtml\Staff\DataProvider\Form;


class Staff
{
   protected $_posFactory;

   protected $_staffPosFactory;

    /**
     * @param \Magestore\POS\Model\ResourceModel\Pos\CollectionFactory $posFactory
     * @param \Magestore\POS\Model\ResourceModel\Staff_Pos\CollectionFactory $staffPosFactory
     */
    public function __construct(
        \Magestore\POS\Model\ResourceModel\Pos\CollectionFactory $posFactory,
        \Magestore\POS\Model\ResourceModel\Staff_Pos\CollectionFactory $staffPosFactory
    )
    {
        $this->_posFactory = $posFactory;
        $this->_staffPosFactory = $staffPosFactory;
    }

    /**
     * get list pos of staff by id when edit staff
     * @param int $id
     *
     * @return array
     */
    public function getPosArray($id){
        $posArray = array();
        $staffPos = $this->_staffPosFactory->create();
        $data = $staffPos->getPosByStaffId($id);
        foreach ($data as $item){
            $temp = [ "value" => $item['pos_id'], "label" => $item['name']];
            array_push($posArray, $temp);
        }

        return $posArray;
    }


    /**
     * get list all of pos to display when create staff
     * @return array
     */
    public function getListCurrentPos(){
        $result = [];
        $pos = $this->_posFactory->create();
        foreach ($pos->getData() as $item) {
            $temp = ["value" => $item['id'], "label" => $item['name']];
            array_push($result, $temp);
        }
        return $result;
    }
}
