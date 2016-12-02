<?php
namespace MGZJ\Helloworld\Model;

use MGZJ\Helloworld\Api\HelloworldInterface;

class Helloworld implements HelloworldInterface
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
    public function name($name) {
        $table=$this->_resource->getTableName('mgzj_share');
        $sku = $this->getConnection()->fetchRow('SELECT * FROM ' . $table);
        //$data[] = ['share_owner_id' => 385, 'content' => '梓敬的第2条分享内容', 'title' => '梓敬的第2条分享题目', 'publish_time'=>'1480393706','update_time'=>'1480393706'];
        $sku = $this->getConnection()->insertMultiple($table,$data);
        var_dump($sku);exit();
        //$this->connection = $this->_resource->getConnection('mgzj_share');
        //var_dump($this->_resource);exit();
        //$array = [1,2,3,4,5];
        return $name;
    }
}