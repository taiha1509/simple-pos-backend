<?php


namespace Magestore\POS\Block\Adminhtml\Staff\Edit;

//tab staff information

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     *
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('staff_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Staff Information'));
    }


    /**
     * @return $this
     * @throws \Exception
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _beforeToHtml()
    {

        $this->addTab(
            'staff_form',
            [
                'label' => __('General'),
                'title' => __('General'),
                'content' => $this->getLayout()->createBlock('Magestore\POS\Block\Adminhtml\Staff\Edit\Tab\FormStaff')
                    ->toHtml(),
                'active' => true
            ]
        );


        $this->addTab(
            'product_section',
            [
                'label' => __('Pos List'),
                'title' => __('Pos List'),
                'class' => 'ajax',
                'url' => $this->getUrl('*/*/pos', array('_current' => true, 'id' => $this->getRequest()->getParam('id')))
            ]
        );

        return parent::_beforeToHtml();
    }

}
