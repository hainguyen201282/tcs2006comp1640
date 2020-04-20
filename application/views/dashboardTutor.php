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
 
    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row"><!-- TO DO List -->
        <section class="col-lg-5 connectedSortable">
          <div class="box box-primary">
            <div class="box-header">
              <i class="ion ion-clipboard"></i>

              <h3 class="box-title">Number of messages</h3>

              <div class="box-tools pull-right">
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
              <ul class="todo-list" id="todo-list">
                <?php foreach($numberMessageStudentSentToTutor as $key => $studentMessage) {?>
                <li style="<?= ($key > 0) ? 'display: none;' : '';?>">

                  <img src="<?= base_url() . 'uploads/user_avatar/' . ($studentMessage->imgAvatar ? $studentMessage->imgAvatar : 'avatar.png');?>" alt="Student Image">
                  <!-- drag handle -->
                  <div>
                    <span class="handle" style="font-weight: bold;">
                        <i class="fa fa-ellipsis-v"></i>
                        <?= 'Student ID: ' . $studentMessage->studentId;?>
                      </span>
                    <!-- todo text -->
                    <br/>
                    <span class="text">
                      <?= 'Student Name: ' . $studentMessage->fullname;?>
                    </span>
                  </div>
                  
                  <div style="margin-top: -20px;">
                    <!-- Emphasis label -->
                    <small class="label label-info" style="font-size: 13px; float:right; margin-top: -23px;"> Sent <font color="red" size="3"><?= $studentMessage->sent_msg_count;?></font> messages</small> <br/>
                    <small class="label label-info" style="font-size: 13px; float:right; margin-top: -23px;">Received <font color="red" size="3"><?= $studentMessage->received_msg_count;?></font> messages</small>
                  </div>
                  
                                    
                </li>
                <?php } ?>
              </ul>
            </div>
          </div>
          <!-- /.box -->
          </section>
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
    <style type="text/css">
      .small-box .inner p {
        max-width: 140px;
      }
      .pagination>li>a {
        cursor: pointer;
      }

      .todo-list > li img {
        border-radius: 50%;
        max-width: 100%;
        height: 60px;
      }
      .todo-list>li .text {
          display: inline-block;
          margin-left: 5px;
          font-weight: 600;
          float: initial;
      }
    </style>
    
    <script type="text/javascript">
      var number_of_pages = 0;
      $(document).ready(function() {
          var show_per_page = 3;
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