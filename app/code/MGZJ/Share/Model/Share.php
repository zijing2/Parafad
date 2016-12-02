<?php
namespace MGZJ\Share\Model;

use MGZJ\Share\Api\ShareInterface;
use Magento\Framework\Exception\InputException;

class Share implements ShareInterface
{
    
    protected $_resource;
    
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\App\ResourceConnection $resource,
        array $data = []
        ) {
            $this->_resource = $resource;
            //parent::__construct($context, $data);
    }
    
    protected function getConnection()
    {
        $connection = $this->_resource->getConnection('core_write');
        return $connection;
    }
    
    /**
     * Returns greeting message to user
     *
     * @api
     * @param string $name Users name.
     * @return string Greeting message with users name.
     */
    public function getShareByUser($share_owner_id) {
        //$array = [1,2,3,4,5];
        $table=$this->_resource->getTableName('mgzj_share');
        $shareArr = $this->getConnection()
                    ->fetchAll('SELECT S.share_owner_id,firstname,lastname,S.share_id,title,content,publish_time,SP.location,SP.picture_id,SL.from_customer_id as like_id
                                FROM mgzj_share S left join customer_entity C on S.share_owner_id=C.entity_id
                                                left join mgzj_share_picture SP on S.share_owner_id=SP.share_owner_id and S.share_id=SP.share_id
                                                left join mgzj_share_like SL on S.share_owner_id=SL.share_owner_id and S.share_id=SL.share_id
                                WHERE S.share_owner_id='.$share_owner_id);
       
        if(!empty($shareArr)){
            $formatter_shareArr = array();
            foreach ($shareArr as $k1 => $v1){
                foreach ($formatter_shareArr as $k2 => $v2){
                    
                    if($v1['share_owner_id']==$v2['share_owner_id']&&$v1['share_id']==$v2['share_id']){
                        if(!empty($v1['like_id'])){
                            if(empty($formatter_shareArr[$k2]['like_ids'])){
                                $formatter_shareArr[$k2]['like_ids'] = array();
                            }
                            if(!in_array($v1['like_id'], $formatter_shareArr[$k2]['like_ids'])){
                                $formatter_shareArr[$k2]['like_ids'][] = $v1['like_id'];
                            }
                        }
                        if(!empty($v1['picture_id'])){
                            if(empty($formatter_shareArr[$k2]['picture'])){
                                $formatter_shareArr[$k2]['picture'] = array();
                            }
                            $array_length = count($formatter_shareArr[$k2]['picture']);
                            if($array_length!=0){
                                $flag = true;
                                foreach ($formatter_shareArr[$k2]['picture'] as $key => $value){
                                    if($value['picture_id']==$v1['picture_id'] || $value['location']==$v1['location']){
                                        $flag = false;
                                        break;
                                    }
                                }
                                if($flag ==true){
                                    $formatter_shareArr[$k2]['picture'][$array_length]['picture_id'] = $v1['picture_id'];
                                    $formatter_shareArr[$k2]['picture'][$array_length]['location'] = $v1['location'];
                                }
                            }else{
                                $formatter_shareArr[$k2]['picture'][$array_length]['picture_id'] = $v1['picture_id'];
                                $formatter_shareArr[$k2]['picture'][$array_length]['location'] = $v1['location'];
                            }
                        } 
                        continue 2;
                    }
                }
                $formatter_shareArr[$k1] = $v1;
            }
        }
        
        foreach ($formatter_shareArr as $key => $value){
            unset($formatter_shareArr[$key]['like_id'],$formatter_shareArr[$key]['picture_id'],$formatter_shareArr[$key]['location']);
        }
        
        return $formatter_shareArr;
    }
    
    
    /**
     * Returns greeting message to user
     *
     * @api
     * @param string $page Page
     * @param string $page_size page_size
     * @return string Greeting message with users name.
     */
    public function getShareAll($page,$page_size) {
        $table=$this->_resource->getTableName('mgzj_share');
        $offset = $page_size*($page-1);
        $shareArr = $this->getConnection()
        ->fetchAll('SELECT S.share_owner_id,firstname,lastname,S.share_id,title,content,publish_time,SP.location,SP.picture_id,SL.from_customer_id as like_id
                                FROM mgzj_share S left join customer_entity C on S.share_owner_id=C.entity_id
                                                left join mgzj_share_picture SP on S.share_owner_id=SP.share_owner_id and S.share_id=SP.share_id
                                                left join mgzj_share_like SL on S.share_owner_id=SL.share_owner_id and S.share_id=SL.share_id
                                Order by S.publish_time desc');
         
        if(!empty($shareArr)){
            $formatter_shareArr = array();
            foreach ($shareArr as $k1 => $v1){
                foreach ($formatter_shareArr as $k2 => $v2){
    
                    if($v1['share_owner_id']==$v2['share_owner_id']&&$v1['share_id']==$v2['share_id']){
                        if(!empty($v1['like_id'])){
                            if(empty($formatter_shareArr[$k2]['like_ids'])){
                                $formatter_shareArr[$k2]['like_ids'] = array();
                            }
                            if(!in_array($v1['like_id'], $formatter_shareArr[$k2]['like_ids'])){
                                $formatter_shareArr[$k2]['like_ids'][] = $v1['like_id'];
                            }
                        }
                        if(!empty($v1['picture_id'])){
                            if(empty($formatter_shareArr[$k2]['picture'])){
                                $formatter_shareArr[$k2]['picture'] = array();
                            }
                            $array_length = count($formatter_shareArr[$k2]['picture']);
                            if($array_length!=0){
                                $flag = true;
                                foreach ($formatter_shareArr[$k2]['picture'] as $key => $value){
                                    if($value['picture_id']==$v1['picture_id'] || $value['location']==$v1['location']){
                                        $flag = false;
                                        break;
                                    }
                                }
                                if($flag ==true){
                                    $formatter_shareArr[$k2]['picture'][$array_length]['picture_id'] = $v1['picture_id'];
                                    $formatter_shareArr[$k2]['picture'][$array_length]['location'] = $v1['location'];
                                }
                            }else{
                                $formatter_shareArr[$k2]['picture'][$array_length]['picture_id'] = $v1['picture_id'];
                                $formatter_shareArr[$k2]['picture'][$array_length]['location'] = $v1['location'];
                            }
                        }
                        continue 2;
                    }
                }
                $formatter_shareArr[$k1] = $v1;
            }
            
            foreach ($formatter_shareArr as $key => $value){
                unset($formatter_shareArr[$key]['like_id'],$formatter_shareArr[$key]['picture_id'],$formatter_shareArr[$key]['location']);
            }
            
            $formatter_shareArr = array_slice($formatter_shareArr, $offset,$page_size);
        }
    
        
        return $formatter_shareArr;
    }
    
    /**
     * Returns true or false
     *
     * @api
     * @param string $title Share title.
     * @param string $content Share content.
     * @return int status.
     */
    public function insertShare($share_owner_id,$title,$content){
        if(empty($title)){
            throw InputException::invalidFieldValue('title',$title);
        }else{
            $title = htmlspecialchars($title, ENT_QUOTES);
        }
        if(empty($content)){
            throw InputException::invalidFieldValue('content',$content);
        }else{
            $content = htmlspecialchars($content, ENT_QUOTES);
        }
        $table=$this->_resource->getTableName('mgzj_share');
        $data[] = ['share_owner_id' => $share_owner_id, 'content' => $content, 'title' => $title, 'publish_time'=>time(),'update_time'=>time()];
        $res = $this->getConnection()->insertMultiple($table,$data);
        return $this->getShareByUser($share_owner_id);
    }
    
    /**
     * delete share
     *
     * @api
     * @param string $share_owner_id id
     * $param string $share_id share id
     * @return int status.
     */
    public function deleteShare($share_owner_id,$share_id){
        $table=$this->_resource->getTableName('mgzj_share');
        $where = 'share_owner_id = '.$share_owner_id." and share_id = ".$share_id;
        $res = $this->getConnection()->delete($table,$where);
        //删除相关依赖
        $table=$this->_resource->getTableName('mgzj_share_like');
        $where = 'share_owner_id = '.$share_owner_id." and share_id = ".$share_id;
        $res = $this->getConnection()->delete($table,$where);
        $table=$this->_resource->getTableName('mgzj_share_picture');
        $where = 'share_owner_id = '.$share_owner_id." and share_id = ".$share_id;
        $res = $this->getConnection()->delete($table,$where);
        $table=$this->_resource->getTableName('mgzj_share_comment');
        $where = 'share_owner_id = '.$share_owner_id." and share_id = ".$share_id;
        $res = $this->getConnection()->delete($table,$where);
        return $this->getShareByUser($share_owner_id);
    }
    
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
    public function updateShare($share_owner_id,$share_id,$title,$content){
        $table=$this->_resource->getTableName('mgzj_share');
        $where = 'share_owner_id = '.$share_owner_id." and share_id = ".$share_id;
        if(!empty($title)&&!empty($content)){
            $data = ['title' => $title,'content' => $content];
        }elseif(!empty($title)){
           $data = ['title' => $title];
        }elseif(!empty($content)){
            $data = ['content' => $content];
        }
        
        $res = $this->getConnection()->update($table,$data,$where);
        return $this->getShareByUser($share_owner_id);
    }
    
    /**
     * get comment
     *
     * @api
     * @param string $share_owner_id id
     * @param string $share_id share id
     * @return string comment.
     */
    public function getCommentByShare($share_owner_id,$share_id){
        $commentArr = $this->getConnection()
        ->fetchAll('SELECT S.share_owner_id,S.share_id,S.content as share, SC.Comment_owner_id, C.firstname, C.lastname, SC.Comment_id, SC.publish_time as Comment_publish_time, SC.content as comment
                    FROM mgzj_share S,mgzj_share_comment SC,customer_entity C
                    WHERE S.share_owner_id = SC.share_owner_id and S.share_id = SC.share_id and C.entity_id=SC.comment_owner_id 
                                Order by SC.publish_time desc');
    
        return $commentArr;
    }
    
    /**
     * insert comment
     *
     * @api
     * @param string $share_owner_id id
     * @param string $share_id share id
     * @param string $comment_owner_id comment_owner_id id
     * @param string $title title 
     * @param string $contnet content
     * @return String $comment.
     */
    public function insertCommentByShare($share_owner_id,$share_id,$comment_owner_id,$content){
        if(empty($content)){
            throw InputException::invalidFieldValue('content',$content);
        }else{
            $content = htmlspecialchars($content, ENT_QUOTES);
        }
        if(empty($comment_owner_id)){
            throw InputException::invalidFieldValue('comment_owner_id',$comment_owner_id);
        }
        $table=$this->_resource->getTableName('mgzj_share_comment');
        $data[] = ['share_owner_id' => $share_owner_id, 'share_id'=>$share_id,'comment_owner_id'=>$comment_owner_id,'content' => $content, 'publish_time'=>time()];
        $res = $this->getConnection()->insertMultiple($table,$data);
        return $this->getCommentByShare($share_owner_id,$share_id);
    }
    
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
    public function deleteCommentByShare($share_owner_id,$share_id,$comment_owner_id,$comment_id){
        $table=$this->_resource->getTableName('mgzj_share_comment');
        $where = 'share_owner_id = '.$share_owner_id." and share_id = ".$share_id." and comment_owner_id = ".$comment_owner_id." and comment_id = ".$comment_id;
        $res = $this->getConnection()->delete($table,$where);
        return $this->getCommentByShare($share_owner_id,$share_id);
    }
    
    /**
     * get share detail
     *
     * @api
     * @param string $share_owner_id id
     * @param string $share_id share id
     * @return String $share.
     */
    public function getShareDetail($share_owner_id,$share_id){
        $shareDetail = $this->getConnection()
        ->fetchAll('SELECT S.share_owner_id,S.share_id,S.content as share,  C1.firstname as share_owner_firstname, C1.lastname as share_owner_lastname,SC.comment_owner_id, SC.comment_id,C2.firstname as comment_owner_firstname, C2.lastname as comment_owner_lastname, SC.publish_time as comment_publish_time, SC.content as comment,SP.location,SP.picture_id,SL.from_customer_id as like_id
                    FROM mgzj_share S,mgzj_share_comment SC,customer_entity C1,customer_entity C2, mgzj_share_like SL, mgzj_share_picture SP
                    WHERE S.share_owner_id = SC.share_owner_id and S.share_id = SC.share_id 
                            and C1.entity_id=S.share_owner_id
                            and C2.entity_id=SC.comment_owner_id
                            and S.share_owner_id = SL.share_owner_id and S.share_id = SL.share_id
                            and S.share_owner_id = SP.share_owner_id and S.share_id = SP.share_id
                            and S.share_owner_id = '.$share_owner_id. ' and S.share_id ='.$share_id
                     );

        $shareDetailFormatter = array();
        if(!empty($shareDetail)){
            foreach ($shareDetail as $k => $v){
                $shareDetailFormatter['share_owner_id'] = $v['share_owner_id'];
                $shareDetailFormatter['share_id'] = $v['share_id'];
                $shareDetailFormatter['share_owner_firstname'] = $v['share_owner_firstname'];
                $shareDetailFormatter['share_owner_lastname'] = $v['share_owner_lastname'];
                //comment
                if(!empty($v['comment_id'])){
                    if(!isset($shareDetailFormatter['comment'])){
                        $shareDetailFormatter['comment'] = array();
                    }else{
                        $shareDetailFormatter['comment'][$v['comment_owner_id']][$v['comment_id']]['comment_owner_id'] = $v['comment_owner_id'];
                        $shareDetailFormatter['comment'][$v['comment_owner_id']][$v['comment_id']]['comment_id'] = $v['comment_id'];
                        $shareDetailFormatter['comment'][$v['comment_owner_id']][$v['comment_id']]['comment'] = $v['comment'];
                        $shareDetailFormatter['comment'][$v['comment_owner_id']][$v['comment_id']]['comment_publish_time'] = $v['comment_publish_time'];
                    }
                }
                //like
                if(!empty($v['like_id'])){
                    if(empty($shareDetailFormatter['like_ids'])){
                        $shareDetailFormatter['like_ids'] = array();
                    }
                    if(!in_array($v['like_id'], $shareDetailFormatter['like_ids'])){
                        $shareDetailFormatter['like_ids'][] = $v['like_id'];
                    }
                }
                //picture
                if(!empty($v['picture_id'])){
                    if(empty($shareDetailFormatter['picture'])){
                        $shareDetailFormatter['picture'] = array();
                    }
                    $array_length = count($shareDetailFormatter['picture']);
                    if($array_length!=0){
                        $flag = true;
                        foreach ($shareDetailFormatter['picture'] as $key => $value){
                            if($value['picture_id']==$v['picture_id'] || $value['location']==$v['location']){
                                $flag = false;
                                break;
                            }
                        }
                        if($flag ==true){
                            $shareDetailFormatter['picture'][$array_length]['picture_id'] = $v['picture_id'];
                            $shareDetailFormatter['picture'][$array_length]['location'] = $v['location'];
                        }
                    }else{
                        $shareDetailFormatter['picture'][$array_length]['picture_id'] = $v['picture_id'];
                        $shareDetailFormatter['picture'][$array_length]['location'] = $v['location'];
                    }
                }
            }
        }
        
        $data[] = $shareDetailFormatter;
        return $data;
    }
    
    /**
     * get message
     *
     * @api
     * @param string $message_owner_id id
     * @return String $message.
     */
    public function getMessageByUser($message_owner_id){
        $message = $this->getConnection()
        ->fetchAll('
            SELECT M.message_owner_id, M.message_id, M.time as message_time,M.is_read, SC.share_owner_id,SC.share_id,SC.comment_owner_id,SC.comment_id,SC.content as comment,C.firstname as comment_owner_firstname,C.lastname as comment_owner_lastname,SC.publish_time as comment_publish_time
            FROM mgzj_share_comment as SC, mgzj_msg_comment_relation as MCR, mgzj_messagebox as M, customer_entity C
            WHERE SC.share_owner_id = MCR.share_owner_id
        	and SC.share_id = MCR.share_id
            and SC.comment_owner_id = MCR.comment_owner_id
            and SC.comment_id = MCR.comment_id
            and M.message_owner_id = MCR.message_owner_id
            and M.message_id = MCR.message_id
            and SC.comment_owner_id = C.entity_id
            and M.message_owner_id = '.$message_owner_id
            );
        return $message;
    }
    
    /**
     * create message
     *
     * @api
     * @param string $message_owner_id id
     * @param string $share_owner_id share id
     * @param string $share_id comment_owner_id id
     * @param string $comment_owner_id title
     * @param string $comment_id content
     * @return String $comment.
     */
    public function createCommentMessageByUser($message_owner_id,$share_owner_id,$share_id,$comment_owner_id,$comment_id){
        $table=$this->_resource->getTableName('mgzj_messagebox');
        $data1[] = ['message_owner_id' => $message_owner_id,  'time'=>time()];
        $res = $this->getConnection()->insertMultiple($table,$data1);
        $message_id = $this->getConnection()->lastInsertId($table);
        $table=$this->_resource->getTableName('mgzj_msg_comment_relation');
        $data2[] = ['share_owner_id'=>$share_owner_id,'share_id'=>$share_id,'comment_owner_id'=>$comment_owner_id,'comment_id'=>$comment_id,'message_owner_id' => $message_owner_id, 'message_id'=>$message_id];
        $res = $this->getConnection()->insertMultiple($table,$data2);
        return $this->getMessageByUser($message_owner_id);
    }
    
    /**
     * delete message
     *
     * @api
     * @param string $message_owner_id id
     * @param string $message_id  id
     * @return string $message
     */
    public function deleteMessageByShare($message_owner_id,$message_id){
        $table=$this->_resource->getTableName('mgzj_messagebox');
        $where = 'message_owner_id = '.$message_owner_id." and message_id = ".$message_id;
        $res = $this->getConnection()->delete($table,$where);
        $table=$this->_resource->getTableName('mgzj_msg_comment_relation');
        $where = 'message_owner_id = '.$message_owner_id." and message_id = ".$message_id;
        $res = $this->getConnection()->delete($table,$where);
        return $this->getMessageByUser($message_owner_id);
    }
    
    /**
     * set message status
     *
     * @api
     * @param string $message_owner_id id
     * @param string $message_id  id
     * @return string $message
     */
    public function setMessageReadStatus($message_owner_id,$message_id){
        $table=$this->_resource->getTableName('mgzj_messagebox');
        $where = 'message_owner_id = '.$message_owner_id." and message_id = ".$message_id;
        $data = ['is_read' => 1];
        $res = $this->getConnection()->update($table,$data,$where);
        return $this->getMessageByUser($message_owner_id);
    }
    
    /**
     * get collection
     *
     * @api
     * @param string $collection_owner_id id
     * @return string $message
     */
    public function getShareCollection($collection_owner_id){
        $collectionArr = $this->getConnection()
        ->fetchAll('SELECT S.share_owner_id,S.share_id,C.firstname as share_owner_firstname,C.lastname as share_owner_lastname,S.title,S.content
                   FROM mgzj_share S,customer_entity C,mgzj_share_collect MSC
                   WHERE S.share_owner_id=C.entity_id and 
                   MSC.share_owner_id = S.share_owner_id and
                   MSC.share_id = S.share_id and
                   MSC.collection_owner_id='.$collection_owner_id);
        return $collectionArr;
    }
    
    /**
     * add collection
     *
     * @api
     * @param string $collection_owner_id id
     * @param string $share_owner_id id
     * @param string $share_id id
     * @return string $message
     */
    public function addShareCollection($collection_owner_id,$share_owner_id,$share_id){
        $table=$this->_resource->getTableName('mgzj_share_collect');
        $data[] = ['share_owner_id' => $share_owner_id, 'share_id'=>$share_id,'collection_owner_id'=>$collection_owner_id];
        $res = $this->getConnection()->insertMultiple($table,$data);
        return $this->getShareCollection($collection_owner_id);
    }
    
    /**
     * delete collection
     *
     * @api
     * @param string $collection_owner_id id
     * @param string $share_owner_id id
     * @param string $share_id id
     * @return string $message
     */
    public function deleteShareCollection($collection_owner_id,$share_owner_id,$share_id){
        $table=$this->_resource->getTableName('mgzj_share_collect');
        $where = 'share_owner_id = '.$share_owner_id." and share_id = ".$share_id;
        $res = $this->getConnection()->delete($table,$where);
        return $this->getShareCollection($collection_owner_id);
    }
    
}