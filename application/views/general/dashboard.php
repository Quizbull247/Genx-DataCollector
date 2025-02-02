<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard
        <small>Control panel1</small>
      </h1>
    </section>
    
    <section class="content">
        <div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?php echo $todaydata; ?></h3>
                  <p>Today Data</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
               </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?php echo $totaldata; ?></h3>
                  <p>Total Data</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
               </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?php echo $totalot; ?></h3>
                  <p>Total OT</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
               </div>
            </div><!-- ./col -->
               <?php if($this->session->userdata('role')==1 || $this->session->userdata('role')==2){ ?>
               
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h3><?php echo $totalzc; ?></h3>
                  <p>Total ZC</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
               </div>
            </div><!-- ./col -->
             <?php }  if($this->session->userdata('role')==1){ ?>
             <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h3><?php echo $totalqc; ?></h3>
                  <p>Total QC</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
               </div>
            </div><!-- ./col -->
            
            <?php } ?>
          </div>
              <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 <div class="row">
             <div class="row">
    <div id="charts-container" class="col-12">
        <!-- Dynamic chart canvases will be added here -->
    </div>
</div>

<script>
    const graphsData = <?= json_encode($graphs) ?>;
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    $(document).ready(function () {
        // Loop through each graph data and dynamically create the charts
        $.each(graphsData, function (questionKey, graphData) {
            // Create a container for each chart
            const chartContainer = `
                <div class="col-lg-4 col-xs-6">
                    <div class="small-box">
                        <div class="inner">
                            <canvas id="${questionKey}"></canvas>
                        </div>
                    </div>
                </div>
            `;
            // Append the chart container to the charts-container div
            $('#charts-container').append(chartContainer);

            // Render the pie chart
            const ctx = document.getElementById(questionKey).getContext('2d');
            new Chart(ctx, {
                type: 'pie', // Chart type
                data: {
                    labels: graphData.labels,
                    datasets: [{
                        label: `Responses for ${questionKey}`,
                        data: graphData.data,
                        backgroundColor: [
                            'rgba(255, 0, 0, 0.8)',    // Red  
'rgba(0, 128, 0, 0.8)',    // Green  
'rgba(0, 0, 255, 0.8)',    // Blue  
'rgba(255, 255, 0, 0.8)',  // Yellow  
'rgba(255, 165, 0, 0.8)',  // Orange  
'rgba(128, 0, 128, 0.8)',  // Purple  
'rgba(0, 255, 255, 0.8)',  // Cyan  
'rgba(255, 192, 203, 0.8)', // Pink  
'rgba(165, 42, 42, 0.8)',  // Brown  
'rgba(0, 0, 0, 0.8)',     // Black  
                            'rgba(173, 216, 230, 0.2)',
                            'rgba(240, 230, 140, 0.2)',
                            'rgba(221, 160, 221, 0.2)',
                            'rgba(255, 228, 196, 0.2)',
                            'rgba(250, 128, 114, 0.2)'
                        ],
                        borderColor: [
                             'rgba(0, 0, 0, 0.8)',    // Red  
'rgba(0, 0, 0, 0.8)',    // Green  
'rgba(0, 0, 0, 0.8)',    // Blue  
'rgba(0, 0, 0, 0.8)',  // Yellow  
'rgba(0, 0, 0, 0.8)',  // Orange  
'rgba(0, 0, 0, 0.8)',    // Green  
'rgba(0, 0, 0, 0.8)',    // Blue  
'rgba(0, 0, 0, 0.8)',  // Yellow  
'rgba(0, 0, 0, 0.8)',  // Brown  
'rgba(0, 0, 0, 0.8)',       // Black  
                            'rgba(0, 0, 0, 0.8)',    // Green  
'rgba(0, 0, 0, 0.8)',    // Blue  
'rgba(0, 0, 0, 0.8)',  // Yellow  
'rgba(0, 0, 0, 0.8)',  // Brown  
'rgba(0, 0, 0, 0.8)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top', // Position the legend above the chart
                        },
                        title: {
                            display: true,
                            text: `Pie Chart for ${questionKey}`
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0; // The percentage value
                                    return `${label}: ${value}%`;
                                }
                            }
                        }
                    }
                }
            });
        });
    });
</script>

     </div>
    </section> 
</div>