<?php if($_settings->chk_flashdata('success')): ?>
    <script>
        alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
    </script>
<?php endif;?>
<?php 
if(isset($_GET['id']) && $_GET['id'] > 0){
    $user = $conn->query("SELECT * FROM users where id ='{$_GET['id']}'");
    foreach($user->fetch_array() as $k =>$v){
        $$k = $v;
    }
    $name = ucwords($firstname);
	$meta_qry = $conn->query("SELECT * FROM employee_meta where user_id = '{$_GET['id']}' ");
	while($row = $meta_qry->fetch_assoc()){
        ${$row['meta_field']} = $row['meta_value'];
    }

}
?>


<h1>Welcome to <?php echo $_settings->info('name') ?></h1>
<hr class="bg-light">
<?php if($_settings->userdata('type') != 3): ?>
<div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-red elevation-1"><i class="fas fa-table"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Pending Applications</span>
                <span class="info-box-number text-right">
                  <?php 
                    $pending = $conn->query("SELECT * FROM `leave_applications` where date_format(date_start,'%Y') = '".date('Y')."' and date_format(date_end,'%Y') = '".date('Y')."' and status = 0 ")->num_rows;
                    echo number_format($pending);
                  ?>
                  <?php ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-address-card"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">CEMA Divisions</span>
                <span class="info-box-number text-right">
                  <?php 
                    $department = $conn->query("SELECT id FROM `department_list` ")->num_rows;
                    echo number_format($department);
                  ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-black elevation-1"><i class="fas fa-clipboard "></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Designations</span>
                <span class="info-box-number text-right">
                <?php 
                    $designation = $conn->query("SELECT id FROM `designation_list`")->num_rows;
                    echo number_format($designation);
                  ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-orange elevation-1"><i class="fas fa-list"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Total Type of Leaves</span>
                <span class="info-box-number text-right">
                <?php 
                    $leave_types = $conn->query("SELECT id FROM `leave_types` where status = 1 ")->num_rows;
                    echo number_format($leave_types);
                  ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>

          <?php
          $currentYear = date('Y');
          $currentMonth = date('n');

          // Handle previous and next month navigation
          if (isset($_GET['month']) && isset($_GET['year'])) {
              $currentMonth = $_GET['month'];
              $currentYear = $_GET['year'];
          }

          $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
          $firstDay = date('N', strtotime("$currentYear-$currentMonth-01"));

          $monthNames = [
              1 => 'January',
              2 => 'February',
              3 => 'March',
              4 => 'April',
              5 => 'May',
              6 => 'June',
              7 => 'July',
              8 => 'August',
              9 => 'September',
              10 => 'October',
              11 => 'November',
              12 => 'December'
          ];

          $dayNames = [
              1 => 'Monday',
              2 => 'Tuesday',
              3 => 'Wednesday',
              4 => 'Thursday',
              5 => 'Friday',
              6 => 'Saturday',
              7 => 'Sunday'
          ];

      
         
          // Events data
          $events = [];

          // Fetch data from the leave_applications table and join with the users table
          $dates = $conn->query("SELECT user_id, date_start, date_end FROM leave_applications where status = 1");

          // Check if the query executed successfully
          if ($dates) {
              // Loop through the result set and populate the $events array
              while ($row = mysqli_fetch_assoc($dates)) {
                  $user_id = $row['user_id'];
                  $start = $row['date_start'];
                  $end = $row['date_end'];
                  
                  // Fetch the user's name from the users table
                  $user_query = $conn->query("SELECT * FROM users WHERE id = '$user_id'");
                  if ($user_query && $user = $user_query->fetch_assoc()) {
                      $name = $user['firstname']; // Assuming the name column in the users table is 'name'
                      
                      // Generate a random color
                      $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
                      
                      // Create an event entry with the user's name, start and end dates, and random color
                      $event = [
                          'name' => $name,
                          'start' => $start,
                          'end' => $end,
                          'color' => $color
                      ];
                      
                      $events[] = $event;
                  }
              }
          }
          ?>

          <!DOCTYPE html>
          <html>
          <head>
              
              <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
              <style>
                  .card {
                      margin-top: 20px;
                  }

                  @media (max-width: 576px) {
                      .card-title {
                          font-size: 24px;
                      }
                  }

                  .current-date {
                      background-color: orange;
                      color: white;
                  }

                  .event-bar {
                      height: 5px;
                      margin-top: 3px;
                      border-radius: 2px;
                  }

                  .event-label {
                      font-size: 12px;
                      margin-top: 3px;
                  }
              </style>
          </head>
          <body>
              <div class="container">
                  <div class="card">
                      <div class="card-header">
                          <div class="row">
                              <div class="col-6 col-md-4 text-left">
                                  <?php
                                  $prevMonth = $currentMonth - 1;
                                  $prevYear = $currentYear;
                                  if ($prevMonth == 0) {
                                      $prevMonth = 12;
                                      $prevYear--;
                                  }
                                  ?>
                                  <a href="?month=<?php echo $prevMonth; ?>&year=<?php echo $prevYear; ?>" class="btn btn-primary">&lt; Previous</a>
                              </div>
                              <div class="col-6 col-md-4 text-center">
                                  <h5 class="card-title"><?php echo $monthNames[$currentMonth]; ?> <?php echo $currentYear; ?></h5>
                              </div>
                              <div class="col-12 col-md-4 text-right">
                                  <?php
                                  $nextMonth = $currentMonth + 1;
                                  $nextYear = $currentYear;
                                  if ($nextMonth == 13) {
                                      $nextMonth = 1;
                                      $nextYear++;
                                  }
                                  ?>
                                  <a href="?month=<?php echo $nextMonth; ?>&year=<?php echo $nextYear; ?>" class="btn btn-primary">Next &gt;</a>
                              </div>
                          </div>
                      </div>
                      <div class="card-body">
                          <div class="table-responsive">
                              <table class="table table-bordered">
                                  <thead>
                                      <tr>
                                          <?php foreach ($dayNames as $day) : ?>
                                              <th><?php echo substr($day, 0, 3); ?></th>
                                          <?php endforeach; ?>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      <?php
                                      $dayCounter = 1;
                                      $cellCount = $firstDay;

                                      echo '<tr>';
                                      for ($i = 1; $i < $firstDay; $i++) {
                                          echo '<td></td>';
                                      }

                                      $dayCounter = 1;
                                      $currentDay = 1;

                                      echo '<tr>';
                                      for ($cell = 1; $cell <= 7; $cell++) {
                                          if ($cell < $firstDay) {
                                              echo '<td></td>';
                                          } else {
                                              $cellClass = ($currentDay == date('j') && $currentMonth == date('n') && $currentYear == date('Y')) ? 'current-date' : '';
                                              echo "<td class='$cellClass'>$currentDay";
                                              foreach ($events as $event) {
                                                  $start = strtotime($event['start']);
                                                  $end = strtotime($event['end']);
                                                  if ($start <= strtotime("$currentYear-$currentMonth-$currentDay") && strtotime("$currentYear-$currentMonth-$currentDay") <= $end) {
                                                      $color = $event['color'];
                                                      $name = $event['name'];
                                                      echo "<div class='event-bar' style='background-color: $color;'></div>";
                                                      echo "<div class='event-label'>$name</div>";
                                                  }
                                              }
                                              echo "</td>";
                                              $currentDay++;
                                          }
                                      }
                                      echo '</tr>';

                                      $weeks = ceil(($daysInMonth - ($currentDay - 1)) / 7);

                                      for ($week = 2; $week <= $weeks + 1; $week++) {
                                          echo '<tr>';

                                          for ($day = 1; $day <= 7; $day++) {
                                              if ($currentDay <= $daysInMonth) {
                                                  $cellClass = ($currentDay == date('j') && $currentMonth == date('n') && $currentYear == date('Y')) ? 'current-date' : '';
                                                  echo "<td class='$cellClass'>$currentDay";
                                                  foreach ($events as $event) {
                                                      $start = strtotime($event['start']);
                                                      $end = strtotime($event['end']);
                                                      if ($start <= strtotime("$currentYear-$currentMonth-$currentDay") && strtotime("$currentYear-$currentMonth-$currentDay") <= $end) {
                                                          $color = $event['color'];
                                                          $name = $event['name'];
                                                          echo "<div class='event-bar' style='background-color: $color;'></div>";
                                                          echo "<div class='event-label'>$name</div>";
                                                      }
                                                  }
                                                  echo "</td>";
                                                  $currentDay++;
                                              } else {
                                                  echo '<td></td>';
                                              }
                                          }

                                          echo '</tr>';
                                      }
                                      ?>
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
              </div>
          </body>
          </html>
        </div>
