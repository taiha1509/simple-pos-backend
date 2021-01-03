<?php


namespace Magestore\POS\Controller\Adminhtml\Staff;


class Save extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory

    ) {
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }


    /**
     * @inheritDoc
     */
    public function execute()
    {

        $staff_id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create(\Magestore\POS\Model\Staff::class);
        if($staff_id){
            $model->load($staff_id);
        }
        $dateTime = date("d-m-Y");
        //edit
        if($model){
            $data = $this->getRequest()->getPostValue();
            $model->setData($data);
            $model->setData('updated_at', $dateTime);
        }
        //add new
        else {
            $data = $this->getRequest()->getPostValue();
            $model->setData($data);
            $model->setData('updated_at', $dateTime);
            $model->setCreated_at($dateTime);
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();


        if($model) {
            // get hash password before save to database
            $model->setData('password', $model->getHashPassword($model->getData('password')));
            $model = $model->save();
            $resultRedirect->setPath('*/staff/index');
            $this->messageManager->addSuccess(__('Staff was successfully saved'));

        }else if($this->getRequest()->getParam('back') == 'edit'){

            $resultRedirect->setPath('*/staff/edit', ['id' => $this->getRequest()->getParam('id')]);
        }

        // set data to save list pos of staff
        $listPosId = $this->getRequest()->getPostValue('listPos');
        $listPosData = array();
        if($listPosId){
            foreach ($listPosId as $element){
                array_push($listPosData, ['staff_id' => $model->getId(), 'pos_id' => $element]);
            }
        }

        $this->saveListPosOfStaff($listPosData);

        return $resultRedirect;
    }

    /**
     * create or update relationship between pos and staff in POS_staff_pos table
     * $data : array(['staff_id' => 1, 'pos_id' => 1])
     * @param array $data
     * @return void
     */
    protected function saveListPosOfStaff($data){
        if($data){
            $collection = $this->_objectManager->create(\Magestore\POS\Model\ResourceModel\Staff_Pos\Collection::class);
            $model = $this->_objectManager->create(\Magestore\POS\Model\StaffPos::class);
            foreach ($data as $item){
                $temp = $collection->checkPosOfStaff($item);
                if($temp){
                    $model->setData($temp);
                    $model->delete();
                }else{

                    $model->setData($item);
                    $model->save();
                }
                unset($model['staff_id']);
                unset($model['pos_id']);
            }
        }
    }

}
