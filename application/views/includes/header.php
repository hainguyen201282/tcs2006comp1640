<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= isset($pageTitle) ? $pageTitle : 'Academic Portal'; ?></title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.4 -->
    <link href="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css?<?php echo time(); ?>"
          rel="stylesheet"
          type="text/css"/>
    <!-- FontAwesome 4.3.0 -->
    <link href="<?php echo base_url(); ?>assets/bower_components/font-awesome/css/font-awesome.min.css?<?php echo time(); ?>"
          rel="stylesheet"
          type="text/css"/>
    <!-- Ionicons 2.0.0 -->
    <link href="<?php echo base_url(); ?>assets/bower_components/Ionicons/css/ionicons.min.css?<?php echo time(); ?>"
          rel="stylesheet"
          type="text/css"/>
    <!-- Theme style -->
    <link href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css?<?php echo time(); ?>" rel="stylesheet"
          type="text/css"/>
    <!-- AdminLTE Skins. Choose a skin from the css/skins 
         folder instead of downloading all of them to reduce the load. -->
    <link href="<?php echo base_url(); ?>assets/dist/css/skins/_all-skins.min.css?<?php echo time(); ?>"
          rel="stylesheet" type="text/css"/>
    <style type="text/css">
        .error {
            color: red;
            font-weight: normal;
        }
    </style>
    <script src="<?php echo base_url(); ?>assets/bower_components/jquery/dist/jquery.min.js"
            type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/ckeditor/ckeditor.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/js/ckfinder/ckfinder.js" type="text/javascript"></script>
    <script type="text/javascript">
        let baseURL = "<?php echo base_url(); ?>";
    </script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <!-- Tab Pane -->
    <script type="text/javascript"
            src="<?php echo base_url(); ?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/dist/js/adminlte.min.js" type="text/javascript"></script>

    <!--JQuery -->
    <!-- <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script> -->
    <!-- <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script> -->

    <!-- Datatables -->
    <link type="text/css" rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <script type="text/javascript" rel='stylesheet'
            src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" rel='stylesheet'
            src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>


