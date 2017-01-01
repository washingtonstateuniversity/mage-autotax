<?php

class Wsu_Taxtra_Block_Adminhtml_Taxtrareports_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('taxtrareports_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('wsu_taxtra')->__('Item Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
          'label'     => Mage::helper('wsu_taxtra')->__('Item Information'),
          'title'     => Mage::helper('wsu_taxtra')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('taxtrareports/adminhtml_taxtrareports_edit_tab_form')->toHtml(),
        ));
     
        return parent::_beforeToHtml();
    }
}
