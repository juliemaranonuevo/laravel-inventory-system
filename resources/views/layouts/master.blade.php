<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- <link rel="shortcut icon" href="{{ asset('/template/dist/img/lagunaseal.png') }}" type="image/x-icon"> -->
  <title>IS | @yield('page_title', $page['title'])</title>
  <link rel="shortcut icon" href="{{ asset('/img/seal_laguna.png') }}" type="image/x-icon">  
  <!-- CSRF Token -->
  <meta name="_token" content="{{ csrf_token() }}">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  @include('layouts.header')
  @yield('page_css')
</head>
<style type="text/css">
  .capitalized{
    text-transform: capitalize;
  }
</style>
<body class="hold-transition skin-blue sidebar-mini fixed">
<div class="wrapper">
  <header class="main-header">
    <!-- Logo -->
    <a href="{{ route('dashboard') }}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>IS</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Inventory System</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="/img/" class="user-image" alt="User Image">
              <span class="hidden-xs">{{ Auth::user()->name }}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="/img/" class="img-circle" alt="User Image">

                <p>
                {{ Auth::user()->name }}
                  <small>{{ App\Office::findOrFail(Auth::user()->office_id)->office_code }}</small>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="/users/change-password" class="btn btn-default btn-flat">Change Password</a>
                </div>
                <div class="pull-right">
                  <a href="{{ route('logout') }}" class="btn btn-default btn-flat" onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();">Sign out</a>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
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
      <!-- search form -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>

        <li id="dashboard">
          <a href="{{ route('dashboard') }}">
            <i class="fa fa-th"></i> <span>Dashboard</span>
            <span class="pull-right-container">
            </span>
          </a>
        </li>
        @if(Auth::user()->user_type == 0)
        <li id="inventory" class="treeview">
          <a href="#">
            <i class="fa fa-cubes"></i> <span> Inventory</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
          </a>
          <ul class="treeview-menu">

             <li id="overview">
              <a href="/inventories">
                <i class="fa fa-circle-o"></i>
                  Overview
              </a>
            </li>

            <li id="transaction_logs">
              <a href="/transaction-logs/select-type">
                <i class="fa fa-circle-o"></i>
                  Transaction Logs
              </a>
            </li>
          </ul>
        </li>

        <li id="reports">
          <a href="/reports">
            <i class="fa fa-list-alt"></i> <span>Reports</span>
            <span class="pull-right-container">
            </span>
          </a>
        </li>

        <li class="header">ADMINISTRATION</li>
        <li id="reference_library" class="treeview">
          <a href="#">
            <i class="fa fa-suitcase"></i> <span> Reference Library</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
          </a>
          <ul class="treeview-menu">
              <li id="fields">
                <a href="{{ route('reference_library.fields') }}">
                  <i class="fa fa-circle-o"></i>
                    Fields
                </a>
              </li>

              <li id="categories">
                <a href="{{ route('reference_library.categories') }}">
                  <i class="fa fa-circle-o"></i>
                    Categories
                </a>
              </li>
          </ul>
        </li>
      
        <li id="audit_trails">
          <a href="/audit-trails">
            <i class="ion ion-clipboard"></i> <span>Audit Trail</span>
            <span class="pull-right-container">
            </span>
          </a>
        </li>
        @else
        <li id="inventory" class="treeview">
          <a href="#">
            <i class="fa fa-cubes"></i> <span> Inventory</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
          </a>
          <ul class="treeview-menu">
             <li id="overview">
              <a href="/inventories">
                <i class="fa fa-circle-o"></i>
                  Overview
              </a>
            </li>
            <li id="transaction_logs">
              <a href="/transaction-logs/select-type">
                <i class="fa fa-circle-o"></i>
                  Transaction Logs
              </a>
            </li>
          </ul>
        </li>
        
        <li id="reports">
          <a href="/reports">
            <i class="fa fa-list-alt"></i> <span>Reports</span>
            <span class="pull-right-container">
            </span>
          </a>
        </li>
        
        <li class="header">ADMINISTRATION</li>
        <li id="reference_library" class="treeview">
          <a href="#">
            <i class="fa fa-suitcase"></i> <span> Reference Library</span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
          </a>
          <ul class="treeview-menu">
              <li id="fields">
                <a href="{{ route('reference_library.fields') }}">
                  <i class="fa fa-circle-o"></i>
                    Fields
                </a>
              </li>

              <li id="categories">
                <a href="{{ route('reference_library.categories') }}">
                  <i class="fa fa-circle-o"></i>
                    Categories
                </a>
              </li>
          </ul>
        </li>
        <li id="offices">
          <a href="/offices">
            <i class="fa fa-bank"></i> <span>Add New Office</span>
            <span class="pull-right-container">
            </span>
          </a>
        </li>
        <li id="users">
          <a href="/users">
            <i class="fa fa-gear"></i> <span>Manage Account</span>
            <span class="pull-right-container">
            </span>
          </a>
        </li>
        <li id="audit_trails">
          <a href="/audit-trails">
            <i class="ion ion-clipboard"></i> <span>Audit Trail</span>
            <span class="pull-right-container">
            </span>
          </a>
        </li>
        @endif
        </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

  <section class="content-header">
        <h1>
        @yield('page_title')
        <small>@yield('page_subtitle')</small>
        </h1>
        @yield('breadcrumb')
  </section>

  <b></b> 
  
    <!-- Main content -->
      @yield('content')
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
      <strong>
        <a href="#">
          MARU
        </a>
      </strong>
  </footer>
<!-- ./wrapper -->
</div>
</body>
@include('layouts.footer')
@yield('page_script')
</html>



