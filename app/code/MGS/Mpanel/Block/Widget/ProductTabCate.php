<?php
namespace MGS\Mpanel\Block\Widget;
 
class ProductTabCate extends \Magento\Catalog\Block\Product\AbstractProduct implements \Magento\Widget\Block\BlockInterface
{
	protected $httpContext;

    protected $_catalogProductVisibility;

    protected $_productCollectionFactory;
	
    protected $_objectManager;
	
	protected $_count;
	
	protected $_date;
	
	protected $_resource;
	
	protected $urlHelper;
	
	public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Framework\App\Http\Context $httpContext,
		\Magento\Framework\Stdlib\DateTime\DateTime $date,
		\Magento\Framework\ObjectManagerInterface $objectManager,
		\Magento\Catalog\Model\CategoryFactory $categoryFactory,
		\Magento\Framework\App\ResourceConnection $resource,
		\Magento\Framework\Url\Helper\Data $urlHelper,
        array $data = []
    ) {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_catalogProductVisibility = $catalogProductVisibility;
		$this->_categoryFactory = $categoryFactory;
		$this->_objectManager = $objectManager;
		$this->_resource = $resource;
		$this->_date = $date;
        $this->httpContext = $httpContext;
		$this->urlHelper = $urlHelper;
        parent::__construct(
            $context,
            $data
        );
    }
	
	public function _toHtml()
    {
		$this->setTemplate('widget/product_tab_cate.phtml');
		return parent::_toHtml();
    }
	
	public function getProductCollection($id_category)
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
		$collection->joinField(
			'category_id', $this->_resource->getTableName('catalog_category_product'), 'category_id', 
			'product_id = entity_id', null, 'left'
		)
		->addAttributeToFilter('category_id', array(
			array('finset' => $id_category),
		));
		
		if($this->getTyleList() == 'sale'){
			$collection = $this->getSaleProduct($collection);
		}
		
		if($this->getTyleList() == 'top_rate'){
			$collection = $this->getTopRateProduct($collection);
		}
		
		if($this->getTyleList() == 'attribute'){
			$collection = $this->getAttributeProduct($collection);
		}
		
		if($this->getTyleList() == 'new'){
			$collection = $this->getNewProduct($collection);
		}
		if(count($collection) > $this->getProductsCount()){
			$collection->setPageSize($this->getProductsCount())
				->setCurPage(1);
		}
		
        return $collection;
    }
	
	public function getNewProduct($collection)
    {
		$now = $this->_date->gmtDate();
		$todayStartOfDayDate = $this->_localeDate->date()->setTime(0, 0, 0)->format('Y-m-d H:i:s');
        $todayEndOfDayDate = $this->_localeDate->date()->setTime(23, 59, 59)->format('Y-m-d H:i:s');
		
		$collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
			->addAttributeToFilter(
				'news_from_date',
				[
					'or' => [
						0 => ['date' => true, 'to' => $todayEndOfDayDate],
						1 => ['is' => new \Zend_Db_Expr('not null')],
					]
				],
				'left'
			)->addAttributeToFilter(
				'news_to_date',
				[
					'or' => [
						0 => ['date' => true, 'from' => $todayStartOfDayDate],
						1 => ['is' => new \Zend_Db_Expr('not null')],
					]
				],
				'left'
			)->addAttributeToFilter(
				[
					['attribute' => 'news_from_date', 'is' => new \Zend_Db_Expr('not null')],
					['attribute' => 'news_to_date', 'is' => new \Zend_Db_Expr('not null')],
				]
			)->addAttributeToFilter(
				[
					['attribute' => 'news_from_date', array('lt' => $now)],
				]
			)->addAttributeToFilter(
				[
					['attribute' => 'news_to_date', array('gt' => $now)],
				]
			)->addAttributeToSort(
				'news_from_date',
				'desc'
			);
		return $collection;
    }
	
	public function getAttributeProduct($collection)
    {
		if($this->getData('attribute_filter_list') != ''){
			$collection->addAttributeToFilter($this->getData('attribute_filter_list'), 1);
			return $collection;
		}
		return;
    }
	public function getCategoryNameShow($categoryId)
    {
		$category = $this->_categoryFactory->create();
		$category->load($categoryId);
		$_catName = $category->getName();
		return $_catName;
    }
	
	public function getTopRateProduct($collection)
    {
		$storeId = $this->_storeManager->getStore(true)->getId();
        $reviewTable =  $this->_resource->getTableName('review_entity_summary');
		$collection = $this->_addProductAttributesAndPrices($collection)
			->addAttributeToSelect('*');
		$collection->joinField('rating_summary', ''. $reviewTable .'', 'rating_summary', 'entity_pk_value=entity_id',  array('entity_type' => 1, 'store_id' => (int)$storeId), 'left');
	
		$collection->joinField('reviews_count', ''. $reviewTable .'', 'reviews_count', 'entity_pk_value=entity_id',  array('entity_type' => 1, 'store_id' => (int)$storeId), 'left');

		$collection->setOrder('rating_summary', 'desc');
		$collection->setOrder('reviews_count', 'desc');
		return $collection;
    }
	
	public function getSaleProduct($collection)
    {
        $collection = $this->_addProductAttributesAndPrices($collection)
			->addAttributeToSelect('*')
			->addStoreFilter()
			->addFinalPrice()
			->addAttributeToSort('created_at', 'desc')
			->addAttributeToFilter('special_price', ['notnull'=>true]);  
		
		$collection->getSelect()->where('price_index.final_price < price_index.price');
        return $collection;
    }
	
	public function getProductsCount()
    {
        if (!$this->hasData('number_show')) {
            return 3;
        }
        return $this->getData('number_show');
    }
	
	public function getCategoryToShow()
    {
		$result = [];
		if($this->hasData('category_id')){
			$categoryIds = $this->getData('category_id');
			$categoryArray = explode(',',$categoryIds);
			if(count($categoryArray)>0){
				foreach($categoryArray as $categoryId){
					$result[] = $categoryId;
				}
			}
		}
		return $result;
    }
	
	public function getTyleList()
    {
		return $this->getData('type_list');
    }
	
	public function getViewMode()
    {
        return $this->getData('view_mode');
    }
	
	public function getEncodedUrl($url)
    {
        return $this->urlHelper->getEncodedUrl($url);
    }
	
}