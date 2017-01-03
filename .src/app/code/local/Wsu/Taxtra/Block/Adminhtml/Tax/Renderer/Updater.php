<?php
class Wsu_Taxtra_Block_Adminhtml_Tax_Renderer_Updater extends Varien_Data_Form_Element_Abstract
{
    protected $_element;

    public function getElementHtml()
    {
    //$this->getLastUpdate();
        $html = '<button id="update"><span><span>Update</span></span></button>';
        $tax_id = Mage::app()->getRequest()->getParam('rate');
        $model = Mage::getModel('tax/calculation_rate')->load($tax_id);
        ob_start();
    ?>
    <textarea name="history" style="display:none;"><?=$model->getUpdateHistory();?></textarea>
    <p>The last update for this rate was <strong><?=$model->getLastUpdate();?></strong></p>
<script>
(function($){
    var post_url = "<?=Mage::helper("adminhtml")->getUrl('taxtra/adminhtml_rate/updater');?>";
    $(document).ready(function(){
        $("#update").on("click", function(e){
            e.preventDefault();
            $.ajaxSetup({
                cache:false
            });
            $.getJSON( post_url, { "tax_id": $('[name="tax_calculation_rate_id"]').val(), "used_feed":1 }, function(data){
                if( false === data.error ){
                    $.each( data.data, function( idx, val ){
                        var tar = $("[name='"+idx+"']");
                        if( 0 === tar.next(".was").length ){
                            tar.after("<span class='was'></span>");
                        }
                        tar.next(".was").text(tar.val());
                        tar.val(val);
                    });
                    if( 0 === $("#base_fieldset .update_info").length ){
                        $("#base_fieldset").append("<span class='update_info'>"+data.info+"</span>");
                    } else {
                        $("#base_fieldset .update_info").text(data.info);
                    }
                    //$("#base_fieldset").append("<input name='used_feed' value='1'/>");
                }else{
                    if( 0 === $("#base_fieldset .update_massage").length ){
                        $("#base_fieldset").append("<span class='update_massage'>"+data.data+"</span>");
                    } else {
                        $("#base_fieldset .update_massage").text(data.data);
                    }
                }
            });
        });
    });
}(jQuery));
</script>

<h3>Update History</h3>
<table  style="width:100%">
    <thead>
        <tr>
            <th style="padding: 5px;">Date</th>
            <th style="padding: 5px;">Rate from</th>
            <th style="padding: 5px;">Rate as</th>
            <th style="padding: 5px;">Done by</th>
            <th style="padding: 5px;">User</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $history = $model->getUpdateHistory();
        if (!empty($history)) {
            $history = (array)json_decode($history);
            foreach ($history as $item) {
                $item = (array)$item;
                ?><tr>
                    <td  style="padding: 5px;"><?=$item['date'];?></td>
                    <td style="padding: 5px;"><?=$item['from'];?></td>
                    <td style="padding: 5px;"><?=$item['to'];?></td>
                    <td style="padding: 5px;"><?=$item['how'];?></td>
                    <td style="padding: 5px;"><?=$item['who'];?></td>
                </tr><?php
            }
        } else {
            ?><tr>
            <td col_span="5" style="padding: 5px;"><h4>No history</h4></td>
            </tr><?php
        }?></tbody>
            </table><?php

            $html .= ob_get_clean();

            $html = ' <div>'.$html.'</div>';

            return $html;
    }
}
