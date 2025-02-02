<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> Option Management
        <small>Add, Edit, Delete</small>
      </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>Option/add"><i class="fa fa-plus"></i> Add New Option</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                    $this->load->helper('form');
                    $error = $this->session->flashdata('error');
                    if($error)
                    {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('error'); ?>                    
                </div>
                <?php } ?>
                <?php  
                    $success = $this->session->flashdata('success');
                    if($success)
                    {
                ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
                <?php } ?>
                
                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Option List</h3>
                   
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover" id="feedbackTable">
                 <thead>    <tr>
                        <th>Id</th>
                        <th>Election</th>
                        <th>Question</th>
                        <th>Option</th>
                         <th>Option Order</th>
                         <th>Status</th>
                        <th>Created On</th>
                        <th class="text-center">Actions</th>
                    </tr> </thead> <tbody>
                    <?php
                    if(!empty($records))
                    {
                        foreach($records as $record)
                        {
                            
                            $this->db->select('*');
                            $this->db->from('election');
                            $this->db->where('id', $record->election);
                            $queryq = $this->db->get();
                            $queryqr=$queryq->row();
                            
                            $this->db->select('*');
                            $this->db->from('question');
                            $this->db->where('id', $record->question);
                            $queryq11 = $this->db->get();
                            $queryqr1=$queryq11->row();
                            
                    ?>
                    <tr>
                        
                       <td><?php  echo @$record->id;  ?></td>
                        <td><?php if(@$queryqr->name!='') { echo @$queryqr->name; } ?></td>
                        <td><?php if(@$queryqr1->name!='') { echo @$queryqr1->name; } ?></td>
                          <td><?php echo $record->option ?></td>
                          <td><?php echo $record->option_order ?></td>
                          <td>
                              <?php if($record->status=='1'){ ?>
                          <button onclick="updatestatus('0','<?php echo $record->id;  ?>')" class="small-box bg-green">Active</button>
                          <?php }else { ?>
                          <button onclick="updatestatus('1','<?php echo $record->id;  ?>')" class="small-box bg-red">Deactive</button>
                          <?php }?>
                          
                          
                          </td>
                        <td><?php echo date("d-m-Y", strtotime($record->createdDtm)) ?></td>
                        <td class="text-center">
                        <a class="btn btn-sm btn-success"  style="    background-color: #00a65a !important; color:white" href="<?php echo base_url().'option/view/'.$record->id; ?>" title="Edit"><i class="fa fa-eye"></i></a>

                            <a class="btn btn-sm btn-info" href="<?php echo base_url().'option/edit/'.$record->id; ?>" title="Edit"><i class="fa fa-pencil"></i></a>
                            <a class="btn btn-sm btn-danger deleteBooking" href="#" onclick="deleteEmoji(<?php echo $record->id; ?>)" title="Delete"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php
                        }
                    }
                    ?> </tbody>
                  </table>
                  
                </div><!-- /.box-body -->
                
              </div><!-- /.box -->
            </div>
        </div>
    </section> 
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('ul.pagination li a').click(function (e) {
            e.preventDefault();            
            var link = jQuery(this).get(0).href;            
            var value = link.substring(link.lastIndexOf('/') + 1);
            jQuery("#searchList").attr("action", baseURL + "booking/bookingListing/" + value);
            jQuery("#searchList").submit();
        });
    });
    
    function deleteEmoji(emojiId) {
    if (confirm("Are you sure you want to delete this candidate?")) {
        $.ajax({
            url: '<?php echo base_url(); ?>option/delete', // URL of the delete method in the controller
            type: 'POST',
            data: {
                id: emojiId,
            },
            success: function(response) {
                window.location.reload();

            },
            error: function() {
                alert('Error deleting candidate');
            }
        });
    }
}
function updatestatus(a,b){
   
    if (confirm("Are you sure you want to update this Option?")) {
        $.ajax({
            url: '<?php echo base_url(); ?>Option/update', // URL of the delete method in the controller
            type: 'POST',
            data: {
                id: b,
                 status: a,
            },
            success: function(response) {
                window.location.reload();

            },
            error: function() {
                alert('Error deleting candidate');
            }
        });
    }
    
}

</script>
