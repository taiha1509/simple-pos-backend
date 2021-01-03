<?php


namespace Magestore\POS\Ui\Component\Buttons\Pos;


class Save implements \Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface
{

    /**
     * @inheritDoc
     */
    public function getButtonData()
    {
        return [
            'label' => 'Save',
            'class' => 'save primary',
            'data_attribute' => array(
                'mage-init' => array('button' => array('event' => 'saveAndContinueEdit', 'target' => '#edit_form_pos'))
            ),
            'sort_order' => 80,
        ];
    }
}
