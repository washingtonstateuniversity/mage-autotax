<?php
class Wsu_Taxtra_Block_Adminhtml_Report_Sales_Tax_Renderer_Postcode extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row)
    {
        $html = '';
        $period = $row->getData('code');
        //var_dump($period);// @codingStandardsIgnoreLine
        if ( null !==  $period) {
            $taxCode = $row->getData('code');
            $rawTax= Mage::getSingleton('tax/calculation_rate')->loadByCode($taxCode);
            $html = $rawTax->getData("tax_postcode");
        }
        return $html;
    }
}
