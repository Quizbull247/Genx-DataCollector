<?php
$bookingId = $bookingInfo->id;


 $this->db->select('*');
        $this->db->from('election');
        $this->db->where('id', $bookingInfo->election);
        $queryq = $this->db->get();
        $queryqr=$queryq->row();
        
        $this->db->select('*');
                            $this->db->from('question');
                            $this->db->where('id', $bookingInfo->question);
                            $queryq11 = $this->db->get();
                            $queryqr1=$queryq11->row();
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> Option Management
        <small>View Option</small>
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
                                        
                                         <input type="text" readonly class="form-control required" value="<?php echo $bookingInfo->id; ?>" id="name" name="option" maxlength="256" />

                                    </div>

                                </div>
                             <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="roomName">Election</label>
                                        
                                         <input type="text" readonly class="form-control required" value="<?php if(@$queryqr->name!='') { echo @$queryqr->name; } ?>" id="name" name="option" maxlength="256" />

                                    </div>

                                </div>
                                <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="roomName">Question</label>
                                    <input type="text" readonly class="form-control required" value="<?php if(@$queryqr1->name!='') { echo @$queryqr1->name; } ?>" id="name" name="option" maxlength="256" />

                                </div>
                                </div>
                                

                        
                                <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="roomName">Option</label>
                                        <input type="text" readonly class="form-control required" value="<?php echo $bookingInfo->option; ?>" id="name" name="option" maxlength="256" />
                                    </div>
                                    
                                </div>
                                 <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="roomName">Option Order</label>
                                        <input type="text" readonly class="form-control required" value="<?php echo $bookingInfo->option_order; ?>" id="name" name="option_order" maxlength="256" />
                                    </div>
                                    
                                </div>
                                <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="roomName">Status</label></br>
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

