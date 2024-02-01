<?php
    require("inc/iTweet.php");

    ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Twitter Panel Version 1.4 | </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">

  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>	


  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="bower_components/morris.js/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="bower_components/jvectormap/jquery-jvectormap.css">
  <link rel="stylesheet" href="../../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="index.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>T</b>WT</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>TwitX</b>Panel</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs">Admin</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->

              <!-- Menu Body -->

              <!-- Menu Footer-->
              <li >

                  <a type="submit" onClick="parent.location='index.php?do=logout'" class="btn btn-default btn-flat">Çıkış Yap</a>

              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->

        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Admin</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->

      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">

        <li class="active treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Hesap İşlemleri</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="?page=users"><i class="fa fa-circle-o"></i>Kullanıcılar</a></li>
            <li><a href="?page=addUsers"><i class="fa fa-circle-o"></i>Kullanıcı Ekle</a></li>
            <li><a href="?page=checkUsers"><i class="fa fa-circle-o"></i>Kullanıcıları Check Et </a></li>
          </ul>
        </li>
        <li class="active treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Twitter İşlemleri</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li ><a href="?page=favorite"><i class="fa fa-circle-o"></i>Favori Gönder</a></li>
            <li><a href="?page=retweet"><i class="fa fa-circle-o"></i>Retweet Gönder</a></li>
            <li><a href="?page=follower"><i class="fa fa-circle-o"></i>Takipçi Gönder</a></li>
            <li><a href="?page=tweet"><i class="fa fa-circle-o"></i>Tweet Gönder</a></li>
          </ul>
        </li>
        



      
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h2>
        Güncelleme</h2>

       <h1><span style="color: red;">Son güncelleme 01.07.2020 Tarihinde yapılmıştır. Version 1.4</span></h2>


    </section>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>	
    <?php if ($_GET["page"] == "follower") {
        if ($_POST["sendFollower"]) {
            $response = $iTweet->addFollower($_POST["username"], $_POST["count"]);
                        echo "<div class='alert alert-success' role='alert'>" . $response["ok"] . "Takipçi başarıyla gönderildi.</div>";

          
        }
        ?>
        
        <div class="row">
            <div class="col-sm-12">
                <div class="col-md-24">

                        <div class="clear"></div>
                    </div>
                    <div class="panel-body">
                        <form name="addAccount" action="?page=follower" method="post">
                            <h3 for="box-title">Kullanıcı adı</h3>
                            <input type="text" name="username" class="form-control input-lg" placeholder="kullaniciadi"/>
                            <h3 for="box-title">Sayı</h3>
                            <input type="text" name="count" class="form-control input-lg" placeholder="2000"/>
                            <button  id="custom"name="sendFollower" value="Takipçi Gönder" class="btn bg-purple btn-flat margin"/>Takipçi Gönder</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
          $('#custom').on('click',function(){
		swal({
			title: "Başlık",
			text: "Açıklama",
			icon: "success",  //"warning", "error", "success" and "info".
			button: {
				text: "OK", //Buton yazısı
				value: true,
				visible: true, //Görünsün mü? true, false
				className: "", //class değiştirmek istersen
				closeModal: true, //Modal kapatılsın mı, true, false 
			},
			closeOnClickOutside: false, //Modal dışında tıklayınca kapansın mı true, false 
			closeOnEsc: false, //Modal ESC ile kapansın mı true, false 
			dangerMode: true, //Buton rengi kırmızıya döner true, false
			timer: 3000, //Belli bir süre sonra otomatik kapanır. (ms cinsinden)
		
		});
	});
  </script>

    <?php } ?>
    <?php if ($_GET["page"] == "favorite") {
        if ($_POST["sendFavorite"]) {
            $response = $iTweet->addFavorite($_POST["tweet_id"], $_POST["count"]);
            echo "<div class='alert alert-success' role='alert'>" . $response["ok"] . "Favori başarıyla gönderildi.</div>";
        }
        ?>



        <div class="row">
            <div class="col-sm-12">
                <div class="col-md-24">

                        <div class="clear"></div>
                    </div>
                    <div class="panel-body">

                        <form name="addAccount" action="?page=favorite" method="post">
                            <h3 for="box-title">Tweet ID</h3>
                            <input type="text" name="tweet_id" class="form-control input-lg" placeholder="306766026899607552" required=""/>
                            <h3 for="box-title">Sayı</h3>
                            <input type="text" name="count" class="form-control input-lg" placeholder="2000"/>
                              <button  id="custom"name="sendFavorite" value="Favori Gönder" class="btn bg-purple btn-flat margin">Favori Gönder</button>


                        </form>

                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php if ($_GET["page"] == "retweet") {
        if ($_POST["sendRetweet"]) {
            $response = $iTweet->addRetweet($_POST["tweet_id"], $_POST["count"]);
            echo "<div class='alert alert-success' role='alert'>" . $response["ok"] . " retweet gönderildi.</div>";
        }
        ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="col-md-24">

                        <div class="clear"></div>
                    </div>
                    <div class="panel-body">
                        <form name="addAccount" action="?page=retweet" method="post">
                            <h3 for="box-title">Tweet ID</h3>
                            <input type="text" name="tweet_id" class="form-control input-lg" placeholder="306766026899607552"/>
                            <h3 for="box-title">Sayı</h3>
                            <input type="text" name="count" class="form-control input-lg" placeholder="2000"/>
                            <button  id="custom"name="sendRetweet" value="Retweet Gönder" class="btn bg-purple btn-flat margin"/>Retweet Gönder</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        

        <?php if ($_GET["page"] == "users") {
        $myAccounts = $iTweet->getUsers();
        if (isset($_GET["sil"])) {
            unlink("data/{$_GET["sil"]}.json");
        }
        ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                             
                                <td><div    class="text"><a type="button" class="btn bg-purple btn-flat margin">Kullanıcı adı</a></div></td>
                                <td><div    class="text"><a type="button" class="btn bg-purple btn-flat margin">Bulunduğu api</a></div></td>
                                <td><div    class="text"><a type="button" class="btn bg-purple btn-flat margin">İşlem</a></div></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($myAccounts as $key => $account) { ?>
                                <tr>
                                    <td><?= $account->username; ?></td>
                                    <td><?= $account->consumer_name; ?></td>
                                    <td><a type="button" class="btn bg-purple btn-flat margin" href="?page=users&sil=<?= $account->username; ?>">sil</a></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if ($_GET["page"] == "addUsers") {
        if ($_POST["addUser"]) {
            $users = explode("\r\n", $_POST['userlist']);
              $response = $iTweet->importUsers($users);
            if ($response["ok"] > 0) {
                echo "<div class='alert alert-success' role='alert'>" . $response["ok"] . " Kullanıcı başarıyla eklendi.</div>";
            } else {
                echo "<div class='alert alert-danger' role='alert'>Kullanıcı Eklenemedi.</div>";
            }
        }
        ?>
        <div class="row">
            <div class="col-sm-12">

                <div class="col-md-24">

                        <div class="clear"></div>
                    </div>
                    <div class="panel-body">
                        <form name="sendMessage" action="" method="post">
                            <h3 for="box-title">Hesap Ekle</h3>
                                    <textarea name='userlist' rows='10' cols='50' class="form-control input-lg"
                                              placeholder='id:şifre' required></textarea>


                            <button  id="custom"name="addUser" value="Ekle"
                                   class="btn bg-purple btn-flat margin"/> Hesapları Ekle</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
 <?php if ($_GET["page"] == "checkUsers") {
        if ($_POST["checkUser"]) {
            $response = $iTweet->checkUsers();
            echo "<div class='alert alert-success' role='alert'>" . $response["no"] . "Kullanıcı başarıyla silindi.</div>";
        }
        ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="col-md-24">
                    <div class="box-header with-border">
                        <h2><span style="color: grey;">Data Ayıklama Servisi</span></h2>
                        <div class="clear"></div>
                    </div>
                    <div class="panel-body">
                        <form name="addAccount" action="?page=checkUsers" method="post">
                          <span style="color: red;"><b>Aşağıdaki butona bastığınızda sistemden çıkmış kullanıcılar siler.<b></span>
                            <br>
                            <button  id="custom"name="checkUser" value="Datayı Kontrol Et!" class="btn bg-purple btn-flat margin"/>Kontrol Et</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php if ($_GET["page"] == "tweet") {
        if ($_POST["sendRetweet"]) {
            $response = $iTweet->addRetweet($_POST["tweet_id"], $_POST["count"]);
            echo "<div class='alert alert-success' role='alert'>" . $response["ok"] . "Retweet başarıyla gönderildi.</div>";
        }
        ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="col-md-24">

                        <div class="clear"></div>
                    </div>
                    <div class="panel-body">
                        <form name="addAccount" action="?page=retweet" method="post">
                            <h3 for="box-title">Gönderilecek Tweet</h3>
                            <input type="text" name="tweet_id" class="form-control input-lg" placeholder="Merhaba Dünya"/>
                            <h3 for="box-title">Kaç Tweet Gönderilecek</h3>
                            <input type="text" name="count" class="form-control input-lg" placeholder="2000"/>
                            <button  id="custom"name="sendRetweet" value="Tweet Gönder" class="btn bg-purple btn-flat margin"/>TWeet Gönder</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>

    

    <!-- Main content -->
   
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.4
    </div>
    <strong>Copyright &copy; 2020 TwitX  </a>.</strong> All rights
    reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark" style="display: none;">
    <!-- Create the tabs -->

    <!-- Tab panes -->

  </aside>


</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="bower_components/raphael/raphael.min.js"></script>
<script src="bower_components/morris.js/morris.min.js"></script>
<!-- Sparkline -->
<script src="bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="bower_components/moment/min/moment.min.js"></script>
<script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- Slimscroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<script src="ad.js"></script>


<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>

<script>
 $('#custom').on('click',function(){
    swal({
      title: "İşlem Gönderiliyor",
      text: "",
      icon: "success",  
      button: {
        text: "OK", //Buton yazısı
        value: true,
        visible: true, //Görünsün mü? true, false
        className: "", //class değiştirmek istersen
        closeModal: true, //Modal kapatılsın mı, true, false 
      },
      closeOnClickOutside: false, //Modal dışında tıklayınca kapansın mı true, false 
      closeOnEsc: false, //Modal ESC ile kapansın mı true, false 
      dangerMode: true, //Buton rengi kırmızıya döner true, false
      timer: 3000, //Belli bir süre sonra otomatik kapanır. (ms cinsinden)

    });
  });
  
  </script>



</body>

</html>
