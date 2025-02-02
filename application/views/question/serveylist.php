<style>
/*    button.dt-button.buttons-excel.buttons-html5 {*/
/*    left: 100px !important;*/
/*}*/
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) --> 
    <section class="content-header"> 
      <h1>
        <i class="fa fa-user-circle-o" aria-hidden="true"></i> Feedback Management
      </h1>
    </section>
    <section class="content"> 
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Feedback List</h3>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover" id="feedbackTa">
                    <thead>
                      <tr>
                          <th>ID</th>
                          <th>Name</th>
                          <th>Mobile</th>
                          <th>QC ID</th>
                          <th>ZC ID</th>
                          <th>QT ID</th>
                          <?php for ($i = 1; $i <= 41; $i++) { ?>
                              <th>Answer <?php echo $i; ?></th>
                          <?php } ?>
                          <th>Created On</th>
                          <th>Action</th>
                       </tr>
                    </thead>
                  </table>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                </div>
              </div><!-- /.box -->
            </div>
        </div>
    </section> 
</div> 

<!-- DataTables and related plugins -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.2.2/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script>
$(document).ready(function() {
    var qt_id = "<?php echo $this->uri->segment(3)?>";
    $('#feedbackTa').DataTable({
        "processing": true, // Show processing indicator 
        "serverSide": true, // Enable server-side processing
        "ajax": {
            "url": "<?php echo base_url('question/serveydatanew') ?>", // Update with your controller function URL
            "type": "GET",
            "data": function(d) {
                d.qt_id = qt_id; // Add qt_id to the request
            }
        },
        "columns": [
            { "data": "id" },
            { "data": "name" },
            { "data": "mobile" },
            { "data": "qc_id" },
            { "data": "zc_id" },
            { "data": "qt_id" },
            // Generate columns for all 40 answers dynamically
            <?php for ($i = 1; $i <= 41; $i++) { ?>
                { "data": "ans_string_<?php echo $i; ?>" },
            <?php } ?>
            { "data": "created_at" },
            { "data": "action", "orderable": false, "searchable": false }
        ],
                // dom: 'Bfrtip',

        "dom": '<"top"lfB>rt<"bottom"ip>', // Control layout: l=lengthMenu, f=filter, B=buttons, t=table, i=info, p=pagination
        "buttons": [
            {
                extend: 'excelHtml5',
                text: 'Export Excel',
                title: 'Feedback List'
            }
        ],
        "paging": true, // Enable pagination
        "searching": true, // Enable searching
        "ordering": false, // Disable ordering
        "info": true, // Display info about table
        "lengthMenu": [ // Add lengthMenu to allow user to select number of entries to display
            [10], // Page length options
            [10,  "All"] // Text for the options
        ]
    });
});
</script>

