<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content">
        <div class="row"> 
            <div class="col-xs-12">
              <div class="box">
                <div class="box-body table-responsive no-padding" >
                  <table class="table table-hover" id="feedbackTable">
                  <thead>  
                  <tr>
                        <th>S.No1</th>
                        <th>ZC Name</th>
                        <th>OT Name</th>
                        <th>Today Data</th>
                        <th>Total Data</th> 
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($userRecords as $key => $record) { ?>
                    <tr>
                        <td><?php echo $key + 1; ?></td>
                        <td><?php echo $record->zcname; ?></td>
                        <td><a href="<?php echo base_url() ?>question/serveydata/<?php echo $record->qt_id ?>"><?php echo $record->name; ?></a></td>
                        <td><?php echo $record->today_data_count; ?></td>
                        <td><?php echo $record->total_data; ?></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
        </div>
    </section>
</div> 