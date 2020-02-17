<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="modal fade" id="product" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('contracts/product'), array('id'=>'contract-product-form')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <!-- <span class="edit-title"><?php echo _l('contract_product_edit'); ?></span> -->
                    <span class="add-title"><?php echo _l('new_contract_product'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <label><?php echo _l('new_contract_product'); ?></label>
                <input type="text" name="contract_product" id="contract_product" class="form-control" />
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="close_product"><?php echo _l('close'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
  
function new_product(){
    $('#product').modal('show');
    $('.edit-title').addClass('hide');
}

$('#contract-product-form').submit(function(e){
    e.preventDefault();
    $.ajax({
        url:'<?php echo admin_url('contracts/product') ?>',
        method:'POST',
        data: new FormData(this),
        contentType:false,
        processData:false,
        success:function(id){
            if(id){
                alert("New Product Added");
                // $('#consulting_client_point').load(location.href + " #consulting_client_point");
                // $('#consulting').load(document.URL +  ' #consulting');
                
            }
        }
    })
});

$('#close_product').click(function(){
    // $('#consulting_client_point').load(document.URL + ' #consulting_client_point');
    location.reload();
});

</script>
