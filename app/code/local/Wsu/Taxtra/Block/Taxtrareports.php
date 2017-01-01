<?php
class Wsu_Taxtra_Block_Taxtrareports extends Mage_Core_Block_Template
{
    /*public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }*/

    public function getTaxtrareports()
    {
        if (!$this->hasData('taxtrareports')) {
            $this->setData('taxtrareports', Mage::registry('taxtrareports'));
        }
        return $this->getData('taxtrareports');

    }
}
