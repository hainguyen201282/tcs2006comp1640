<div class="content-wrapper">

    
    <link href="<?php echo base_url(); ?>assets/dist/css/skins/_all-skins.min.css?<?php echo time(); ?>" rel="stylesheet"
          type="text/css"/>
    <link href="<?php echo base_url(); ?>assets/bower_components/jvectormap/jquery-jvectormap.css?<?php echo time(); ?>" rel="stylesheet"
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

      
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <div class="col-md-8">
          <!-- /.box -->
          <div class="row">
            <div class="col-md-6">
              <!-- DIRECT CHAT -->
              <div class="box box-warning direct-chat direct-chat-warning">
                <div class="box-header with-border">
                  <h3 class="box-title">Messages</h3>

                  <div class="box-tools pull-right">
                    <span data-toggle="tooltip" title="3 New Messages" class="badge bg-yellow"><?= count($studentTutorMessages);?> messages</span>
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <!-- Conversations are loaded here -->
                  
                  <div class="direct-chat-messages">
                    <!-- Message. Default to the left -->
                    <?php foreach($studentTutorMessages as $key => $studentTutorMessage) {?>
                    <div class="direct-chat-msg <?= ($studentTutorMessage->studentSender == 1) ? 'right' : '';?>">
                      <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name pull-left"><?= ($studentTutorMessage->studentSender == 1) ? $studentTutorMessage->student_name : $studentTutorMessage->tutor_name;?></span>
                        <span class="direct-chat-timestamp pull-right"><?= $studentTutorMessage->createdDate;?></span>
                      </div>
                      <!-- /.direct-chat-info -->
                      <?php
                        $avatar = $studentTutorMessage->studentSender == 1 ? ($studentTutorMessage->studentAvatar ? $studentTutorMessage->studentAvatar : 'avatar.png') : 
                          ($studentTutorMessage->tutorAvatar ? $studentTutorMessage->tutorAvatar : 'avatar.png');
                      ?>
                      <img class="direct-chat-img" src="<?= base_url() . 'uploads/user_avatar/' . $avatar;?>" alt="message user image">
                      <!-- /.direct-chat-img -->
                      <div class="direct-chat-text">
                        <?= $studentTutorMessage->content;?>
                      </div>
                      <!-- /.direct-chat-text -->
                    </div>
                    <!-- /.direct-chat-msg -->
                  <?php } ?>
    

                  </div>
                </div>
                <!-- /.box-body -->
              </div>
              <!--/.direct-chat -->
            </div>
          </div>
          <!-- /.row -->
        </div>

      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
    <style type="text/css">
      .small-box .inner p {
        max-width: 140px;
      }
    </style>
</div>