<?php
class Wsu_Taxtra_Block_Adminhtml_Report_Sales_Tax_Renderer_Remitter extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)// @codingStandardsIgnoreLine
    {
        $html = '';
        $taxCode = $row->getData('code');
        if (null !==  $taxCode) {
            $account_id = Mage::getStoreConfig('wsu_taxtra/updater/account_id');
            if (null !==  $account_id) {
                $html = $account_id;
            }
        }
        return $html;
    }
}
