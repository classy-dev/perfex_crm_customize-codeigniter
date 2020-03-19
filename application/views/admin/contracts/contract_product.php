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

<div class="modal fade" id="delete-product" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <?php echo form_open(admin_url('contracts/delete_product'), array('id'=>'contract-delete-product-form')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span class="add-title"><?php echo _l('delete_contract_product'); ?></span>
                </h4>
            </div>
            <div class="modal-body">
                <div class="form-group" id="products_list">
                    <?php
                        if(isset($products))
                            foreach ($products as $key => $product) {?>
                                <div class="checkbox checkbox-primary"  style="margin-left: 5%">
                                    <input type="checkbox"  class="product" id="<?php echo $product['id'];?>" name="<?php echo $product['id'];?>">

                                    <label id="<?php echo ("contract_product".$product['id']);?>" for="<?php echo ($product['id']);?>"><?php echo $product['contract_product']; ?></label> 
                                </div>
                    <?php }?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info"><?php echo _l('delete'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal" id="close_product"><?php echo _l('close'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>

var selectedProduct = [];
function new_product(){
    $('#product').modal('show');
    // $('.edit-title').addClass('hide');
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
                $('#contract_product').val("");
                $('#consulting_client_point').selectpicker('destroy');
                $('#consulting_client_point').append('<option val="'+res[0].id+'">'+res[0].contract_product+'</option>');
                $('#consulting_client_point').selectpicker('refresh');
                
                $('#products_list').append(
                    '<div class="checkbox checkbox-primary"  style="margin-left: 5%"><input type="checkbox"  class="product" id="'+res[0].id+'" name="'+res[0].id+'"><label id="contract_product'+res[0].id +'" for="'+res[0].id+'">'+res[0].contract_product+'</label></div>'

                    );
                $('.product').change(function(){
                    // console.log(this);
                    if(this.checked) {
                        // console.log($(this).attr('id'));
                        selectedProduct.push($(this).attr('id'));
                    }
                    
                });
                
            }
        }
    })
});

function delete_product(){
    $('#delete-product').modal('show');
}


$('.product').change(function(){
    // console.log(this);
    if(this.checked) {
        // console.log($(this).attr('id'));
        selectedProduct.push($(this).attr('id'));
    }
    
})
$('#contract-delete-product-form').submit(function(e){
    e.preventDefault();
    console.log(selectedProduct);
    $.ajax({
        url:'<?php echo admin_url('contracts/delete_product')?>',
        method: 'POST',
        data: {
              <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>", selectedProduct:selectedProduct
            },
        success: function(data){
            var res = JSON.parse(data);
            // console.log(res);
            // $('#products_list').html("");
            document.getElementById('products_list').innerHTML = "";
            $('#consulting_client_point').selectpicker('destroy');
            $('#consulting_client_point').html("");  
            for(i = 0; i<res.length; i++){
                $('#products_list').append(
                    '<div class="checkbox checkbox-primary"  style="margin-left: 5%"><input type="checkbox"  class="product" id="'+res[i].id+'" name="'+res[i].id+'"><label id="contract_product'+res[i].id +'" for="'+res[i].id+'">'+res[i].contract_product+'</label></div>'

                    );

                $('#consulting_client_point').append('<option val="'+res[i].id+'">'+res[i].contract_product+'</option>');
                
            }
            $('#consulting_client_point').selectpicker('refresh');
            selectedProduct = [];
            $('.product').change(function(){
                if(this.checked) {
                    selectedProduct.push($(this).attr('id'));
                }
                
            })
            // $("#products_list").load(window.location.href + " #products_list" );
            // $.each(data, function(index, value){
            //     console.log(value);
            // });
            alert("products deleted successfully");
            // $('#delete-product').modal('hide');
            // location.reload();

        }
    })
});

</script>
