<?php
/**
 * Copyright © 2015 Inchoo d.o.o.
 * created by Zoran Salamun(zoran.salamun@inchoo.net)
 */
namespace MGZJ\Share\Controller\Test;
class Test extends \Magento\Framework\App\Action\Action
{
    /**
     * Listing all images in gallery
     *  -@param gallery id
     */
    public function execute()
    {
       var_dump("I'm Test Action");
       exit();
    }
}