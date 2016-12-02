<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MGS\Portfolio\Block;

use Magento\Framework\View\Element\Template;

/**
 * Main contact form block
 */
class Portfolio extends Template
{
	/**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
	
    /**
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
		Template\Context $context, array $data = [], 
		\Magento\Framework\ObjectManagerInterface $objectManager
	)
    {
        parent::__construct($context, $data);
		$this->_objectManager = $objectManager;
    }
	
	/**
     * Prepare global layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
		$id = $this->getRequest()->getParam('id');
		$portfolio = $this->getModel()->load($id);
		$title = $portfolio->getName();
		
		$breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs');
		$breadcrumbsBlock->addCrumb(
			'home',
			[
				'label' => __('Home'),
				'title' => __('Go to Home Page'),
				'link' => $this->_storeManager->getStore()->getBaseUrl()
			]
		);
		
		$breadcrumbsBlock->addCrumb('portfolio_category', ['label' => $title, 'title' => $title]);
		
        $this->pageConfig->getTitle()->set($title);
        return parent::_prepareLayout();
    }
	
	public function getModel(){
		return $this->_objectManager->create('MGS\Portfolio\Model\Portfolio');
	}
	
	public function getPortfolio(){
		return $this->getModel()->load($this->getRequest()->getParam('id'));
	}
	
	public function getBaseImage($portfolio){
		$filePath = 'mgs/portfolio/image/'.$portfolio->getBaseImage();
		if($filePath!=''){
			$imageUrl = $this->_urlBuilder->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]) . $filePath;
			return $imageUrl;
		}
		return 0;
	}
	
	public function getCategories($portfolio){
		$collection = $this->_objectManager->create('MGS\Portfolio\Model\Stores')
			->getCollection()
			->addFieldToFilter('portfolio_id', $portfolio->getId());
		
		$resourceModel = $this->_objectManager->create('MGS\Portfolio\Model\ResourceModel\Stores');
		$collection = $resourceModel->joinFilter($collection);
		return $collection;
	}
	
	public function getRelatedPortfolio($portfolio){
		$collection = $this->getCategories($portfolio);
		if(count($collection)>0){
			$arrResult = array();
			foreach($collection as $item){
				$arrResult[] = $item->getCategoryId();
			}
			$catString = implode(', ', $arrResult);
			
			$portfolios = $this->getModel()
				->getCollection()
				->addFieldToFilter('status', 1);
				
			if($catString != ''){
				$resourceModel = $this->_objectManager->create('MGS\Portfolio\Model\ResourceModel\Portfolio');
				$portfolios = $resourceModel->getRelatedPortfolio($portfolios, $portfolio->getId(), $catString);
			}
			return $portfolios;
		}
		return false;
	}
	
	public function getPortfolioAddress($portfolio){
		$identifier = $portfolio->getIdentifier();
		if($identifier!=''){
			return $this->getUrl('portfolio/'.$identifier);
		}
		return $this->getUrl('portfolio/index/view', ['id'=>$portfolio->getId()]);
	}
	
	public function getThumbnailSrc($portfolio){
		$filePath = 'mgs/portfolio/thumbnail/'.$portfolio->getThumbnailImage();
		if($filePath!=''){
			$thumbnailUrl = $this->_urlBuilder->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]) . $filePath;
			return $thumbnailUrl;
		}
		return 0;
	}
	
	public function getCategoriesText($portfolio){
		$collection = $this->getCategories($portfolio);
		
		if(count($collection)>0){
			$arrResult = [];
			foreach($collection as $item){
				$arrResult[] = $item->getName();
			}
			return implode(', ', $arrResult);
		}
		return '';
	}
	
	public function getCategoriesLink($portfolio){
		$collection = $this->getCategories($portfolio);
		$html = '';
		if(count($collection)>0){
			$i=0;
			foreach($collection as $item){
				$cate = $this->_objectManager->create('MGS\Portfolio\Model\Category')->getCollection()->addFieldToFilter('category_id', ['eq' => $item->getCategoryId()])->getFirstItem();
				$i++;
				if($cate->getIdentifier()!=''){
					$html .= '<a href="'.$this->getUrl('portfolio/'.$cate->getIdentifier()).'">'.$item->getName().'</a>';
				}else{
					$html .= '<a href="'.$this->getUrl('portfolio/category/view', ['id'=>$cate->getId()]).'">'.$item->getName().'</a>';
				}
				
				if($i<count($collection)){
					$html .= ', ';
				}
			}
		}
		return $html;
	}
	
	public function getPortfolios($cateid = NULL){
		$portfolios = $this->getModel()
			->getCollection()
			->addFieldToFilter('status', 1);
		
		if($this->getPortfolioCount()>0){
			$portfolios->setPageSize($this->getPortfolioCount());
		}

		if($cateid != null){
			$resourceModel = $this->_objectManager->create('MGS\Portfolio\Model\ResourceModel\Portfolio');
			$portfolios = $resourceModel->filterByCategories($portfolios, $cateid);
		}
		foreach ($portfolios as $portfolio) {
			if($portfolio->getIdentifier()!=''){
				$portfolio->setAddress($this->getUrl('portfolio/'.$portfolio->getIdentifier()));
			}else{
				$portfolio->setAddress($this->getUrl('portfolio/index/view', ['id'=>$portfolio->getId()]));
			}
        }

		return $portfolios;
	}
	
	public function getPortfoliosId(){
		$portfolios = $this->getPortfolios();
		if(count($portfolios) > 0){
			$arrResult = array();
			foreach($portfolios as $item){
				$arrResult[] = $item->getPortfolioId();
			}
			return $arrResult;
		}
		return;
	}
	
	public function getUrlPortfoliosById($id){
		$portfolios = $this->getModel()->load($id);
		if($portfolios->getIdentifier()!=''){
			return $this->getUrl('portfolio/'.$portfolios->getIdentifier());
		}else{
			return $this->getUrl('portfolio/index/view', ['id'=>$id]);
		}
	}
	
	public function getNextUrl($id){
		$arrId = $this->getPortfoliosId();
		if(is_array($arrId)){
			$maxKey = count($arrId) - 1;
			$key = array_search($id, $arrId);
			if($key == $maxKey){
				$nextKey = 0;
			}else {
				$nextKey = $key + 1;
			}
			$idNext = $arrId[$nextKey];
			return $this->getUrlPortfoliosById($idNext);
		}
		return;
	}
	
	public function getPrevUrl($id){
		$arrId = $this->getPortfoliosId();
		if(is_array($arrId)){
			$maxKey = count($arrId) - 1;
			$key = array_search($id, $arrId);
			if($key == 0){
				$nextKey = $maxKey;
			}else {
				$nextKey = $key - 1;
			}
			$idNext = $arrId[$nextKey];
			return $this->getUrlPortfoliosById($idNext);
		}
		return;
	}
}

