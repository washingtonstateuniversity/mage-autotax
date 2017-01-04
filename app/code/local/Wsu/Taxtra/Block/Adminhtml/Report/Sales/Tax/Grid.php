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
        $this->addColumnAfter('remitter_id', array(
            'header'        => Mage::helper('tax')->__('Remitter'),
            'align'         =>'left',
            'index'         => 'remitter_id',
            'type'          => 'text',
            'width'         => '100',
            'renderer'      => 'Wsu_Taxtra_Block_Adminhtml_Report_Sales_Tax_Renderer_Remitter',
            'default'       => '*',
        ), 'tax_postcode');
        $this->addColumnAfter('remittee_id', array(
            'header'        => Mage::helper('tax')->__('Remittee'),
            'align'         =>'left',
            'index'         => 'remittee_id',
            'type'          => 'text',
            'width'         => '100',
            'renderer'      => 'Wsu_Taxtra_Block_Adminhtml_Report_Sales_Tax_Renderer_Remittee',
            'default'       => '*',
        ), 'remitter_id');

        $this->sortColumnsByOrder();
        return $this;
    }
}
