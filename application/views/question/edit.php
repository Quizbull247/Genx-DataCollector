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
        <small>Add / Edit Booking</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter Question Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <form role="form" action="<?php echo base_url() ?>Question/editemoji" method="post" id="editBooking" role="form" enctype="multipart/form-data">
                        <div class="box-body">
                        <div class="row">
                             <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="roomName">Election</label>
                                        <select class="form-control" name="election">
                                            <?php foreach($election as $ele =>$elec){ ?>
                                            <option <?php if($elec->id == $bookingInfo->election){ echo"selected";} ?> value="<?php echo $elec->id; ?>"><?php echo $elec->name ?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                    
                                </div>
                                <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="roomName"> Question</label>
                                        <input type="text" class="form-control required" value="<?php echo $roomName; ?>" id="name" name="name" maxlength="256" />
                                        <input type="hidden" value="<?php echo $bookingId; ?>" name="bookingId" id="bookingId" />
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