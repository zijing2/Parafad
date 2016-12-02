<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Used in creating options for Yes|No config value selection
 *
 */
namespace MGS\Mpanel\Model\Config\Source;

class Positiony implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
			['value' => 'top', 'label' => __('Top')],
			['value' => 'bottom', 'label' => __('Bottom')], 
			['value' => 'middle', 'label' => __('Middle')]
		];
    }
}
