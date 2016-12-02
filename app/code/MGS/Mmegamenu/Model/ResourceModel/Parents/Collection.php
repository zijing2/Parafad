<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MGS\Mmegamenu\Model\ResourceModel\Parents;

/**
 * Mmegamenu resource model collection
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Init resource collection
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('MGS\Mmegamenu\Model\Parents', 'MGS\Mmegamenu\Model\ResourceModel\Parents');
    }
}
