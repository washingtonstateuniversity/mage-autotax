<?php
class Wsu_Taxtra_Block_Adminhtml_Taxtrareports_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('taxtrareports_form', array('legend'=>Mage::helper('wsu_taxtra')->__('Item information')));

        $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('wsu_taxtra')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
        ));

        $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('wsu_taxtra')->__('File'),
          'required'  => false,
          'name'      => 'filename',
        ));

        $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('wsu_taxtra')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('wsu_taxtra')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('wsu_taxtra')->__('Disabled'),
              ),
          ),
        ));

        $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('wsu_taxtra')->__('Content'),
          'title'     => Mage::helper('wsu_taxtra')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
        ));

        if (Mage::getSingleton('adminhtml/session')->getTaxtrareportsData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getTaxtrareportsData());
            Mage::getSingleton('adminhtml/session')->setTaxtrareportsData(null);
        } elseif (Mage::registry('taxtrareports_data')) {
            $form->setValues(Mage::registry('taxtrareports_data')->getData());
        }
        return parent::_prepareForm();
    }
}
