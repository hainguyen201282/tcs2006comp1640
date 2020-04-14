<div class="content-wrapper">

    <link href="<?php echo base_url(); ?>assets/bower_components/bootstrap-daterangepicker/daterangepicker.css?<?php echo time(); ?>" rel="stylesheet"
          type="text/css"/>
    <link href="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css?<?php echo time(); ?>" rel="stylesheet"
          type="text/css"/>
    <link href="<?php echo base_url(); ?>assets/bower_components/morris.js/morris.css?<?php echo time(); ?>" rel="stylesheet"
          type="text/css"/>
    <link href="<?php echo base_url(); ?>assets/bower_components/jvectormap/jquery-jvectormap.css?<?php echo time(); ?>" rel="stylesheet"
          type="text/css"/>
    <link href="<?php echo base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css?<?php echo time(); ?>" rel="stylesheet"
          type="text/css"/>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard
        <small>Control panel</small>
      </h1>
    </section>
    
    <?php if ($role == AUTHORISED_STAFF) { ?>
    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>150</h3>

              <p>New Orders</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>53<sup style="font-size: 20px">%</sup></h3>

              <p>Bounce Rate</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>44</h3>

              <p>User Registrations</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>65</h3>

              <p>Unique Visitors</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-7 connectedSortable">
          <!-- /.nav-tabs-custom -->

          <!-- TO DO List -->
          <div class="box box-primary">
            <div class="box-header">
              <i class="ion ion-clipboard"></i>

              <h3 class="box-title">Average Message Number of Tutor</h3>

              <div class="box-tools pull-right">
                <!-- <ul class="pagination pagination-sm inline">
                  <li><a href="#">&laquo;</a></li>
                  <li><a href="#">1</a></li>
                  <li><a href="#">2</a></li>
                  <li><a href="#">3</a></li>
                  <li><a href="#">&raquo;</a></li>
                </ul> -->
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
              <ul class="todo-list" id="todo-list">
                <?php foreach($averageMessagesSentByTutor as $key => $tutorMessage) {?>
                <li style="height: 60px; <?= ($key > 0) ? 'display: none;' : '';?>">
                  <!-- drag handle -->
                  <span class="handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <?= 'Tutor ID: ' . $tutorMessage->tutorId;?>
                      </span>
                  <!-- todo text -->
                  <span class="text"><?= 'Tutor Name: ' . $tutorMessage->fullname;?></span>
                  <!-- Emphasis label -->
                  <small class="label label-info" style="font-size: 13px; float:right;"> sent <?= round($tutorMessage->avg_message_count_sent_by_tutor, 2);?> messages</small> <br/>
                  <small class="label label-info" style="font-size: 13px; float:right;">received <?= round($tutorMessage->avg_message_count_sent_to_tutor, 2);?> messages</small>
                </li>
                <?php } ?>
              </ul>
            </div>
          </div>
          <!-- /.box -->

        </section>
        <!-- /.Left col -->
      </div>
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
    <?php } ?>
    <style type="text/css">
      .small-box .inner p {
        max-width: 140px;
      }
      .pagination>li>a {
        cursor: pointer;
      }
    </style>
    
    <script src="<?php echo base_url(); ?>assets/bower_components/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/bower_components/raphael/raphael.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/bower_components/morris.js/morris.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/bower_components/jquery-knob/dist/jquery.knob.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/bower_components/moment/min/moment.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/bower_components/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/bower_components/fastclick/lib/fastclick.js" type="text/javascript"></script>
    <!-- <script src="<?php //echo base_url(); ?>assets/js/dashboardChiefStaff.js?<?php //echo time(); ?>" type="text/javascript"></script> -->

    <script type="text/javascript">
      var number_of_pages = 0;
      $(document).ready(function() {
          var show_per_page = 1;
          var number_of_items = $('#todo-list').children('li').length;
          number_of_pages = Math.ceil(number_of_items / show_per_page);

          $($('#todo-list').parent().parent().find('.box-tools')[0]).append('<ul class="controls pagination pagination-sm inline"></ul><input id="current_page" type="hidden"><input id="show_per_page" type="hidden">');
          $('#current_page').val(0);
          $('#show_per_page').val(show_per_page);

          var navigation_html = '<li class="prev" onclick="previous()"><a>Prev</a></li>';
          var current_link = 0;
          // while (number_of_pages > current_link) {
          while (number_of_pages > current_link && current_link < 3) {
              navigation_html += '<li class="page" longdesc="' + current_link + '" onclick="go_to_page(this)"><a >' + (current_link + 1) + '</a></li>';
              current_link++;
          }
          navigation_html += '<li class="next" onclick="next()"><a>Next</a></li>';

          $('.controls').html(navigation_html);
          $('.controls li.page:first').addClass('active');

          $('#todo-list').children().css('display', 'none');
          $('#todo-list').children().slice(0, show_per_page).css('display', 'block');

      });

      function go_to_page(currentItem) {

          page_num = parseInt($(currentItem).attr('longdesc'),0);
          var show_per_page = parseInt($('#show_per_page').val(), 0);

          start_from = page_num * show_per_page;

          end_on = start_from + show_per_page;

          $('#todo-list').children().css('display', 'none').slice(start_from, end_on).css('display', 'block');

          

          if (page_num > 0 && $('.page[longdesc=' + page_num + ']').prev('.page').length != true) {
            $('.page[longdesc=' + page_num + ']').html('<a >' + (page_num) + '</a>').attr('longdesc', page_num - 1).next('.page').html('<a >' + (page_num + 1) + '</a>').attr('longdesc', page_num).next('.page').html('<a >' + (page_num + 2) + '</a>').attr('longdesc', page_num + 1);
          }
          if (page_num < number_of_pages - 1 && $('.page[longdesc=' + page_num + ']').next('.page').length != true) {

            $('.page[longdesc=' + page_num + ']').html('<a >' + (page_num + 2) + '</a>').attr('longdesc', page_num + 1).prev('.page').html('<a >' + (page_num + 1) + '</a>').attr('longdesc', page_num).prev('.page').html('<a >' + (page_num) + '</a>').attr('longdesc', page_num - 1);
            
          }

          $('.page[longdesc=' + page_num + ']').addClass('active').siblings('.active').removeClass('active');

          $('#current_page').val(page_num);
      }



      function previous() {

          new_page = parseInt($('#current_page').val(), 0) - 1;
          //if there is an item before the current active link run the function
          if ($('.active').prev('.page').length == true) {
              go_to_page($('.page[longdesc=' + new_page + ']'));
          }

      }

      function next() {
          new_page = parseInt($('#current_page').val(), 0) + 1;
          //if there is an item after the current active link run the function
          if ($('.active').next('.page').length == true) {
              go_to_page($('.page[longdesc=' + new_page + ']'));
          }

      }
    </script>
</div>