<?php
class Wsu_Taxtra_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {

        /*
    	 * Load an object by id
    	 * Request looking like:
    	 * http://site.com/taxtrareports?id=15
    	 *  or
    	 * http://site.com/taxtrareports/id/15
    	 */
        /*
		$taxtrareports_id = $this->getRequest()->getParam('id');

  		if($taxtrareports_id != null && $taxtrareports_id != '')	{
			$taxtrareports = Mage::getModel('taxtrareports/taxtrareports')->load($taxtrareports_id)->getData();
		} else {
			$taxtrareports = null;
		}
		*/

         /*
    	 * If no param we load a the last created item
    	 */
        /*
    	if($taxtrareports == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$taxtrareportsTable = $resource->getTableName('taxtrareports');

			$select = $read->select()
			   ->from($taxtrareportsTable,array('taxtrareports_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;

			$taxtrareports = $read->fetchRow($select);
		}
		Mage::register('taxtrareports', $taxtrareports);
		*/


        $this->loadLayout();
        $this->renderLayout();
    }
}
