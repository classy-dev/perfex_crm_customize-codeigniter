<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
     <div class="col-md-12">
      <div class="panel_s">
       
      <div class="panel-body">
        <?php //print_r($info);?>
        <!--  start product table -->
           <div class="col-md-12">
            <div class="container box" style="margin-top: 40px; width: 100% !important;">
              <h2 style="text-align: center;">Subscription Settings</h2>
              <div class="table-responsive">
                <button type="button" data-toggle="modal" data-target="#Blocks_Modal" id="add" class="btn btn-info btn-xs" style="margin-bottom:10px;">Add</button>
                <table id="products" class="table table-bordered table-scriped" style="width: 100% !important">
                  <thead>
                    <tr>
                      <!-- <th >Id</th> -->
                      <th style="width:60%;">Blocks Content</th>
                      <th style="width:10%;" >Price</th>
                      <th style="width:10%;" >Currency</th>
                      <th style="width:10%;">Edit</th>
                      <th style="width:10%;">Delete</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
           </div>
               
                <!-- end product table -->
      </div>
    </div>
  </div>
  <?php init_tail(); ?>
  <div id="Blocks_Modal" class="modal fade">
  <div class="modal-dialog">
    <form method="post" id="blocks_form">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">
            &times;
          </button>
          <h4 class="modal-title">Add New Block</h4>
        </div>
        <div class="modal-body">
          <label>Block Content</label>
          <input type="text" name="content" id="content" class="form-control" />
          <br>
          <div style="display: flex;">
            <div>
              <label>Price</label>
              <input type="text" name="price" id="price" class="form-control"  />
            </div>
            
            <div style="margin:auto;">
              <label>Currency</label>
              <select class="form-control" id="currency" name="currency">
                <option value=""></option>
                <option value="EUR">EUR</option>
                <option value="USD">USD</option>
              </select>
            </div>

            <div style="margin:auto;">
              <label>Index</label>
              <select class="form-control" id="index" name="index">
                <option value=""></option>
                <!-- <option value="Parent">Parent</option> -->
                <option value="Child">Child</option>
              </select>
            </div>
            
          </div>
          
        </div>
        <div class="modal-footer">
          <input type="hidden" name="block_id" id="block_id">
          <input type="submit" class="btn btn-success" name="action" id="action" value="add"/>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>
</body>
</html>
<script type="text/javascript">

  
  $('#add').click(function(){
    $('.modal-title').html('Add New Block');
    $('#content').val('');
    $('#price').val('');
    $('#currency').val('');
    $('#action').val('add');
  });
  
  $(document).ready(function(){
  
    var dataTable = $('#products').DataTable({
      "processing":true,
      "serverSide":true,
      "order":[],
      "ajax":{
        url:"<?php echo admin_url('subscriptions/blocks_load');?>",
        type:"POST",
        dataType : "json",
        data:{
          <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>"
        },

        dataSrc: function ( data ) {
             $('.dataTables_wrapper').removeClass('table-loading');
             return data.data;
        }
      },    
      "columns" : [
        {data:"content"},
        {data:"price"},
        {data:"currency"},
        {data:"edit"},
        {data:"delete"}
       ]
    });


    // $('#blocks_form').submit(function(event){
    $(document).on('submit','#blocks_form',function(event){
        event.preventDefault();
        
        var content = $('#content').val();
        var price = $('#price').val();
        var currency = $('#currency option:selected').val();
        var index = $('#index option:selected').val();
        var action = $('#action').val();
        var block_id =$('#block_id').val();
        // console.log(action);
        // console.log(this);
        // var formElement = document.getElementById("blocks_form");
        // console.log($(this).serialize());
        // console.log(currency);

        if(content != ''){
          $.ajax({
            url:'<?php echo admin_url('subscriptions/blocks_action') ?>',
            method:'POST',
            data:{
              <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",content:content,price:price,currency:currency,index:index,action:action,block_id:block_id
            },
            success:function(data)
            {
              alert(data);
              // console.log($('#blocks_form')[0]);
              $('#Blocks_Modal').modal('hide');
              dataTable.ajax.reload();
              }
          });
        }
        else{
          alert("Contents Are Required!");
        }

    });
    

    $(document).on('click','.delete',function(){

        // $('#Blocks_Modal').modal('show');
        var block_id = $(this).attr("id");
        if (confirm("Are you sure you want to delete this?")) {
          $.ajax({
              url:'<?php echo admin_url('subscriptions/blocks_single_remove') ?>',
              method:'POST',
              data:{<?php  echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>", block_id:block_id},
              success:function(data)
              {
                alert(data);
                dataTable.ajax.reload();
              }
          });
        }
        else{
          return false;
        }
    });

    $(document).on('click','.edit', function(){

      var block_id = $(this).attr('id');
      $.ajax({
        url:'<?php echo admin_url('subscriptions/blocks_single_get')?>',
        method:'POST',
        data:{<?php  echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>", block_id:block_id},
        dataType:"json",
        success:function(data)
        {
          console.log(data);
          $('#Blocks_Modal').modal('show');
          $('.modal-title').text('Edit Current Block');
          $('#block_id').val(block_id);
          $('#content').val(data.content);
          $('#price').val(data.price);
          $('#currency').val(data.currency);
          $('#index').val(data.index);
          $('#action').val('edit');
        }

      });

    });


  });

</script>
