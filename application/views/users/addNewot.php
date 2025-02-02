<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i>OT User Management
        <small>Add / Edit OT User</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter User Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="addUser" action="<?php echo base_url() ?>user/addNewUseradminot" method="post" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Full Name</label>
                                        <input type="text" class="form-control required" value="<?php echo set_value('fname'); ?>" id="fname" name="fname" maxlength="128">
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email address</label>
                                        <input type="text" class="form-control required email" id="email" value="<?php echo set_value('email'); ?>" name="email" maxlength="128">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control required" id="password" name="password" maxlength="20">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cpassword">Confirm Password</label>
                                        <input type="password" class="form-control required equalTo" id="cpassword" name="cpassword" maxlength="20">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile">Mobile Number</label>
                                        <input type="text" class="form-control required digits" id="mobile" value="<?php echo set_value('mobile'); ?>" name="mobile" maxlength="10">
                                    </div>
                                </div>
                                 
                                  <div class="col-md-6" >
                                    <div class="form-group">
                                        <label for="role">Role</label>
                                        OT<input type="hidden" class="form-control required" value="4" id="role" name="role">
                                    <input type="hidden" class="form-control required" value="<?= REGULAR_USER ?>" id="isAdmin" name="isAdmin">

                                    </div>
                                </div>
                               
                                  
                               
                            </div>
                            <div class="row">
                                <div class="col-md-6" >
                                    <div class="form-group">
                                        <label for="role">Election</label>
                                        <select class="form-control required" id="election" name="election" onchange="getZone(this.value)">
                                            <option value="0">Select Election</option>
                                              <?php
                                            if(!empty($election))
                                            {
                                                foreach ($election as $rl)
                                                { ?>
                                                    <option value="<?php echo $rl->id ?>"><?php echo $rl->name ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="roomName">Zone</label>
                                        <select class="form-control" name="zone">
                        <option value="0">Select Zone</option>

                                        </select>
                                </div>
                                </div>
                            </div>
                            
                             <div class="row">
                                 
                                 <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="roomName">QC</label>
                                        <select class="form-control" name="qc_id" onchange="getZC(this.value)">
                                         <option value="0">Select QC</option>
                                        </select>
                                </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="roomName">ZC</label>
                                        <select class="form-control" name="zc_id">
                                            <option value="0">Select ZC</option>
                                        </select>
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
<script src="<?php echo base_url(); ?>assets/js/addUser.js" type="text/javascript"></script>


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
            partySelect.innerHTML = '<option>Select zone</option>'; // Clear existing options

            if (data.parties && data.parties.length > 0) {
                data.parties.forEach(party => {
                    const option = document.createElement('option');
                    option.value = party.id;
                    option.textContent = party.name;
                    partySelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.textContent = 'No Zone available';
                partySelect.appendChild(option);
            }
            
            
            
             const qc_idSelect = document.querySelector('select[name="qc_id"]');
            qc_idSelect.innerHTML = '<option>Select QC</option>'; // Clear existing options

            if (data.candidateListing && data.candidateListing.length > 0) {
                data.candidateListing.forEach(party => {
                    const option = document.createElement('option');
                    option.value = party.userId;
                    option.textContent = party.name;
                    qc_idSelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.textContent = 'No QC available';
                qc_idSelect.appendChild(option);
            }
             
            
        },
        error: function() {
            console.error('Error fetching parties');
        }
    });
}
function getZC(electionId) {
    $.ajax({
        url: '<?php echo base_url(); ?>candidate/getzc', // URL of the delete method in the controller
        type: 'POST',
        data: {
            qc_id: electionId,
            electionId: $("#election").val(),
        },
        success: function(response) {
            // Parse the JSON response
            const data = JSON.parse(response); 
            
             const qc_idSelect = document.querySelector('select[name="zc_id"]');
            qc_idSelect.innerHTML = '<option>Select ZC</option>'; // Clear existing options

            if (data.candidateListing && data.candidateListing.length > 0) {
                data.candidateListing.forEach(party => {
                    const option = document.createElement('option');
                    option.value = party.userId;
                    option.textContent = party.name;
                    qc_idSelect.appendChild(option);
                });
            } else {
                const option = document.createElement('option');
                option.textContent = 'No ZC available';
                qc_idSelect.appendChild(option);
            }
             
            
        },
        error: function() {
            console.error('Error fetching parties');
        }
    });
}

</script>