<?php else: ?>
  <div class="row">
    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box">
        <span class="info-box-icon bg-light elevation-1"><i class="fas fa-file-alt"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Pending Applications</span>
          <span class="info-box-number text-right">
            <?php 
              $pending = $conn->query("SELECT * FROM `leave_applications` where date_format(date_start,'%Y') = '".date('Y')."' and date_format(date_end,'%Y') = '".date('Y')."' and status = 0 and user_id = '{$_settings->userdata('id')}' ")->num_rows;
              echo number_format($pending);
            ?>
            <?php ?>
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
    <div class="col-12 col-sm-6 col-md-3">
      <div class="info-box">
        <span class="info-box-icon bg-lightblue elevation-1"><i class="fas fa-th-list"></i></span>

        <div class="info-box-content">
          <span class="info-box-text">Upcoming Leave</span>
          <span class="info-box-number text-right">
            <?php 
              $upcoming = $conn->query("SELECT * FROM `leave_applications` where date(date_start) > '".date('Y-m-d')."' and status = 1 and user_id = '{$_settings->userdata('id')}' ")->num_rows;
              echo number_format($upcoming);
            ?>
            <?php ?>
          </span>
        </div>
        <!-- /.info-box-content -->
      </div>
      <!-- /.info-box -->
    </div>
  </div>
<?php endif; ?>
