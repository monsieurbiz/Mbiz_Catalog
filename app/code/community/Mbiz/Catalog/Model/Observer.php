<?php
/**
 * This file is part of Mbiz_Catalog for Magento.
 *
 * @license proprietary
 * @author Jacques Bodin-Hullin <j.bodinhullin@monsieurbiz.com> <@jacquesbh>
 * @category Mbiz
 * @package Mbiz_Catalog
 * @copyright Copyright (c) 2016 Monsieur Biz (https://monsieurbiz.com/)
 */

/**
 * Observer Model
 * @package Mbiz_Catalog
 */
class Mbiz_Catalog_Model_Observer extends Mage_Core_Model_Abstract
{

// Monsieur Biz Tag NEW_CONST

// Monsieur Biz Tag NEW_VAR

    /**
     * Apply the handle of the page layout if it is not the default
     * <p>Only for the category page.</p>
     */
    public function applyLayoutHandle(Varien_Event_Observer $observer)
    {
        $action = $observer->getAction();

        if ($action->getFullActionName() === 'catalog_category_view') {
            $category = Mage::registry('current_category');
            $settings = Mage::getSingleton('catalog/design')->getDesignSettings($category);
            if ($settings->getPageLayout()) {
                $observer->getLayout()->helper('page/layout')->applyHandle($settings->getPageLayout());
            }
        }

        // In case we have Mbiz_Ajax installed and enabled
        if (Mage::helper('core')->isModuleEnabled('Mbiz_Ajax')) {
            Mage::getSingleton('mbiz_ajax/observer')->addAjaxHandles($observer);
        }
    }

// Monsieur Biz Tag NEW_METHOD

}
