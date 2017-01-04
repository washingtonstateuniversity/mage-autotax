<?php

class Wsu_Taxtra_Model_Observer
{
    public function taxrateField($observer)
    {
        $id = Mage::app()->getRequest()->getParam('rate');
        $block = $observer->getEvent()->getBlock();
        if (!isset($block)) {
            return $this;
        }
        if ($block->getType() == 'adminhtml/tax_rate_form') {
            $enabled = Mage::getStoreConfig('wsu_taxtra/updater/updater_enable');

            if ($enabled) {
                //get CMS model with data
                $model = Mage::getModel('tax/calculation_rate')->load($id);

                $form = $block->getForm();
                $fieldset = $form->getElement('base_fieldset');

                $fieldset->addField('label', 'label', array(
                    'value'     => Mage::helper('wsu_taxtra')->__('Label Text'),
                ));


                //add new field
                $fieldset->addField('remittee_id', 'text', array(
                    'name'      => 'remittee_id',
                    'label'     => 'remittee_id',
                    'title'     => 'remittee_id',
                    'disabled'  => false,
                    //set field value
                    'value'     => $model->getRemitteeId()
                ));


                $fieldset->addType('customtype', 'Wsu_Taxtra_Block_Adminhtml_Tax_Renderer_Updater');
                $fieldset->addField('updater', 'customtype', array(
                    'name'      => 'updater',
                ));
            }
        }
    }
}
