<?php
$bookingId = $bookingInfo->id;
$roomName = $bookingInfo->name;


 $this->db->select('*');
        $this->db->from('election');
        $this->db->where('id', $bookingInfo->election);
        $queryq = $this->db->get();
        $queryqr=$queryq->row();
$this->db->select('*');
$this->db->from('party');
$this->db->where('id', $bookingInfo->party);
$queryq11 = $this->db->get();
$queryqr1=$queryq11->row();

$this->db->select('*');
$this->db->from('zone');
$this->db->where('id', $bookingInfo->zone);
$queryq110 = $this->db->get();
$queryqr10=$queryq110->row();
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> Candidate Management
        <small>Add / Edit Candidate</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter Candidate Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <form role="form" action="<?php echo base_url() ?>candidate/editemoji" method="post" id="editBooking" role="form" enctype="multipart/form-data">
                        <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="roomName">Id</label>
                                        <input type="text" readonly  class="form-control required" value="<?php echo $bookingId; ?>" name="bookingId" id="bookingId" />

                                    </div>
                                    
                                </div>
                             <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="roomName">Election</label>
                                        <input type="text" readonly  class="form-control required" value="<?php if(@$queryqr->name!='') { echo @$queryqr->name; } ?>" name="bookingId" id="bookingId" />

                                       
                                    </div>
                                    
                                </div>
                                <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="roomName">Zone</label>
                                     <input type="text" readonly  class="form-control required" value="<?php if(@$queryqr1->name!='') { echo @$queryqr1->name; } ?>" name="bookingId" id="bookingId" />

                                </div>
                                </div>
                                <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="roomName">Party</label>
                                       <input type="text" readonly  class="form-control required" value="<?php if(@$queryqr10->name!='') { echo @$queryqr10->name; } ?>" name="bookingId" id="bookingId" />

                                    </div>
                                </div>

                                <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="roomName"> Name</label>
                                        <input type="text" readonly class="form-control required" value="<?php echo $roomName; ?>" id="name" name="name" maxlength="256" />
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
    
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Submit" />
                            <input type="reset" class="btn btn-default" value="Reset" />
                        </div>
                    </form>
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