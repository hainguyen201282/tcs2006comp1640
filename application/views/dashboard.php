<div class="content-wrapper">

    <link href="<?php echo base_url(); ?>assets/bower_components/bootstrap-daterangepicker/daterangepicker.css?<?php echo time(); ?>" rel="stylesheet"
          type="text/css"/>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard
        <small>Control panel</small>
      </h1>
    </section>
    
    <?php if ($role == AUTHORISED_STAFF) { ?>
    <section class="content">
        <div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?= $numberOfMessageIn7Days; ?></h3>
                  <p>Number of messages in last 7 days</p>
                </div>
                <div class="icon">
                  <i class="ion ion-chatbubbles"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3>53<sup style="font-size: 20px">%</sup></h3>
                  <p>Average number of messages for each personal tutor</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?= $numberOfStudentWithoutTutor; ?></h3>
                  <p>Student without personal tutor</p>
                </div>
                <div class="icon">
                  <i class="ion ion-android-people"></i>
                </div>
                <!-- <a href="<?php //echo base_url(); ?>userListing" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h3>65</h3>
                  <p>
                    Students with no interaction for 7 days and 28 days
                    <button type="button" class="btn btn-primary btn-sm daterange pull-right" data-toggle="tooltip" title="" data-original-title="Date range">
                  <i class="fa fa-calendar"></i></button>
                  </p>
                </div>
                <div class="icon">
                  <i class="ion ion-sad"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> -->
              </div>
            </div><!-- ./col -->
          </div>
      </section>
    <?php } ?>
    <style type="text/css">
      .small-box .inner p {
        max-width: 140px;
      }
    </style>
    <script src="<?php echo base_url(); ?>assets/bower_components/moment/min/moment.min.js" type="text/javascript"></script>
    <!-- <script src="<?php //echo base_url(); ?>assets/bower_components/jquery-ui/jquery-ui.min.js" type="text/javascript"></script> -->
    <!-- <script src="<?php //echo base_url(); ?>assets/dist/js/adminlte.min.js" type="text/javascript"></script> -->
    <!-- <script src="<?php //echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script> -->
    <script src="<?php echo base_url(); ?>assets/bower_components/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
    <script type="text/javascript">
      $('.daterange').daterangepicker({
        ranges   : {
          'Last 7 Days' : [moment().subtract(7, 'days'), moment()],
          'Last 28 Days': [moment().subtract(28, 'days'), moment()]
        },
        startDate: moment().subtract(7, 'days'),
        endDate  : moment(),
        showCustomRangeLabel: false,
        autoApply: true
      }, function (start, end) {
        if (start.format('D') == moment().subtract(7, 'days').format('D')) {
          window.alert('You chose: 7 days');
        }
        if (start.format('D') == moment().subtract(28, 'days').format('D')) {
          window.alert('You chose: 28 days');
        }
        // window.alert('You chose: ' + start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
      });
    </script>
</div>