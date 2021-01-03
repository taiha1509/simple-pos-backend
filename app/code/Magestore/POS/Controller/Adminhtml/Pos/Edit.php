<?php
/**
 * Edit
 *
 * @copyright Copyright © 2020 Staempfli AG. All rights reserved.
 * @author    juan.alonso@staempfli.com
 */

namespace Magestore\POS\Controller\Adminhtml\Pos;

/***
 * Class Edit
 * @package Magestore\Product\Controller\Adminhtml\Staff
 */
class Edit extends \Magestore\POS\Controller\Adminhtml\Pos
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    protected $modelPos;
    protected $registry;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magestore\POS\Model\PosFactory $modelPos
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magestore\POS\Model\PosFactory $modelPos,
        \Magento\Framework\Registry $registry
    )
    {
        $this->registry = $registry;
        $this->modelPos = $modelPos;
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * @return $this|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $resultRedirect = $this->resultRedirectFactory->create();
        $model = $this->modelPos->create();
        if ($id) {
            $model = $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This POS no longer exists.'));
                return $resultRedirect->setPath('posmanagement/pos/', ['_current' => true]);
            }
        }

        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        $this->registry->register('current_pos', $model);

        $resultPage = $this->_resultPageFactory->create();

        if (!$model->getId()) {
            $pageTitle = __('New POS');
        } else {
            $pageTitle = __('Edit %1', $model->getName());
        }

        $resultPage->getConfig()->getTitle()->prepend($pageTitle);
        return $resultPage;
    }
}
