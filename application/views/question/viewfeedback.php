<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <?php error_reporting(0); ?>
    <section class="content" style="background: white;">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title">Survey Data</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Name:</strong></label>
                                <p><?php echo htmlspecialchars($records->name, ENT_QUOTES, 'UTF-8'); ?></p>
                            </div>

                            <div class="form-group">
                                <label><strong>Mobile:</strong></label>
                                <p><?php echo htmlspecialchars($records->mobile, ENT_QUOTES, 'UTF-8'); ?></p>
                            </div>

                            <div class="form-group">
                                <label><strong>QC Name:</strong></label>
                                <p>
                                    <?php 
                                    $qc_id = $records->qc_id;
                                    $q = $this->db->query("SELECT name FROM `tbl_users` WHERE `userId`='$qc_id'");
                                    $row = $q->row();
                                    echo !empty($row) ? htmlspecialchars($row->name, ENT_QUOTES, 'UTF-8') : 'N/A';
                                    ?>
                                </p>
                            </div>

                            <div class="form-group">
                                <label><strong>ZC Name:</strong></label>
                                <p>
                                    <?php 
                                    $zc_id = $records->zc_id;
                                    $q = $this->db->query("SELECT name FROM `tbl_users` WHERE `userId`='$zc_id'");
                                    $row = $q->row();
                                    echo !empty($row) ? htmlspecialchars($row->name, ENT_QUOTES, 'UTF-8') : 'N/A';
                                    ?>
                                </p>
                            </div>

                            <div class="form-group">
                                <label><strong>OT Name:</strong></label>
                                <p>
                                    <?php 
                                    $ot_id = $records->qt_id;
                                    $q = $this->db->query("SELECT name, zoneId FROM `tbl_users` WHERE `userId`='$ot_id'");
                                    $row = $q->row();
                                    echo !empty($row) ? htmlspecialchars($row->name, ENT_QUOTES, 'UTF-8') : 'N/A';
                                    $zonename = '';
                                    if (!empty($row->zoneId)) {
                                        $zone_id = $row->zoneId;
                                        $q = $this->db->query("SELECT name FROM `zone` WHERE `id`='$zone_id'");
                                        $row1 = $q->row();
                                        $zonename = !empty($row1) ? htmlspecialchars($row1->name, ENT_QUOTES, 'UTF-8') : '';
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php if ($records->customer_file) { ?>
                                    <img style="width: 100px;height: 100px;" src="<?php echo base_url() ?>uploads/<?php echo htmlspecialchars($records->customer_file, ENT_QUOTES, 'UTF-8'); ?>" style="width: 100%; height: auto;" class="img-thumbnail" />
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <label><strong>Location:</strong></label>
                                <p><?php echo htmlspecialchars($records->lat, ENT_QUOTES, 'UTF-8'); ?>, <?php echo htmlspecialchars($records->lng, ENT_QUOTES, 'UTF-8'); ?></p>
                            </div>

                            <div class="form-group">
                                <label><strong>Zone:</strong></label>
                                <p><?php echo $zonename; ?></p>
                            </div>

                            <div class="form-group">
                                <label><strong>Date:</strong></label>
                                <p><?php echo htmlspecialchars($records->created_at, ENT_QUOTES, 'UTF-8'); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Survey Questions -->
                    <div class="row mt-4">
                        <?php $i = 1; foreach ($questions as $each_question) { ?>
                            <div class="col-md-8">
                                <strong>Q<?php echo $i; ?>. <?php echo htmlspecialchars($each_question->name, ENT_QUOTES, 'UTF-8'); ?></strong>
                            </div>
                            <div class="col-md-4">
                                <p><?php 
                                    $answerProperty = 'ans_string_' . $i;
                                    echo isset($records->$answerProperty) ? htmlspecialchars($records->$answerProperty, ENT_QUOTES, 'UTF-8') : 'No answer provided';
                                ?></p>
                            </div>
                        <?php $i++; } ?>

                        <?php foreach ($candidates as $candidate) { ?>
                            <div class="col-md-8">
                                <strong>Q<?php echo $i; ?>. विधायक संभावित उम्मीदवार <?php echo htmlspecialchars($candidate->name, ENT_QUOTES, 'UTF-8'); ?></strong>
                            </div>
                            <div class="col-md-4">
                                <p><?php 
                                    $answerProperty = 'ans_string_' . $i;
                                    echo isset($records->$answerProperty) ? htmlspecialchars($records->$answerProperty, ENT_QUOTES, 'UTF-8') : 'No answer provided';
                                ?></p>
                            </div>
                        <?php $i++; } ?>

                        <div class="col-md-8">
                            <strong>Q<?php echo $i; ?>. इन सभी में से आप किसको अगले विधायक के रुप में देखना चाहते?</strong>
                        </div>
                        <div class="col-md-4">
                            <p><?php 
                                $answerProperty = 'ans_string_' . $i;
                                echo isset($records->$answerProperty) ? htmlspecialchars($records->$answerProperty, ENT_QUOTES, 'UTF-8') : 'No answer provided';
                            ?>
                              <?php $i++;  ?></p>
                        </div>
                    </div>

                    <!-- Last Questions -->
                    <div class="row mt-4">
                        <?php foreach ($questionslast as $each_question) { ?>
                            <div class="col-md-8">
                                <strong>Q<?php echo $i; ?>. <?php echo htmlspecialchars($each_question->name, ENT_QUOTES, 'UTF-8'); ?></strong>
                            </div>
                            <div class="col-md-4">
                                <p><?php 
                                    $answerProperty = 'ans_string_' . $i;
                                    echo isset($records->$answerProperty) ? htmlspecialchars($records->$answerProperty, ENT_QUOTES, 'UTF-8') : 'No answer provided';
                                ?></p>
                            </div>
                        <?php $i++; } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- AdminLTE CSS -->
<link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/dist/css/AdminLTE.min.css">
