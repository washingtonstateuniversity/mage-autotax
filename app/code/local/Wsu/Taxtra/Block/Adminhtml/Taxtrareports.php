<?php
class Wsu_Taxtra_Block_Adminhtml_Taxtrareports extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_taxtrareports';
        $this->_blockGroup = 'taxtrareports';
        $this->_headerText = Mage::helper('wsu_taxtra')->__('Advanced Tax Report');
        parent::__construct();
        $this->_removeButton('add');
    }
}
