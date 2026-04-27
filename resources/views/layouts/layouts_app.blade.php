<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>PLN UIP2B JAMALI OFFLINE</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('template_app') }}/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('template_app') }}/dist/css/adminlte.min.css">
  
  <!-- jquery ui dan signature-->
  <link rel="stylesheet" href="{{ asset('template_app') }}/plugins/jquery-ui/jquery-ui.css">
  <link rel="stylesheet" href="{{ asset('template_app') }}/dist/css/adminlte.min.css?v=3.2.0">

  <!-- jQuery -->
  <script src="{{ asset('template_app') }}/plugins/jquery/jquery.min.js"></script>
  <!-- favicon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('template_app') }}/dist/img/logo-pln.png">

  <style>

      .container { width: 100% !important; }
      .select2-container { width: 100% !important; }
      .error { color: red; padding-left: 5px; }
      .table td button { margin: 1px !important; }
      .modal-xl { width: 95%; }
      .sweet-overlay { z-index: 1999; }

      /* Reduce font size and padding for a more compact table */
      .dataTables_wrapper .dataTables_length,
      .dataTables_wrapper .dataTables_filter,
      .dataTables_wrapper .dataTables_info,
      .dataTables_wrapper .dataTables_paginate {
          font-size: 12px;
      }

      /* Reduce padding in the table cells */
      .dataTables_wrapper .dataTable th,
      .dataTables_wrapper .dataTable td {
          padding: 5px 10px;
      }

      /* Reduce the height of the table rows */
      table.dataTable tr {
          height: 30px;
      }

      /* Adjust pagination and other elements */
      .dataTables_paginate {
          padding-top: 5px;
      }

      .dataTables_filter input {
          font-size: 12px;
          padding: 4px;
      }

      .dataTables_length select {
          font-size: 12px;
          padding: 4px;
      }
      
      div.dataTables_wrapper div.dataTables_length select {
          width: 50px !important;
      }

      #modal-loading {
          position: fixed;
          top: 0;
          right: 0;
          bottom: 0;
          left: 0;
          text-align: center;
          vertical-align: middle;
          padding-top: 100px;
          background: black; 
          color: white; 
          opacity: 0.7; 
          display: none;
          z-index: 9999;
      }
      
      #data_table th, td {
          white-space: nowrap;
      }

      table.dataTable thead .sorting:after,
      table.dataTable thead .sorting:before,
      table.dataTable thead .sorting_asc:after,
      table.dataTable thead .sorting_asc:before,
      table.dataTable thead .sorting_asc_disabled:after,
      table.dataTable thead .sorting_asc_disabled:before,
      table.dataTable thead .sorting_desc:after,
      table.dataTable thead .sorting_desc:before,
      table.dataTable thead .sorting_desc_disabled:after,
      table.dataTable thead .sorting_desc_disabled:before {
          bottom: .5em;
      }

      #data_table tbody tr.selected {
        background-color: #607d8b !important;
      }

      #data_table tbody tr.selectedPosting {
          background-color: #28a745 !important;
          /*color: white;*/
      }

      #data-table tbody tr.selectedNonAktif {
          background-color: #e74c3c !important;
          /*color: white;*/
      }
      
      .table td button { margin: 1px !important; }

      /*loading*/
      .overlay{
          display: flex;
          position: fixed;
          width: 100%;
          height: 100%;
          top: 0;
          left: 0;
          z-index: 9999;      
          /*background: rgba(255,255,255,0.8) url("http://i.stack.imgur.com/FhHRx.gif") center no-repeat;*/
          background: rgba(255,255,255,0.8) url("{{ asset('file/loading.gif') }}") center no-repeat;
      }
      
      /* Turn off scrollbar when body element has the loading class */
      body.loading{
          overflow: hidden;   
      }
      
      /* Make spinner image visible when body element has the loading class */
      body.loading .overlay{
          display: block;
      }

      .radio-group-status {
          display: flex;
          gap: 20px; /* Space between radio options */
          align-items: center;
      }

      /* Aligns radio button with text */
      .radio-label {
          display: flex;
          align-items: center;
          gap: 8px; /* Space between radio button and text */
          cursor: pointer;
      }

      /* Slightly enlarge radio buttons for better UX */
      .radio-label input[type="radio"] {
          transform: scale(1.2);
      }
      
      .main-sidebar .brand-link {
          border-bottom: 1px solid rgba(255, 255, 255, 0.1);
          background-color: #00a9ce;
          color: #fff;
      }
      .main-header.navbar {
          background: 
              url('{{ url("/file/bg-header.jpeg") }}') left/contain no-repeat,
              white 60%;
          border-bottom: 1px solid rgba(0, 0, 0, 0.1);
          height: 60px; /* Adjust height as needed */
        }

      /* Fallback for image loading */
      .main-header.navbar::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 60%;
        height: 100%;
        z-index: -1;
      }

      /* Yellow accent elements */
      .brand-link {
        border-bottom: 3px solid #ffc107 !important; /* Yellow border */
      }

      /* Adjust navbar items positioning */
      .navbar-nav {
        height: 100%;
        display: flex;
        align-items: center;
      }

      /* White text for image portion */
      .navbar-nav:first-child .nav-link {
        color: white !important;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
      }

      /* Dark text for white portion */
      .navbar-nav.ml-auto .nav-link {
        color: #495057 !important;
      }

      /* Yellow hover effects */
      .nav-link:hover, .dropdown-item:hover {
        color: #00a9ce !important;
      }

      /* Profile image with yellow border */
      .user-image, .img-circle {
        border: 2px solid #00a9ce;
      }

      .nav-sidebar>.nav-item.menu-open>.nav-link {
        color: #00a9ce !important;
      }
      .nav-link.active {
        color: #00a9ce !important;
      }

      .card-body-filter{
        padding: 0.5rem 1rem 0.5rem 1rem;
      }
      .card-footer-filter{
        padding: .5rem 1.25rem;
      }
      .text-xxs { font-size: 12px !important; }

  
  </style>
