<?php
/**
 * This file is part of Mbiz_Catalog for Magento.
 *
 * @license MIT
 * @author Jacques Bodin-Hullin <j.bodinhullin@monsieurbiz.com> <@jacquesbh>
 * @category Mbiz
 * @package Mbiz_Catalog
 * @copyright Copyright (c) 2016 Monsieur Biz (https://monsieurbiz.com/)
 */

/**
 * Layer Helper
 * @package Mbiz_Catalog
 */
class Mbiz_Catalog_Helper_Layer extends Mage_Core_Helper_Abstract
{

// Monsieur Biz Tag NEW_CONST

    /**
     * Active filters
     * @var array
     */
    protected $_activeFilters;

// Monsieur Biz Tag NEW_VAR

    /**
     * Retrieve the active filters (as Item)
     * @param Mage_Catalog_Model_Layer $layer
     * @return Mage_Catalog_Model_Layer_Filter_Item[]
     */
    public function getActiveFilterItems(Mage_Catalog_Model_Layer $layer)
    {
        if (null === $this->_activeFilters) {
            $filters       = $layer->getState()->getFilters();
            $this->_activeFilters = [];

            /* @var $item Mage_Catalog_Model_Layer_Filter_Item */
            foreach ($filters as $item) {
                /* @var $filter Mage_Catalog_Model_Layer_Filter_Abstract */
                $filter = $item->getFilter();
                $this->_activeFilters[$filter->getRequestVar()] = $item;
            }
        }
        return $this->_activeFilters;
    }

    /**
     * Retrieve the filter model of a block filter
     * @param Mage_Catalog_Block_Layer_Filter_Abstract $filter
     * @return Mage_Catalog_Model_Layer_Filter_Abstract
     */
    public function getFilterModel(Mage_Catalog_Block_Layer_Filter_Abstract $filter)
    {
        if (!$realFilter = $filter->getData('_filter_model')) {
            // Make the _filter property accessible
            $filterProperty = (new ReflectionClass($filter))->getProperty('_filter');
            $filterProperty->setAccessible(true);
            $realFilter = $filterProperty->getValue($filter);
            $filter->setData('_filter_model', $realFilter);
            unset($filterProperty);
        }
        return $realFilter;
    }

    /**
     * Retrieve the filter item (model) of a filter block
     * <p>It returns NULL if the filter isn't active.</p>
     * @param Mage_Catalog_Block_Layer_Filter_Abstract $filter
     * @return Mage_Catalog_Model_Layer_Filter_Item|null
     */
    public function getFilterItem(Mage_Catalog_Block_Layer_Filter_Abstract $filter)
    {
        // No active? We can't get the non existent item
        if (!$this->isFilterActive($filter)) {
            return null;
        }

        $filterModel = $this->getFilterModel($filter);
        $activeFilters = $this->getActiveFilterItems($filter->getLayer());
        return $activeFilters[$filterModel->getRequestVar()];
    }

    /**
     * Is a filter active?
     * @param Mage_Catalog_Block_Layer_Filter_Abstract $filter
     * @return bool
     */
    public function isFilterActive(Mage_Catalog_Block_Layer_Filter_Abstract $filter)
    {
        $realFilter = $this->getFilterModel($filter);
        $activeFilters = $this->getActiveFilterItems($realFilter->getLayer());
        return array_key_exists($realFilter->getRequestVar(), $activeFilters);
    }

// Monsieur Biz Tag NEW_METHOD

}
