<?php
class Wsu_Taxtra_Block_Adminhtml_Report_Sales_Tax_Grid extends Mage_Adminhtml_Block_Report_Sales_Tax_Grid
{
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
    // @codingStandardsIgnoreLine
    protected function _prepareCollection()
    {
        /*$filterData = $this->getFilterData();
        if(!$filterData->hasData('order_statuses')) {
            $orderConfig = Mage::getModel('sales/order_config');
            $statusValues = array();
            $canceledStatuses = $orderConfig->getStateStatuses(Mage_Sales_Model_Order::STATE_CANCELED);
            foreach ($orderConfig->getStatuses() as $code => $label) {
                if (!isset($canceledStatuses[$code])) {
                    $statusValues[] = $code;
                }
            }
            $filterData->setOrderStatuses($statusValues);
        }*/
        // @codingStandardsIgnoreLine
        //var_dump(parent::_prepareCollection());
        return parent::_prepareCollection();
    }
}
