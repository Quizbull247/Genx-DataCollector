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
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="addBooking" action="<?php echo base_url() ?>candidate/addemoji" method="post" role="form" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="roomName">Election</label>
                                        <select class="form-control" name="election" onchange="getZone(this.value)">
                                            <option>Select Election</option>
                                            <?php foreach($election as $elec) { ?>
                                            <option value="<?php echo $elec->id; ?>"><?php echo $elec->name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="roomName">Zone</label>
                                        <select class="form-control" name="zone">
                                            <!-- Options will be populated here -->
                                        </select>
                                </div>
                                </div>
                                <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="roomName">Party</label>
                                        <select class="form-control" name="party">
                                            <option>Select Party</option>
                                            <?php foreach($party as $elec) { ?>
                                            <option value="<?php echo $elec->id; ?>"><?php echo $elec->name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="roomName">Name</label>
                                        <input type="text" class="form-control required" value="<?php echo set_value('name'); ?>" id="name" name="name" maxlength="256" />
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
            const partySelect = document.querySelector('select[name="zone"]');
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

