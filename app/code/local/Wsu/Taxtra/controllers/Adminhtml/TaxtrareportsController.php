<?php

class Wsu_Taxtra_Adminhtml_TaxtrareportsController extends Mage_Adminhtml_Controller_action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('taxtrareports/items')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Wsu - Tax Report'), Mage::helper('adminhtml')->__('Wsu - Advanced Tax Report'));

        return $this;
    }

    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();

        Mage::getSingleton('core/session')->addNotice('This report is grouped by city. When a city belongs in more than one county, only the first county found will show up in the row.
        For a report that aggregates by county use the specific report designed for that purpose.');
    }

    public function editAction()
    {
        $id     = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('wsu_taxtra/taxtrareports')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }

            Mage::register('taxtrareports_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('taxtrareports/items');

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('taxtrareports/adminhtml_taxtrareports_edit'))
                ->_addLeft($this->getLayout()->createBlock('taxtrareports/adminhtml_taxtrareports_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('wsu_taxtra')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function exportCsvAction()
    {
        $fileName   = 'taxtrareports.csv';
        $content    = $this->getLayout()->createBlock('taxtrareports/adminhtml_taxtrareports_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'taxtrareports.xml';
        $content    = $this->getLayout()->createBlock('taxtrareports/adminhtml_taxtrareports_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType = 'application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        //exit();//die;
    }
}
