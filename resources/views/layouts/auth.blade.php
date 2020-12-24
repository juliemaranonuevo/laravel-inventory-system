<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>PMIS | @yield('head_title')</title>
        <link rel='shortcut icon' type='image/x-icon' href='/favicon.ico'/>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta http-equiv="refresh" content="600" />

        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="/vendor/AdminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="/vendor/AdminLTE/bower_components/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="/vendor/ionicons/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="/vendor/AdminLTE/dist/css/AdminLTE.min.css">
        <!-- App CSS -->
        <link rel="stylesheet" href="/vendor/css/app.css">
        <!-- Custom style -->
        <style>
            h4 {
                padding: 0;
                margin: 0;
            }
            h4, h6 {
                color: #FAFAFA;
            }
            .title {
                color: greenyellow;
            }
            .title b {
                color: #8DC3E3;
            }
            .login-page {
                background-color: #7EBAEA;
                background-image: url('/img/login-bg.png');
                background-size: auto 100%;
                background-repeat: no-repeat;
                background-position: right;
                overflow: hidden;
            }
            .login-box-body {
                position: relative;
                background: none;
            }
            .col-xs-5 {
                padding-right: 0;
            }
        </style>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="row login-logo">
                <div class="col-xs-5 text-right"><img src="/img/seal_laguna.png" height="100px" width="100px"></div>
                <div class="col-xs-7 text-left"><span class="title"><b>I</b>S</span><br><h4>Inventory System</h4></div>
            </div>
            <!-- /.login-logo -->
            <div class="login-box-body">
                <div class="login-bg"></div>

                @yield('content')

            </div>
            <!-- /.login-box-body -->
            <div class="text-center">
                <br><br>
                <img src="/img/auth_footer.png">
            </div>
        </div>
        <!-- /.login-box -->
        
        <!-- jQuery 3 -->
        <script src="/vendor/AdminLTE/bower_components/jquery/dist/jquery.min.js"></script>
        <!-- Bootstrap 3.3.7 -->
        <script src="/vendor/AdminLTE/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- AdminLTE App -->
        <script src="/vendor/AdminLTE/dist/js/adminlte.min.js"></script>

        <!-- Scripts -->
        <script src="/js/loading.js"></script>
    </body>
</html>