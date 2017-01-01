<?php

class Wsu_Taxtra_Block_Adminhtml_Taxtrareports_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'taxtrareports';
        $this->_controller = 'adminhtml_taxtrareports';

        $this->_updateButton('save', 'label', Mage::helper('wsu_taxtra')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('wsu_taxtra')->__('Delete Item'));

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('taxtrareports_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'taxtrareports_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'taxtrareports_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if (Mage::registry('taxtrareports_data') && Mage::registry('taxtrareports_data')->getId()) {
            return Mage::helper('wsu_taxtra')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('taxtrareports_data')->getTitle()));
        } else {
            return Mage::helper('wsu_taxtra')->__('Add Item');
        }
    }
}
