<?php
$bookingId = $bookingInfo->id;
$roomName = $bookingInfo->name;


 $this->db->select('*');
        $this->db->from('election');
        $this->db->where('id', $bookingInfo->election);
        $queryq = $this->db->get();
        $queryqr=$queryq->row();
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> Question Management
        <small>View Booking</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">View Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                        <div class="box-body">
                        <div class="row">
                             <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="roomName">Id</label>
                                        <input type="text" readonly  class="form-control required" value="<?php echo $bookingId; ?>" id="name" name="name" maxlength="256" />

                                        
                                    </div>
                                    
                                </div>
                                <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="roomName">Election</label>
                                        <input type="text"   readonly class="form-control required" value="<?php if(@$queryqr->name!='') { echo @$queryqr->name; } ?>" id="name" name="name" maxlength="256" />

                                        
                                    </div>
                                    
                                </div>
                                <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="roomName"> Question</label>
                                        <input type="text" readonly  class="form-control required" value="<?php echo $roomName; ?>" id="name" name="name" maxlength="256" />
                                    </div>
                                    
                                </div>
                                <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="roomName"> Status</label></br>
                                        <?php if($bookingInfo->status=='1'){ ?>
                          <button onclick="updatestatus('0','<?php echo $bookingInfo->id;  ?>')" class="small-box bg-green">Active</button>
                          <?php }else { ?>
                          <button onclick="updatestatus('1','<?php echo $bookingInfo->id;  ?>')" class="small-box bg-red">Deactive</button>
                          <?php }?>
                          
                                    </div>
                                    
                                </div>
                                
                                
                            </div>
                        </div><!-- /.box-body -->
    
                        
                </div>
            </div>
            <div class="col-md-4">
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
    </section>
</div>