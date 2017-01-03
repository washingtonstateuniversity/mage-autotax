<?php
class Wsu_Taxtra_Block_Adminhtml_Tax_Rate_Grid extends Mage_Adminhtml_Block_Tax_Rate_Grid
{
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('tax_calculation_rate_id');
        $this->getMassactionBlock()->setFormFieldName('tax_id');
        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> Mage::helper('tax')->__('Delete'),
            'url'  => $this->getUrl('taxtra/adminhtml_rate/massDelete', array('' => '')),
            ));
        $this->getMassactionBlock()->addItem('update', array(
            'label'=> Mage::helper('tax')->__('Update'),
            'url'  => $this->getUrl('taxtra/adminhtml_rate/massUpdate', array('' => '')),
            ));
        return $this;
    }
}
