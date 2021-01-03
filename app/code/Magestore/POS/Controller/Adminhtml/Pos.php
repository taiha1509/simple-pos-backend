<?php

/**
 * Webpos
 *
 * @copyright Copyright © 2020 Staempfli AG. All rights reserved.
 * @author    juan.alonso@staempfli.com
 */

namespace Magestore\POS\Controller\Adminhtml;


use Magento\Framework\App\Action\Action;

abstract class Pos extends Action
{
    /**
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(\Magento\Backend\App\Action\Context $context)
    {
        parent::__construct($context);
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magestore_POS::pos_manage');
    }

    public function execute()
    {
        // TODO: Implement execute() method.
    }
}
