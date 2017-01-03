<?php
class Wsu_Taxtra_Adminhtml_RateController extends Mage_Adminhtml_Controller_Action
{
    protected function _isAllowed()
    {
        return true; //do this correctly
    }

    /**
    * @access protected
    */
    protected function _sendJsonResponse($result)
    {
        $this->getResponse()->setHeader('Content-type', 'application/json', true);
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function massDeleteAction()
    {
        $taxIds = $this->getRequest()->getParam('tax_id');      // $this->getMassactionBlock()->setFormFieldName('tax_id'); from Mage_Adminhtml_Block_Tax_Rate_Grid
        if (!is_array($taxIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('tax')->__('Please select tax(es).'));
        } else {
            try {
                $tax_collection = Mage::getModel('tax/calculation_rate')->getCollection()
                    ->addFieldToFilter('tax_calculation_rate_id', array("in" => array($taxIds)))
                    ->walk('delete');

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tax')->__(
                        'Total of %d record(s) were deleted.',
                        count($taxIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirectReferer();
    }
    protected function checkTaxArray()
    {
        $taxIds = $this->getRequest()->getParam('tax_id');
        if (!is_array($taxIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('tax')->__('Please select tax(es).'));
            $this->_redirectReferer();
        }
        return $taxIds;
    }

    protected function getUrlUpdate($url)
    {
        // @codingStandardsIgnoreStart
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url
            )
        );
        $response = curl_exec($curl);
        curl_close($curl);
        // @codingStandardsIgnoreEnd
        return $response;
    }


    public function massUpdateAction()
    {
        $taxIds = $this->checkTaxArray();

        try {
            $tax_collection = Mage::getModel('tax/calculation_rate')->getCollection()
                ->addFieldToFilter('tax_calculation_rate_id', array("in" => array($taxIds)));
            $i = 0;
            foreach ($tax_collection as $tax_rate) {
                $model = $tax_rate;//Mage::getModel('tax/calculation_rate')->load($tax_id);

                $update_url = Mage::getStoreConfig('wsu_taxtra/updater/update_url');

                $url = $this->buildUpdateUrl($model, $update_url);

                $response = $this->getUrlUpdate($url);

                $is_percent = Mage::getStoreConfig('wsu_taxtra/updater/feed_rate_type');

                $update_response_pattern = Mage::getStoreConfig('wsu_taxtra/updater/update_response_pattern');

                preg_match_all($update_response_pattern, $response, $matches, PREG_PATTERN_ORDER);

                $changed = [];
                foreach ($matches as $key => $value) {
                    if ('zip_is_range' === $key && !empty($value)) {
                        $changed['zip_is_range'] = $matches['zip_is_range'][0];
                        $model->setZipIsRange($matches['zip_is_range'][0]);
                        break;
                    } elseif ('zip_from' === $key && !empty($value)) {
                        $changed['zip_from'] = $matches['zip_from'][0];
                        $model->setZipFrom($matches['zip_from'][0]);
                        break;
                    } elseif ('zip_to' === $key && !empty($value)) {
                        $changed['zip_to'] = $matches['zip_to'][0];
                        $model->setZipTo($matches['zip_to'][0]);
                        break;
                    } elseif ('rate' === $key && !empty($value)) {
                        $rate = (float)$matches['rate'][0];
                        if ("0" === $is_percent) {
                            $rate = $rate * 100;
                        }
                        $changed['rate'] = $rate;
                        $model->setRate($rate);
                    }
                }
                if (!empty($changed)) {
                    $model->setUpdateType("feed");
                    $model->setLastUpdate(date('Y-m-d H:i:s'));
                    $model->setUpdateHistory($this->setHistory($model));
                    Mage::register('feed_save', true);
                    $model->save();// @codingStandardsIgnoreLine
                    $i++;
                }
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('tax')->__(
                    'Total of %d record(s) out of %d were updated.',
                    $i,
                    count($taxIds)
                )
            );
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        $this->_redirectReferer();
    }

    protected function setHistory($model)
    {
        // create history
        $admin = Mage::getSingleton('admin/session')->getUser();
        $history = $model->getUpdateHistory();
        if (!empty($history)) {
            $history = is_array($history) ? $history : (array)json_decode($history);
            $idx = count($history) - 1;
            $idx = $idx >= 0 ? $idx : 0;
            $last = (array)$history[$idx];
        } else {
            $history = [];
            $idx = -1;
        }

        $history[] = [
            'date'=>date("Y-m-d H:i:s"),
            'from'=> $idx >= 0 ? $last['to'] : 'first',
            'to'=>$model->getRate(),
            'how'=>$model->getUpdateType(),
            'who'=>$model->getId()
        ];
        return json_encode($history);
    }

    protected function buildUpdateUrl($model, $url)
    {
        $params = [ 'tax_calculation_rate_id', 'tax_country_id', 'tax_region_id', 'tax_postcode', 'code' ];
        foreach ($params as $key) {
            if (false !== strpos($url, $key)) {
                switch ($key) {
                    case 'tax_calculation_rate_id':
                        $val = $model->getTaxCalculationRateId();
                        break;
                    case 'tax_country_id':
                        $val = $model->getTaxCountryId();
                        break;
                    case 'tax_region_id':
                        $val = $model->getTaxRegionId();
                        break;
                    case 'tax_postcode':
                        $val = $model->getTaxPostcode();
                        break;
                    case 'code':
                        $val = $model->getCode();
                        break;
                }
                $url = str_replace("{{".$key."}}", $val, $url);
            }
        }
        return $url;
    }

    protected function getTaxId()
    {
        $tax_id = $this->getRequest()->getParam('tax_id');
        if ("" === $tax_id) {
            $responseData['error'] = true;
            $responseData['data'] = "no tax_id";
            $this->_sendJsonResponse($responseData);
            exit();// @codingStandardsIgnoreLine
        }
        return $tax_id;
    }

    public function updaterAction()
    {
        $responseData = [];
        $tax_id = $this->getTaxId();

        $responseData['error'] = false; // set this to logic against later
        $responseData['saved'] = false;
        $responseData['tax_id'] = $tax_id;

        $model = Mage::getModel('tax/calculation_rate')->load($tax_id);

        $update_url = Mage::getStoreConfig('wsu_taxtra/updater/update_url');

        $url = $this->buildUpdateUrl($model, $update_url);

        $responseData['info'] = ' | url: '.$url;
        try {
            $response = $this->getUrlUpdate($url);
        } catch (Exception $e) {
            $responseData['error'] = true;
            $responseData['data'] = $e->getMessage();
        }

        if (false === $responseData['error']) {
            $is_percent = Mage::getStoreConfig('wsu_taxtra/updater/feed_rate_type');
            $responseData['info'] = $responseData['info'] . ' | feed_rate_type as is_percent: '.$is_percent;
            $update_response_pattern = Mage::getStoreConfig('wsu_taxtra/updater/update_response_pattern');
            $responseData['info'] = $responseData['info'] . ' | update_response_pattern: '.$update_response_pattern;
            preg_match_all($update_response_pattern, $response, $matches, PREG_PATTERN_ORDER);

            $changed = [];
            foreach ($matches as $key => $value) {
                if ('zip_is_range' === $key && !empty($value)) {
                    $changed['zip_is_range'] = $matches['zip_is_range'][0];
                    $model->setZipIsRange($matches['zip_is_range'][0]);
                    break;
                } elseif ('zip_from' === $key && !empty($value)) {
                    $changed['zip_from'] = $matches['zip_from'][0];
                    $model->setZipFrom($matches['zip_from'][0]);
                    break;
                } elseif ('zip_to' === $key && !empty($value)) {
                    $changed['zip_to'] = $matches['zip_to'][0];
                    $model->setZipTo($matches['zip_to'][0]);
                    break;
                } elseif ('rate' === $key && !empty($value)) {
                    $rate = (float)$matches['rate'][0];
                    $responseData['info'] = $responseData['info'] . ' | match rate as float: '.number_format($rate, 4);
                    if ("0" === $is_percent) {
                        $rate = $rate * 100;
                    }
                    $responseData['info'] = $responseData['info'] . ' | changed rate: '.number_format($rate, 4);
                    $changed['rate'] = $rate;
                    $model->setRate($rate);
                }
            }
            if (!empty($changed)) {
                $model->setUpdateType("feed");
                $model->setLastUpdate(date('Y-m-d H:i:s'));
                $model->setUpdateHistory($this->setHistory($model));
                Mage::register('feed_save', true);
                $model->save();
                $responseData['saved'] = true;
            }
            $responseData['error'] = false;
            $responseData['data'] = $changed;/**/
        }

        $this->_sendJsonResponse($responseData);
    }
}
