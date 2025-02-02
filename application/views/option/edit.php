<?php
$bookingId = $bookingInfo->id;


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
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> Option Management
        <small>Add / Edit Option</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter Option Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <form role="form" action="<?php echo base_url() ?>option/editemoji" method="post" id="editBooking" role="form" enctype="multipart/form-data">
                        <div class="box-body">
                        <div class="row">
                             <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="roomName">Election</label>
                                        <select class="form-control" name="election"  onchange="getZone(this.value)">
                                            <?php foreach($election as $ele =>$elec){ ?>
                                            <option <?php if($elec->id == $bookingInfo->election){ echo"selected";} ?> value="<?php echo $elec->id; ?>"><?php echo $elec->name ?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                                                    <input type="hidden" name="bookingId" value="<?php echo $bookingInfo->id; ?>">

                                </div>
                                <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="roomName">Question</label>
                                        <select class="form-control" name="question">
                                            <option>Select Question</option>
                                            <?php foreach($question as $elec) { ?>
                                            <option  <?php if($elec->id == $bookingInfo->question){ echo"selected";} ?> value="<?php echo $elec->id; ?>"><?php echo $elec->name; ?></option>
                                            <?php } ?>
                                            <!-- Options will be populated here -->
                                        </select>
                                </div>
                                </div>
                                

                        
                                <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="roomName">Option</label>
                                        <input type="text" class="form-control required" value="<?php echo $bookingInfo->option; ?>" id="name" name="option" maxlength="256" />
                                    </div>
                                    
                                </div>
                                 <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="roomName">Option Order</label>
                                        <input type="text" class="form-control required" value="<?php echo $bookingInfo->option_order; ?>" id="name" name="option_order" maxlength="256" />
                                    </div>
                                    
                                </div
                                
                                
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
<script>
function getZone(electionId) {
    $.ajax({
        url: '<?php echo base_url(); ?>candidate/zone', // URL of the delete method in the controller
        type: 'POST',
        data: {
            electionId: electionId,
        },
        success: function(response) {
            // Parse the JSON response
            const data = JSON.parse(response);
            const partySelect = document.querySelector('select[name="question"]');
            partySelect.innerHTML = ''; // Clear existing options

            if (data.parties && data.parties.length > 0) {
                data.parties.forEach(party => {
                    const option = document.createElement('option');
                    option.value = party.id;
                    option.textContent = party.name;
                    partySelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.textContent = 'No parties available';
                partySelect.appendChild(option);
            }
        },
        error: function() {
            console.error('Error fetching parties');
        }
    });
}

</script>

