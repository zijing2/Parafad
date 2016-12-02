<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MGZJ\Helloworld\Api;

interface HelloworldInterface
{
    /**
     * Returns greeting message to user
     *
     * @api
     * @param string $nameId.
     * @return string Greeting message with users name.
     */
    public function name($name);
}
