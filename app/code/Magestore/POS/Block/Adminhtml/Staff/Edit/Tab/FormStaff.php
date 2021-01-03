<?php
namespace Magestore\POS\Block\Adminhtml\Staff\Edit\Tab;
/**
 * Class Form
 * tao cac truong trong form edit
 */
class FormStaff extends \Magento\Backend\Block\Widget\Form\Generic
    implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @param \Magento\Backend\Block\Template\Context $contextStaff
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        array $data = array()
    )
    {
        $this->_objectManager = $objectManager;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout() {
        $this->getLayout()->getBlock('page.title')->setPageTitle($this->getPageTitle());
    }


    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {

        $model = $this->_coreRegistry->registry('current_staff');

        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('page_');
        $fieldset = $form->addFieldset('base_fieldset', array('legend' => __('Staff Information')));

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array('name' => 'id'));
        }

        $fieldset->addField('name', 'text', array(
            'label'     => __('Name'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'name',
            'disabled' => false,
        ));

        $fieldset->addField('email', 'text', array(
            'label'     => __('Email'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'email',
            'disabled' => false,
        ));
//
//        $fieldset->addField('logo', 'image', array(
//            'label'     => __('Logo'),
//            'name'      => 'logo',
//            'disabled' => false,
//        ));

        $fieldset->addField('password', 'obscure', array(
            'label'     => __('Password'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'password',
            'disabled' => false,
        ));

//        $fieldset->addField('phone', 'text', array(
//            'label'     => __('Phone'),
//            'class'     => 'required-entry',
//            'required'  => true,
//            'name'      => 'phone',
//            'disabled' => false,
//        ));

        $fieldset->addField('status', 'select', array(
            'label'     => __('Status'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'status',
            'options' => array(
                1 => 'Enabled',
                2 => 'Disabled',
            ),
            'disabled' => false,
        ));

        $staffPos = $this->_objectManager->create(\Magestore\POS\Block\Adminhtml\Staff\DataProvider\Form\Staff::class);
        $listPosStaff = [];
//        if($model->getId()){
//            $listPosStaff = $staffPos->getPosArray($model->getId());
//        }else{
//            $listPosStaff = $staffPos->getListCurrentPos();
//        }

        $listPosStaff = $staffPos->getListCurrentPos();

        $fieldset->addField('listPos', 'multiselect', array(
            'label'     => __('Pos'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'listPos',
            'values'    => $listPosStaff,
            'disabled' => false,
        ));

        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * @return mixed
     */
    public function getStaff() {
        return $this->_coreRegistry->registry('current_staff');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getPageTitle() {
        return $this->getStaff()->getId() ? __("Edit Staff %1",
            $this->escapeHtml($this->getStaff()->getDisplayName())) : __('New Staff');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Staff Information');
    }


    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Staff Information');
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }


}
