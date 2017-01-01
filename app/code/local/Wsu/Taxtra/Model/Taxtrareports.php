<?php
class Wsu_Taxtra_Model_Taxtrareports extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        //parent::_construct(); contextually does this really need to be here?  removed for now
        $this->_init('wsu_taxtra/taxtrareports');
    }
}
