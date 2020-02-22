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
        success:function(data){
            var res = JSON.parse(data);
            console.log(res);
            if(data){
                alert("New Product Added");
                $('#consulting_client_point').selectpicker('destroy');
                $('#consulting_client_point').append('<option val="'+res[0].id+'">'+res[0].contract_product+'</option>');
                $('#consulting_client_point').selectpicker('refresh');
                
                
            }
        }
    })
});


</script>
