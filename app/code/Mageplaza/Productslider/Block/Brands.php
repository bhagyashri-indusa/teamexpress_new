<?php
namespace Mageplaza\Productslider\Block;
/**
 * Class NewProducts
 * Get collection of new products
 * @package Mageplaza\Productslider\Block
 */

class Brands extends \Mageplaza\Productslider\Block\AbstractSlider
{
      
    
	public function getProductCollection()
	{
		
                
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                //$collection = $objectManager->create('Emizentech\ShopByBrand\Model\Items')->getCollection();
                
                
                $connection = $objectManager->get('Magento\Framework\App\ResourceConnection')->getConnection('\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION'); 
                $collection = $connection->fetchAll("SELECT * FROM `emizentech_shopbybrand_items` where is_active = 1 Order by name asc");
               return $collection;
	}


	public function getProductCacheKey()
	{return 'mageplaza_product_slider_newbrands' ;
	}

}