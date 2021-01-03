<?php
/**
 * Location
 *
 * @copyright Copyright © 2020 Staempfli AG. All rights reserved.
 * @author    juan.alonso@staempfli.com
 */

namespace Magestore\POS\Ui\Component\Listing\Column;

use Magento\Framework\Data\OptionSourceInterface;

class Location implements OptionSourceInterface
{
    public function toOptionArray()
    {
        return [
            ['label' => __('Hà Đông'), 'value' => 1],
            ['label' => __('Hà Tây'), 'value' => 2],
            ['label' => __('Hà Nội'), 'value' => 3]
        ];
    }
}
