<?php
class Web_Deal_Block_Html_Topmenu extends Mage_Core_Block_Template
{
    protected $_subcats;

    public function _construct()
    {
        if (
            Mage::getSingleton('cms/page')->getIdentifier() == 'home' &&
            Mage::app()->getFrontController()->getRequest()->getRouteName() == 'cms'
        ) {
            //return;
        }
        $currentCategory = Mage::registry('current_category');
        if ($currentCategory) {
            $currentCategory = $currentCategory->getId();
        }
        $catCookie = Mage::getModel('core/cookie')->get('main_cat');
        $mainCat = empty($catCookie) ? (int)Mage::getStoreConfig('deal/config/main_cat') : Mage::getModel('core/cookie')->get('main_cat');

        $array = array();
        $cats = Mage::getModel('catalog/category')->getCollection()
            ->addAttributeToSelect('name')
            ->addFilter('parent_id', $mainCat)
            ->addFilter('level', '3')
            ->addAttributeToFilter('include_in_menu',array('eq'=>1))
            ->addAttributeToFilter('is_active',array('eq'=>1))->load();
        $i = 0;
        $array = array();
        $active = false;
        if (!$currentCategory) {
            $active = true;
        }
        $array[] = array('value' => 0, 'label' => 'All Deals', 'url' => '/', 'active' => $active);
        foreach ($cats as $cat) {
            $active = false;
            if ($cat->getId() == $currentCategory) {
                $active = true;
            }
            $i++;

            $array[$i] = array('value' => $cat->getId(), 'label' => $cat->getName(), 'url' => $cat->getUrl(), 'active' => $active);

        }

        $this->_subcats = $array;
    }

}