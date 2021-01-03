<?php


namespace Magestore\POS\Controller\Adminhtml\Pos;


use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\ResponseInterface;

class Save extends \Magento\Framework\App\Action\Action implements HttpPostActionInterface
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
        $pos_id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create(\Magestore\POS\Model\Pos::class);
        if($pos_id){
            $model->load($pos_id);
        }

        $dateTime = date("d-m-Y");


        //edit
        if($model->getData()){

            $data = $this->getRequest()->getPostValue();
            $model->setData($data);
            $model->setData('updated_at', $dateTime);
        }
        //add new
        else {
            $model = $this->_objectManager->create(\Magestore\POS\Model\PosFactory::class)->create();
            $data = $this->getRequest()->getPostValue();
            unset($data['id']);
            $model->setData($data);
            $model->setData('updated_at', $dateTime);
            $model->setData('created_at', $dateTime);

        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if($model->getData()) {
            $model->save();
            $resultRedirect->setPath('*/pos/index');
            $this->messageManager->addSuccess(__('Pos was successfully saved'));

        }else if($this->getRequest()->getParam('back') == 'edit'){

            $resultRedirect->setPath('*/pos/edit', ['id' => $this->getRequest()->getParam('id')]);
        }

        return $resultRedirect;
    }

    /**
     * @return int
     */
    public function getMaxId(){
        $collection = $this->_objectManager->create(\Magestore\POS\Model\ResourceModel\Pos\Collection::class);
        $id = 0;
        foreach ($collection as $item ){
            if($id < $item->getData('id')){
                $id = $item->getData('id');
            }
        }
        return $id;
    }
}