</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="<?php echo base_url(); ?>" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>ACA</b>P</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Academic</b>Portal</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                            <i class="fa fa-bell-o"></i>
                            <?php if (isset($notifficationLogs) && $notifficationLogs &&count($notifficationLogs) > 0) {?>
                            <span class="label label-warning">
                                <?= (isset($notifficationLogs) && $notifficationLogs && count($notifficationLogs) > 0) ? count($notifficationLogs) : "" ?>
                            </span>
                            <?php } ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <!-- inner menu: contains the actual data -->
                                <ul class="menu">
                                    <?php
                                    if (isset($notifficationLogs) && $notifficationLogs && count($notifficationLogs) > 0) {
                                        foreach ($notifficationLogs as $notificationLog) { ?>
                                            <li>
                                                <a href="#">
                                                    <i class="fa fa-users text-aqua"></i>
                                                    <?= $notificationLog->notification_text; ?>
                                                </a>
                                            </li>
                                        <?php }
                                    } ?>
                                </ul>
                            </li>
                            <li class="footer"><a href="#">View all</a></li>
                        </ul>
                    </li>
                    <li class="dropdown tasks-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                            <i class="fa fa-history"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header"> Last Login :
                                <i class="fa fa-clock-o"></i>
                                <?= empty($last_login) ? "First Time Login" : $last_login; ?>
                            </li>
                        </ul>
                    </li>
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?php echo base_url() . AVATAR_PATH . $imgAvatar; ?>"
                                 class="user-image" alt="User Image"/>
                            <span class="hidden-xs"><?= $name; ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="<?php echo base_url() . AVATAR_PATH . $imgAvatar; ?>"
                                     class="img-circle" alt="User Image"/>
                                <p>
                                    <?= $name; ?>
                                    <small><?= isset($role_text) ? $role_text : 'Unknowns'; ?></small>
                                </p>

                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="<?php echo base_url(); ?>profile" class="btn btn-warning btn-flat"
                                       style="color: #666;">
                                        <i class="fa fa-user-circle"></i>
                                        Profile
                                    </a>
                                </div>
                                <div class="pull-right">
                                    <a href="<?php echo base_url(); ?>logout" class="btn btn-default btn-flat">
                                        <i class="fa fa-sign-out"></i>
                                        Sign out
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu" data-widget="tree">

                <li class="header">MAIN NAVIGATION</li>

                <?php if ($role == AUTHORISED_STAFF) {
                    ?>
                    <li>
                        <a href="<?php echo base_url(); ?>dashboard">
                            <i class="fa fa-dashboard"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>userListing">
                            <i class="fa fa-users"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>studentListing">
                            <i class="fa fa-id-card"></i>
                            <span>Students</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>conferenceListing">
                            <i class="fa fa-comments"></i>
                            <span>Conference</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>blogListing">
                            <i class="fa fa-files-o"></i>
                            <span>Blog</span>
                        </a>
                    </li>
                    <?php
                }

                if ($role == STAFF) {
                    ?>
                    <li>
                        <a href="<?php echo base_url(); ?>dashboard">
                            <i class="fa fa-dashboard"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>userListing">
                            <i class="fa fa-users"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>studentListing">
                            <i class="fa fa-id-card"></i>
                            <span>Students</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>conferenceListing">
                            <i class="fa fa-comments"></i>
                            <span>Conference</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>blogListing">
                            <i class="fa fa-files-o"></i>
                            <span>Blog</span>
                        </a>
                    </li>
                    <?php
                }

                if ($role == TUTOR) {
                    ?>
                    <li>
                        <a href="<?php echo base_url(); ?>dashboard">
                            <i class="fa fa-dashboard"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>studentListing">
                            <i class="fa fa-id-card"></i>
                            <span>Students</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>conferenceListing">
                            <i class="fa fa-handshake-o"></i>
                            <span>Conference</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>blogListing">
                            <i class="fa fa-files-o"></i>
                            <span>Blog</span>
                        </a>
                    </li>
                    <?php
                }

                if ($role == STUDENT) {
                    ?>
                    <li>
                        <a href="<?php echo base_url(); ?>dashboard">
                            <i class="fa fa-dashboard"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>conferenceListing">
                            <i class="fa fa-handshake-o"></i>
                            <span>Conference</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>blogListing">
                            <i class="fa fa-files-o"></i>
                            <span>Blog</span>
                        </a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </section>
        <!-- /.sidebar -->

        <!-- Socket.IO -->
        <script src="<?= base_url() ?>/assets/socket.io/dist/socket.io.js"></script>
        <!-- page script -->
        <script>
            // $(function () {
            let ipAddress = "<?= $_SERVER['HTTP_HOST']; ?>";

            // if (ipAddress == "::1") {
            //     ipAddress = "localhost"
            // }

            const port = "3000";
            const socketIoAddress = `http://` + `35.238.162.110` + `:` + port;
            const socket = io(socketIoAddress);

            socket.on('send_notification_callback', (response) => {
                const data = response.data;
                const eventName = data['eventName'];
                if (eventName === "assign_student_to_tutor") {
                    studentIds = data['student_ids'];
                    tutorId = data['tutor_id'];
                    tutorName = data['tutor_name'];
                    studentArr = studentIds.split(",");

                    if ('<?= $role; ?>' == '<?= STUDENT; ?>' && studentArr.indexOf('<?= $vendorId; ?>') != -1) {
                        $('ul.navbar-nav li.notifications-menu ul.dropdown-menu li ul.menu')
                            .prepend('<li><a href="#"><i class="fa fa-users text-aqua"></i>You are just assigned to tutor ' + tutorName + '</a> </li>');

                        let notiCountElement = $('ul.navbar-nav li.notifications-menu a.dropdown-toggle span.label-warning');
                        if (notiCountElement.text() == '') {
                            notiCountElement.text('1');
                        } else {
                            notiCountElement.text(parseInt(notiCountElement.text()) + 1);
                        }
                    }

                    if ('<?= $role; ?>' == '<?= TUTOR; ?>' && tutorId == '<?= $vendorId; ?>') {
                        $('ul.navbar-nav li.notifications-menu ul.dropdown-menu li ul.menu')
                            .prepend('<li><a href="#"><i class="fa fa-users text-aqua"></i>Students (' + studentIds + ') are assigned to you</a> </li>');

                        let notiCountElement = $('ul.navbar-nav li.notifications-menu a.dropdown-toggle span.label-warning');
                        if (notiCountElement.text() == '') {
                            notiCountElement.text('1');
                        } else {
                            notiCountElement.text(parseInt(notiCountElement.text()) + 1);
                        }
                    }
                }

                if (eventName === "send_message") {
                    studentId = data['student_ids'];
                    tutorId = data['tutor_id'];
                    tutorName = data['tutor_name'];
                    studentName = data['student_name'];
                    sentByStudent = data['sent_by_student'];

                    if ('<?= $role; ?>' == '<?= STUDENT; ?>' && studentId =='<?= $vendorId; ?>') {

                        notifyText = (sentByStudent) ? "You've just sent message to tutor " + tutorName : "You've just received message from tutor " + tutorName;
                        $('ul.navbar-nav li.notifications-menu ul.dropdown-menu li ul.menu')
                            .prepend('<li><a href="#"><i class="fa fa-users text-aqua"></i>'+ notifyText + '</a> </li>');

                        let notiCountElement = $('ul.navbar-nav li.notifications-menu a.dropdown-toggle span.label-warning');
                        if (notiCountElement.text() == '') {
                            notiCountElement.text('1');
                        } else {
                            notiCountElement.text(parseInt(notiCountElement.text()) + 1);
                        }
                    }

                    if ('<?= $role; ?>' == '<?= TUTOR; ?>' && tutorId == '<?= $vendorId; ?>') {

                        notifyText = (sentByStudent) ? "You've just received message from student " + studentName : "You've just sent message to student " + studentName;
                        $('ul.navbar-nav li.notifications-menu ul.dropdown-menu li ul.menu')
                            .prepend('<li><a href="#"><i class="fa fa-users text-aqua"></i>'+ notifyText + '</a> </li>');

                        let notiCountElement = $('ul.navbar-nav li.notifications-menu a.dropdown-toggle span.label-warning');
                        if (notiCountElement.text() == '') {
                            notiCountElement.text('1');
                        } else {
                            notiCountElement.text(parseInt(notiCountElement.text()) + 1);
                        }
                    }
                }
            });
            // })
        </script>
    </aside>
