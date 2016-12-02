<?php

namespace MGS\Blog\Model\Resource\Post;

use MGS\Blog\Model\Resource\PostCollection;

class Collection extends PostCollection
{
    protected $_idFieldName = 'post_id';
    protected $_previewFlag;

    protected function _construct()
    {
        $this->_init('MGS\Blog\Model\Post', 'MGS\Blog\Model\Resource\Post');
        $this->_map['fields']['post_id'] = 'main_table.post_id';
        $this->_map['fields']['store'] = 'store_table.store_id';
    }

    public function toOptionIdArray()
    {
        $res = [];
        $existingIdentifiers = [];
        foreach ($this as $item) {
            $identifier = $item->getData('url_key');
            $data['value'] = $identifier;
            $data['label'] = $item->getData('title');
            if (in_array($identifier, $existingIdentifiers)) {
                $data['value'] .= '|' . $item->getData('post_id');
            } else {
                $existingIdentifiers[] = $identifier;
            }
            $res[] = $data;
        }
        return $res;
    }

    public function setFirstStoreFlag($flag = false)
    {
        $this->_previewFlag = $flag;
        return $this;
    }

    public function addStoreFilter($store, $withAdmin = true)
    {
        if (!$this->getFlag('store_filter_added')) {
            $this->performAddStoreFilter($store, $withAdmin);
        }
        return $this;
    }

    public function addCategoryFilter($categoryId)
    {
        $this->getSelect()
            ->join(
                ['category_table' => $this->getTable('mgs_blog_category_post')],
                'main_table.post_id = category_table.post_id',
                []
            )
            ->where('category_table.category_id = ?', $categoryId);
        return $this;
    }

    public function addTagFilter($tag)
    {
        $this->getSelect()
            ->where('tags like ?', '%' . $tag . '%');
        return $this;
    }

    protected function _afterLoad()
    {
        $this->performAfterLoad('mgs_blog_post_store', 'post_id');
        $this->_previewFlag = false;

        return parent::_afterLoad();
    }

    protected function _renderFiltersBefore()
    {
        $this->joinStoreRelationTable('mgs_blog_post_store', 'post_id');
    }
}
