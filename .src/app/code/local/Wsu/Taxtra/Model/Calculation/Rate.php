<?php
class Wsu_Taxtra_Model_Calculation_Rate extends Mage_Tax_Model_Calculation_Rate
{

    protected function _beforeSave()
    {
        $feed_save = Mage::registry('feed_save');
        if (true !== $feed_save) {
            $history = Mage::app()->getRequest()->getParam('history');
            $this->setUpdateType("hand");

            // create history
            $admin = Mage::getSingleton('admin/session')->getUser();
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
                'to'=>$this->getRate(),
                'how'=>$this->getUpdateType(),
                'who'=>$admin->getId()
            ];
            $this->setUpdateHistory(json_encode($history));
            $remittee_id = Mage::app()->getRequest()->getParam('remittee_id');
            $this->setRemitteeId($remittee_id);
        } else {
            Mage::unregister('feed_save');
        }

        //var_dump($this);// @codingStandardsIgnoreLine
        //die();// @codingStandardsIgnoreLine
        parent::_beforeSave();
        return $this;
    }
}
