<?php
/**
 * Copyright © 2015 Inchoo d.o.o.
 * created by Zoran Salamun(zoran.salamun@inchoo.net)
 */
namespace MGZJ\Helloworld\Controller\Test;
class Test extends \Magento\Framework\App\Action\Action
{
    /**
     * Listing all images in gallery
     *  -@param gallery id
     */
    public function execute()
    {
//        $resource = $this->_objectManager->create('\Magento\Framework\App\ResourceConnection');
//        $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION); 
//        $values = $connection->fetchAll('select * from `table` ');
       var_dump("I'm Test Action");
       exit();
    }
}