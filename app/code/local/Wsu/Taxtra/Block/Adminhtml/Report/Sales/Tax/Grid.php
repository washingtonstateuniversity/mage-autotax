<?php
class Wsu_Taxtra_Block_Adminhtml_Report_Sales_Tax_Grid extends Mage_Adminhtml_Block_Report_Sales_Tax_Grid
{
    /*
    *
    */
    protected function _prepareColumns()
    {
        parent::_prepareColumns();
        $this->addColumnAfter('tax_postcode', array(
            'header'        => Mage::helper('tax')->__('Zip/Post Code'),
            'align'         =>'left',
            'index'         => 'tax_postcode',
            'type'          => 'text',
            'width'         => '100',
            'renderer'      => 'Wsu_Taxtra_Block_Adminhtml_Report_Sales_Tax_Renderer_Postcode',
            'default'       => '*',
        ), 'period');

        $this->sortColumnsByOrder();
        return $this;
    }
}
