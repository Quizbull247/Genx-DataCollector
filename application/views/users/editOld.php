<?php
$userId = $userInfo->userId;
$name = $userInfo->name;
$email = $userInfo->email;
$mobile = $userInfo->mobile;
$roleId = $userInfo->roleId;
$isAdmin = $userInfo->isAdmin;
$electionId = $userInfo->electionId;
$zoneid = $userInfo->zoneId;
$qc_id = $userInfo->qc_id;
  
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> User Management
        <small>Add / Edit User</small>
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
                    
                    <form role="form" action="<?php echo base_url() ?>editUser" method="post" id="editUser" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Full Name</label>
                                        <input type="text" class="form-control" id="fname" placeholder="Full Name" name="fname" value="<?php echo $name; ?>" maxlength="128">
                                        <input type="hidden" value="<?php echo $userId; ?>" name="userId" id="userId" />    
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email address</label>
                                        <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" value="<?php echo $email; ?>" maxlength="128">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" placeholder="Password" name="password" maxlength="20">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cpassword">Confirm Password</label>
                                        <input type="password" class="form-control" id="cpassword" placeholder="Confirm Password" name="cpassword" maxlength="20">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile">Mobile Number</label>
                                        <input type="text" class="form-control" id="mobile" placeholder="Mobile Number" name="mobile" value="<?php echo $mobile; ?>" maxlength="10">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="role">Role</label>
                                        <select class="form-control" id="role" name="role" readonly disabled>
                                            <option value="0">Select Role</option>
                                            <?php
                                            if(!empty($roles))
                                            {
                                                foreach ($roles as $rl)
                                                {
                                                    $roleText = $rl->role;
                                                    $roleClass = false;
                                                    if ($rl->roleStatus == INACTIVE) {
                                                        $roleText = $rl->role . ' (Inactive)';
                                                        $roleClass = true;
                                                    }
                                                    ?>
                                                    <option value="<?php echo $rl->roleId; ?>" <?php if ($roleClass) { echo "class=text-warning"; } ?>  <?php if($rl->roleId == $roleId) { echo "selected=selected";} ?>><?= $roleText ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div> 
                            </div>
                            <div class="row">
                           <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="isAdmin">User Type</label>
                                        <select class="form-control required" id="isAdmin" name="isAdmin">
                                            <option value="<?= REGULAR_USER ?>">Regular User</option>
                                            
                                        </select>
                                    </div>
                                </div></div>
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
                                                    <option value="<?php echo $rl->id ?>" <?php if($electionId==$rl->id){ echo "selected";} ?>><?php echo $rl->name ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <?php if($roleId==4 || $roleId==3){ ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="roomName">Zone</label>
                                        <select class="form-control" name="zone">
                                            <option value="0">Select Zone</option>
                                            <?php foreach($zones as $each_data){?>
                                            <option value="<?php echo $each_data->id ?>" <?php if($zoneid==$each_data->id){ echo "selected";} ?>><?php echo $each_data->name ?></option>
                                            <?php } ?>
                                        </select>
                                </div>
                                </div>
                                <?php }else{ ?>
                                    <input type="hidden" value="0" name="zone">

                                <?php }?>
                            </div>
                              <div class="row">
                                <?php if($roleId==3){ ?>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="roomName">QC</label> 
                                        <select class="form-control" name="qc_id" onchange="getZC(this.value)">
                                         <option value="0">Select QC</option>
                                          <?php foreach($qcdlist as $each_data){?>
                                            <option value="<?php echo $each_data->userId ?>" <?php if($qc_id==$each_data->userId){ echo "selected";} ?>><?php echo $each_data->name ?></option>
                                            <?php } ?>
                                        </select>
                                </div>
                                </div>
                                
                                <?php }else{ ?>
                                    <input type="hidden" value="<?php echo $qc_id ?>" name="qc_id">

                                <?php }?>
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

<script src="<?php echo base_url(); ?>assets/js/editUser.js" type="text/javascript"></script>



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

