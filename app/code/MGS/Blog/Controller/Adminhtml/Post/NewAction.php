<?php

namespace MGS\Blog\Controller\Adminhtml\Post;

class NewAction extends \MGS\Blog\Controller\Adminhtml\Blog
{
    public function execute()
    {
        $this->_forward('edit');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('MGS_Blog::edit_post');
    }
}
