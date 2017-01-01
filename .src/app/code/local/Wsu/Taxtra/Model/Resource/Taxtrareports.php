<?php
class Wsu_Taxtra_Model_Resource_Taxtrareports extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('wsu_taxtra/taxtrareports', 'taxtra_report_id');
    }
}