</head>
<body class="hold-transition sidebar-mini text-sm ">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Tambahan Menu Dropdown profile baru-->
        <li class="nav-item dropdown user-menu">
          <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
              <img src="{{ asset('file') }}/profil_avatar.jpg" class="user-image img-circle elevation-2" alt="User Image"> 
              <i class="right fas fa-angle-down"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

              <li class="user-header">
                  <img src="{{ asset('file') }}/profil_avatar.jpg" class="img-circle elevation-2" alt="User Image"> 
                  <p>
                    {{ $user['username'] }}
                  </p>
              </li>

              <li class="user-footer" style="background-color: #e5e5e5;">
                  <a href="{{ url('/admin/akun') }}" class="btn btn-default btn-flat float-left ">Akun</a>
                  <a href="#" class="btn btn-default btn-flat float-right btn-logout">Keluar</a>
              </li>
          </ul>
      </li>
      <!-- ujung Menu Dropdown profile baru--> 
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-light-danger elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="{{ asset('template_app') }}/dist/img/pln.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8; height:50px">
      <!-- <span class="brand-text font-weight-light text-md">OFFLINE DATABASE MCC</span>
      <span class="brand-text font-weight-light text-sm">UIP2B JAMALI</span> -->
      <div class="d-flex flex-column">
        <span class="brand-text font-weight-light text-md" style="margin-top:-10px">OFFLINE DATABASE MCC</span>
        <span class="brand-text font-weight-light text-xxs">UIP2B JAWA MADURA DAN BALI</span>
      </div>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          @include('layouts.menu', ['items' => $menu['data']])
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

      @yield('content')

  </div>
  <!-- /.content-wrapper -->

  

  <div id="modal-loading"> 
      <h3><i class="fa fa-spinner fa-spin fa-5x"></i></h3>
      <h3>Memperoses...</h3>
  </div>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
    <!-- jQuery -->
    <script src="{{ asset('template_app') }}/plugins/jquery/jquery.min.js"></script>
    
    <!-- jqwidget -->
    <link rel="stylesheet" href="{{ asset('js/jqwidgets/styles/jqx.base.css') }}">
    <link rel="stylesheet" href="{{ asset('js/jqwidgets/styles/jqx.darkblue.css') }}">
    <script src="{{ asset('js/jqwidgets/jqxcore.js') }}"></script>
    <script src="{{ asset('js/jqwidgets/jqxdata.js') }}"></script>
    <script src="{{ asset('js/jqwidgets/jqxbuttons.js') }}"></script>
    <script src="{{ asset('js/jqwidgets/jqxscrollbar.js') }}"></script>
    <script src="{{ asset('js/jqwidgets/jqxmenu.js') }}"></script>
    <script src="{{ asset('js/jqwidgets/jqxdropdownlist.js') }}"></script>
    <script src="{{ asset('js/jqwidgets/jqxlistbox.js') }}"></script>
    <script src="{{ asset('js/jqwidgets/jqxgrid.js') }}"></script>
    <script src="{{ asset('js/jqwidgets/jqxgrid.pager.js') }}"></script>
    <script src="{{ asset('js/jqwidgets/jqxgrid.filter.js') }}"></script>
    <script src="{{ asset('js/jqwidgets/jqxgrid.sort.js') }}"></script>
    <script src="{{ asset('js/jqwidgets/jqxgrid.selection.js') }}"></script>
    <script src="{{ asset('js/jqwidgets/jqxgrid.edit.js') }}"></script>
    <script src="{{ asset('js/jqwidgets/jqxgrid.columnsreorder.js') }}"></script>
    <script src="{{ asset('js/jqwidgets/jqxgrid.columnsresize.js') }}"></script>
    <script src="{{ asset('js/jqwidgets/jqxdatatable.js') }}"></script>
    <script src="{{ asset('js/jqwidgets/jqxtreegrid.js') }}"></script>
    <script src="{{ asset('js/jqwidgets/jqxloader.js') }}"></script>
    <script src="{{ asset('js/jqwidgets/jqxcheckbox.js') }}"></script>
    <!-- <script src="{{ asset('js/jqwidgets/jqxdata.export.js') }}"></script> -->
    <script src="{{ asset('js/jqwidgets/jqxgrid.export.js') }}"></script>
    <script src="{{ asset('js/jqwidgets/jqxexport.js') }}"></script>
    <script src="{{ asset('js/jqwidgets/jqxgrid.sort.js') }}"></script>
    <script src="{{ asset('js') }}/xlsx.full.min.js"></script>
    <script src="{{ asset('js/grid-export.js') }}"></script>

    
    <!-- Bootstrap 4 -->
    <script src="{{ asset('template_app') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('template_app') }}/dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('template_app') }}/dist/js/demo.js"></script>

    <!-- sweetalert2 -->
    <script src="{{ asset('template_app') }}/plugins/sweetalert2/sweetalert2.all.js"></script>
    <script src="{{ asset('template_app') }}/plugins/sweetalert2/sweetalert2.all.min.js"></script>

    <link rel="stylesheet" href="{{ asset('template_app') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('template_app') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <script src="{{ asset('template_app') }}/plugins/select2/js/select2.full.min.js"></script> 
    <script src="{{ asset('template_app') }}/plugins/jquery-validation/jquery.validate.min.js"></script>

    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('template_app') }}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
 
    <!-- ExcelJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
    <!-- FileSaver untuk download -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

    
    <script>
      var mainServerUrl = '{{ url("/") }}';
      var logoutUrl     = '{{ route("logout") }}';
      var currentPath = window.location.pathname;
      const basePath = currentPath.split('/').slice(0, -2).join('/');
      var segments = currentPath.split('/');
      var desiredPath = '/' + segments[1];


        $(document).on({
            ajaxStart: function(){
                $("body").addClass("loading"); 
            },
            ajaxStop: function(){ 
                $("body").removeClass("loading"); 
            }    
        });
      
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.btn-logout').on('click', function (e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Warning!',
                    text: "Anda yakin akan keluar?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: "No",
                    confirmButtonText: 'Yes'
                  }).then((result) => {
                    if (result.isConfirmed) {
                      $.ajax({
                        url: logoutUrl,
                        type: 'POST',
                        headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        data: {},
                        beforeSend: function(data) {
                            $('#modal-loading').fadeIn('fast');
                        },
                        success: function(data) {
                            location.href = mainServerUrl;
                        },
                        error: function(data) {
                            location.reload();
                        },
                        complete: function(data) {
                            $('#modal-loading').fadeOut('fast');
                        }
                    })
                    }
                  });
            });
        
        });
    </script>
  
  <script src="{{ asset('js') }}/global.js"></script>
  @stack('scripts')
  
</body>
</html>
