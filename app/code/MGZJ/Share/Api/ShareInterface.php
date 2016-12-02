<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MGZJ\Share\Api;

use League\CLImate\TerminalObject\Basic\Json;
use Magento\Backend\Block\Page;

interface ShareInterface
{
    /**
     * Returns share information
     *
     * @api
     * @param int $share_owner_id, int $share_id.
     * @return string share inform.
     */
    public function getShareByUser($share_owner_id);
    
    /**
     * Returns share information
     * @api
     * @param string $page Page
     * @param string $page_size page_size
     * @return string share inform by time-reverse order.
     * 
     */
    public function getShareAll($page,$page_size);
    
    /**
     * Create new share
     *
     * @api
     * @param string $share_owner_id id
     * @param string $title Share title.
     * @param string $content Share content.
     * @return int status.
     */
    public function insertShare($share_owner_id,$title,$content);
    
    /**
     * delete share
     *
     * @api
     * @param string $share_owner_id id
     * @param string $share_id share id
     * @return int status.
     */
    public function deleteShare($share_owner_id,$share_id);
    
    /**
     * update share
     *
     * @api
     * @param string $share_owner_id id
     * @param string $share_id share id
     * @param string $title title title
     * @param string $contnet content content
     * @return int status.
     */
    public function updateShare($share_owner_id,$share_id,$title,$content);
    
    /**
     * get comments
     *
     * @api
     * @param string $share_owner_id id
     * @param string $share_id share id
     * @return string $comment.
     */
    public function getCommentByShare($share_owner_id,$share_id);
    
    /**
     * insert comments
     *
     * @api
     * @param string $share_owner_id id
     * @param string $share_id share id
     * @param string $comment_owner_id comment_owner_id id
     * @param string $title title 
     * @param string $contnet content
     * @return string $comment.
     */
    public function insertCommentByShare($share_owner_id,$share_id,$comment_owner_id,$content);
    
    /**
     * delete comment
     *
     * @api
     * @param string $share_owner_id id
     * @param string $share_id share id
     * @param string $comment_owner_id comment_owner_id id
     * @param string $comment_id comment_id
     * @return string $comment
     */
    public function deleteCommentByShare($share_owner_id,$share_id,$comment_owner_id,$comment_id);
    
    /**
     * get share detail
     *
     * @api
     * @param string $share_owner_id id
     * @param string $share_id share id
     * @return string $share.
     */
    public function getShareDetail($share_owner_id,$share_id);
    
    /**
     * get message
     *
     * @api
     * @param string $message_owner_id id
     * @return string $message.
     */
    public function getMessageByUser($message_owner_id);
    
    /**
     * create message
     *
     * @api
     * @param string $message_owner_id id
     * @param string $share_owner_id share id
     * @param string $share_id comment_owner_id id
     * @param string $comment_owner_id title
     * @param string $comment_id content
     * @return string $comment.
     */
    public function createCommentMessageByUser($message_owner_id,$share_owner_id,$share_id,$comment_owner_id,$comment_id);
    
    
    /**
     * delete message
     *
     * @api
     * @param string $message_owner_id id
     * @param string $message_id  id
     * @return string $message
     */
    public function deleteMessageByShare($message_owner_id,$message_id);
    
    /**
     * setReadStatus message
     *
     * @api
     * @param string $message_owner_id id
     * @param string $message_id  id
     * @return string $message
     */
    public function setMessageReadStatus($message_owner_id,$message_id);
    
    /**
     * get collection
     *
     * @api
     * @param string $collection_owner_id id
     * @return string $message
     */
    public function getShareCollection($collection_owner_id);
    
    /**
     * add collection
     *
     * @api
     * @param string $collection_owner_id id
     * @param string $share_owner_id id
     * @param string $share_id id
     * @return string $message
     */
    public function addShareCollection($collection_owner_id,$share_owner_id,$share_id);
    
    /**
     * delete collection
     *
     * @api
     * @param string $collection_owner_id id
     * @param string $share_owner_id id
     * @param string $share_id id
     * @return string $message
     */
    public function deleteShareCollection($collection_owner_id,$share_owner_id,$share_id);
}
