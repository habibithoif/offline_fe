<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>PLN UIP2B JAMALI OFFLINE</title>

  <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
  <link rel="stylesheet" href="{{ asset('template_app') }}/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('template_app') }}/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="{{ asset('template_app') }}/plugins/jquery-ui/jquery-ui.css">
  <link rel="stylesheet" href="{{ asset('template_app') }}/dist/css/adminlte.min.css?v=3.2.0">

  <script src="{{ asset('template_app') }}/plugins/jquery/jquery.min.js"></script>
  <link rel="icon" type="image/x-icon" href="{{ asset('template_app') }}/dist/img/logo-pln.png">

  <style>
    :root {
      --pln-blue: #003366;
      --pln-navy: #002244;
      --pln-red: #e30613;
      --pln-green: #009639;
      --pln-dark: #0a1628;

      --bg-body: #f0f2f5;
      --bg-wrapper: #f0f2f5;
      --bg-card: #ffffff;
      --bg-card-hover: #ffffff;
      --bg-input: #ffffff;
      --bg-sidebar: linear-gradient(180deg, #0a1628 0%, #0f1f3a 40%, #142a4a 100%);
      --text-primary: #1a2332;
      --text-secondary: #495057;
      --text-muted: #6c757d;
      --border-color: rgba(0,0,0,0.06);
      --shadow-card: 0 1px 3px rgba(0,0,0,0.04), 0 2px 12px rgba(0,0,0,0.03);
      --shadow-card-hover: 0 2px 8px rgba(0,0,0,0.06), 0 4px 20px rgba(0,0,0,0.04);
      --scrollbar-thumb: rgba(0,51,102,0.25);
      --scrollbar-thumb-hover: rgba(0,51,102,0.4);
      --btn-default-bg: rgba(0,0,0,0.04);
      --btn-default-color: #495057;
      --btn-default-hover-bg: rgba(0,0,0,0.08);
      --breadcrumb-bg: rgba(0,0,0,0.03);
      --label-color: #495057;
      --select-border: rgba(0,0,0,0.1);
    }

    body.dark-mode {
      --bg-body: #121926;
      --bg-wrapper: #121926;
      --bg-card: #1e293b;
      --bg-card-hover: #253244;
      --bg-input: #1e293b;
      --text-primary: #e2e8f0;
      --text-secondary: #94a3b8;
      --text-muted: #64748b;
      --border-color: rgba(255,255,255,0.06);
      --shadow-card: 0 1px 3px rgba(0,0,0,0.2), 0 2px 12px rgba(0,0,0,0.15);
      --shadow-card-hover: 0 2px 8px rgba(0,0,0,0.25), 0 4px 20px rgba(0,0,0,0.2);
      --scrollbar-thumb: rgba(148,163,184,0.25);
      --scrollbar-thumb-hover: rgba(148,163,184,0.4);
      --btn-default-bg: rgba(255,255,255,0.06);
      --btn-default-color: #94a3b8;
      --btn-default-hover-bg: rgba(255,255,255,0.1);
      --breadcrumb-bg: rgba(255,255,255,0.04);
      --label-color: #94a3b8;
      --select-border: rgba(255,255,255,0.12);
    }

    * { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; }

    body {
      background: var(--bg-body);
      font-family: 'Inter', sans-serif;
      color: var(--text-primary);
      transition: background 0.3s ease, color 0.3s ease;
    }

    /* ===== SCROLLBAR ===== */
    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: var(--scrollbar-thumb); border-radius: 8px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--scrollbar-thumb-hover); }

    /* ===== TOP NAV LAYOUT ===== */
    .layout-top-nav .wrapper { background: var(--bg-wrapper); }

    /* ===== NAVBAR BRAND (LOGO + TEXT) ===== */
    .navbar-brand {
      display: flex;
      align-items: center;
      gap: 10px;
      padding-top: 0;
      padding-bottom: 0;
    }
    .navbar-brand .brand-text {
      display: flex;
      flex-direction: column;
      justify-content: center;
      line-height: 1;
    }

    /* ===== NAVBAR / HEADER ===== */
    .main-header.navbar {
      background: linear-gradient(135deg, #ffffff 0%, #f8f9fc 100%) !important;
      border-bottom: 2px solid rgba(227,6,19,0.08) !important;
      box-shadow: 0 2px 16px rgba(0,0,0,0.06);
      min-height: 60px;
      padding: 0 16px;
      transition: background 0.3s ease;
    }

    body.dark-mode .main-header.navbar {
      background: linear-gradient(135deg, #1a2332 0%, #0f1a28 100%) !important;
      border-bottom: 2px solid rgba(227,6,19,0.15) !important;
      box-shadow: 0 2px 16px rgba(0,0,0,0.2);
    }

    /* ===== FOOTER ===== */
    .main-footer {
      background: var(--bg-card);
      border-top: 1px solid var(--border-color);
      padding: 12px 20px;
      transition: background 0.3s ease, border-color 0.3s ease;
    }
    .main-footer .footer-inner {
      display: flex;
      justify-content: space-between;
      align-items: center;
      max-width: 100%;
      font-size: 12px;
    }
    .main-footer .footer-copy {
      color: var(--text-muted);
    }
    .main-footer .footer-version {
      color: var(--text-muted);
      font-weight: 500;
    }

    .navbar-brand .brand-text .title {
      font-size: 16px;
      font-weight: 700;
      color: var(--pln-navy);
      line-height: 1.15;
      letter-spacing: -0.3px;
      transition: color 0.3s ease;
    }

    body.dark-mode .navbar-brand .brand-text .title { color: #e2e8f0; }

    .navbar-brand .brand-text .sub {
      font-size: 11px;
      font-weight: 500;
      color: rgba(0,34,68,0.4);
      letter-spacing: 0.5px;
      margin-top: 1px;
      transition: color 0.3s ease;
    }

    body.dark-mode .navbar-brand .brand-text .sub { color: rgba(255,255,255,0.3); }

    /* ===== NAVBAR MENU ===== */
    .navbar-nav .nav-item > .nav-link {
      color: rgba(0,34,68,0.65) !important;
      font-weight: 500;
      font-size: 13px;
      padding: 18px 14px !important;
      border-radius: 8px;
      transition: all 0.2s ease;
      display: flex;
      align-items: center;
      gap: 6px;
      white-space: nowrap;
    }

    body.dark-mode .navbar-nav .nav-item > .nav-link { color: rgba(255,255,255,0.6) !important; }
    body.dark-mode .navbar-nav .nav-item > .nav-link:hover,
    body.dark-mode .navbar-nav .nav-item > .nav-link:focus { color: #fff !important; background: rgba(255,255,255,0.06); }
    body.dark-mode .navbar-nav .nav-item > .nav-link.active { color: #fff !important; background: rgba(227,6,19,0.25); }
    body.dark-mode .navbar-nav .nav-item > .nav-link.active::after { background: var(--pln-red); }

    .navbar-nav .nav-item > .nav-link:hover,
    .navbar-nav .nav-item > .nav-link:focus {
      color: var(--pln-red) !important;
      background: rgba(227,6,19,0.05);
    }

    .navbar-nav .nav-item > .nav-link.active {
      color: var(--pln-red) !important;
      background: rgba(227,6,19,0.07);
      position: relative;
    }

    .navbar-nav .nav-item > .nav-link.active::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 60%;
      height: 2px;
      background: var(--pln-red);
      border-radius: 2px 2px 0 0;
    }

    .navbar-nav .nav-item > .nav-link .nav-icon { font-size: 14px; width: 18px; text-align: center; }

    /* ===== DROPDOWN MENUS ===== */
    .dropdown-menu {
      border: none !important;
      border-radius: 12px !important;
      box-shadow: 0 12px 40px rgba(0,0,0,0.1), 0 2px 8px rgba(0,0,0,0.04) !important;
      padding: 6px !important;
      margin-top: 4px !important;
      min-width: 220px;
    }

    body.dark-mode .dropdown-menu { background: #1e293b !important; box-shadow: 0 12px 40px rgba(0,0,0,0.4) !important; }

    .dropdown-item { padding: 0 !important; border-radius: 8px; }
    .dropdown-item .dropdown-link {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 9px 14px;
      color: var(--text-primary);
      font-size: 13px;
      font-weight: 500;
      text-decoration: none;
      border-radius: 8px;
      transition: all 0.15s ease;
    }

    .dropdown-item .dropdown-link:hover { background: rgba(227,6,19,0.06); color: var(--pln-red); }
    .dropdown-item.active .dropdown-link { background: rgba(227,6,19,0.08); color: var(--pln-red); }
    .dropdown-submenu > .dropdown-link::after {
      content: '\f054'; font-family: 'Font Awesome 5 Free'; font-weight: 900;
      font-size: 10px; margin-left: auto; color: rgba(0,0,0,0.2);
    }

    body.dark-mode .dropdown-submenu > .dropdown-link::after { color: rgba(255,255,255,0.2); }

    /* ===== RIGHT NAV (user menu) ===== */
    .navbar-nav.ml-auto .nav-link { color: rgba(0,34,68,0.6) !important; padding: 18px 10px !important; font-size: 14px; }
    .navbar-nav.ml-auto .nav-link:hover { color: var(--pln-red) !important; }
    body.dark-mode .navbar-nav.ml-auto .nav-link { color: rgba(255,255,255,0.6) !important; }

    .user-menu .dropdown-menu .user-header {
      background: linear-gradient(135deg, var(--pln-navy), #003366) !important;
      border-radius: 12px 12px 0 0; padding: 20px;
    }

    .user-menu .dropdown-menu .user-footer { padding: 10px 14px; background: #f8f9fc; border-radius: 0 0 12px 12px; }
    body.dark-mode .user-menu .dropdown-menu .user-footer { background: #1a2332; }

    /* ===== THEME TOGGLE ===== */
    .theme-toggle-btn {
      display: flex; align-items: center; justify-content: center;
      width: 34px; height: 34px; border-radius: 8px;
      border: none; background: rgba(0,0,0,0.04);
      color: rgba(0,34,68,0.5); font-size: 15px;
      cursor: pointer; transition: all 0.2s ease;
    }

    .theme-toggle-btn:hover { background: rgba(227,6,19,0.08); color: var(--pln-red); }
    body.dark-mode .theme-toggle-btn { background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.5); }
    body.dark-mode .theme-toggle-btn:hover { background: rgba(255,255,255,0.1); color: #fff; }

    /* ===== CONTENT ===== */
    .content-wrapper { background: var(--bg-body); padding-bottom: 20px; transition: background 0.3s ease; }
    .content-header { padding: 20px 20px 0; }

    .content-header .breadcrumb { background: var(--breadcrumb-bg); padding: 6px 12px; border-radius: 8px; }
    .content-header .breadcrumb .breadcrumb-item { font-size: 12px; font-weight: 500; }
    .content-header .breadcrumb .breadcrumb-item a { color: var(--pln-blue); }
    .content-header .breadcrumb .breadcrumb-item.active { color: var(--pln-red); }
    .content-header .breadcrumb-item + .breadcrumb-item::before { color: rgba(0,0,0,0.15); }
    body.dark-mode .content-header .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,0.2); }
    body.dark-mode .content-header .breadcrumb .breadcrumb-item a { color: #60a5fa; }

    /* ===== CARDS ===== */
    .card {
      border: none !important;
      border-radius: 12px !important;
      background: var(--bg-card) !important;
      box-shadow: var(--shadow-card) !important;
      transition: box-shadow 0.25s ease, background 0.3s ease !important;
    }

    .card:hover { box-shadow: var(--shadow-card-hover) !important; }

    .card-header {
      background: transparent !important;
      border-bottom: 1px solid var(--border-color) !important;
      padding: 14px 20px !important;
      border-radius: 12px 12px 0 0 !important;
    }

    .card-header.bg-info {
      background: #003366 !important;
      border-radius: 12px 12px 0 0 !important;
    }

    body.dark-mode .card-header.bg-info {
      background: #0a1e3d !important;
    }

    .card-header.bg-info .card-title { color: #fff !important; font-weight: 600; font-size: 14px; }
    .card-header.bg-info .btn-tool { color: rgba(255,255,255,0.7) !important; }
    .card-header.bg-info .btn-tool:hover { color: #fff !important; }
    .card-header .btn { color: inherit; }
    .card-header .btn-default,
    .card-header .btn-secondary {
      color: #fff !important;
      background: rgba(255,255,255,0.15) !important;
      border-color: rgba(255,255,255,0.25) !important;
    }
    .card-header .btn-default:hover,
    .card-header .btn-secondary:hover {
      background: rgba(255,255,255,0.25) !important;
    }

    .card-title { font-weight: 600 !important; font-size: 14px !important; color: var(--text-primary) !important; }
    .card-body { padding: 18px 20px !important; }

    .card-footer {
      background: transparent !important;
      border-top: 1px solid var(--border-color) !important;
      padding: 12px 20px !important;
      border-radius: 0 0 12px 12px !important;
    }

    .card.card-outline.card-primary { border-top: 3px solid var(--pln-red) !important; }

    /* ===== BUTTONS ===== */
    .btn { border-radius: 8px !important; font-weight: 500 !important; font-size: 13px !important; padding: 6px 14px !important; transition: all 0.2s ease !important; }
    .btn-primary { background: linear-gradient(135deg, var(--pln-red), #ff3b30) !important; border: none !important; box-shadow: 0 2px 8px rgba(227,6,19,0.2) !important; }
    .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(227,6,19,0.3) !important; }
    .btn-secondary { background: #6c757d !important; border: none !important; }
    .btn-secondary:hover { background: #5a6268 !important; transform: translateY(-1px); }
    .btn-info { background: linear-gradient(135deg, var(--pln-blue), #004080) !important; border: none !important; }

    .btn-default, .btn-tool {
      background: var(--btn-default-bg) !important;
      border: none !important;
      color: var(--btn-default-color) !important;
      border-radius: 6px !important;
      transition: all 0.2s ease !important;
    }

    .btn-default:hover, .btn-tool:hover { background: var(--btn-default-hover-bg) !important; color: var(--pln-red) !important; }
    .btn-sm { padding: 4px 10px !important; font-size: 12px !important; }

    /* ===== FORM CONTROLS ===== */
    .form-control {
      border-radius: 8px !important;
      border: 1.5px solid var(--select-border) !important;
      font-size: 13px !important;
      padding: 6px 12px !important;
      background: var(--bg-input) !important;
      color: var(--text-primary) !important;
      transition: all 0.2s ease, background 0.3s ease, color 0.3s ease !important;
    }

    .form-control:focus { border-color: var(--pln-red) !important; box-shadow: 0 0 0 3px rgba(227,6,19,0.08) !important; }

    label {
      font-weight: 600 !important;
      font-size: 12px !important;
      color: var(--label-color) !important;
      text-transform: uppercase;
      letter-spacing: 0.4px;
      margin-bottom: 4px !important;
    }

    .select2-container .select2-selection {
      border-radius: 8px !important;
      border: 1.5px solid var(--select-border) !important;
      min-height: 32px;
      background: var(--bg-input) !important;
      transition: background 0.3s ease;
    }

    body.dark-mode .select2-container--default .select2-selection--single .select2-selection__rendered { color: var(--text-primary); }
    body.dark-mode .select2-container--default .select2-selection--single .select2-selection__arrow b { border-color: var(--text-secondary) transparent transparent; }
    body.dark-mode .select2-dropdown { background: #1e293b; border-color: rgba(255,255,255,0.1); }
    body.dark-mode .select2-results__option { color: var(--text-primary); }
    body.dark-mode .select2-results__option--highlighted { background: rgba(227,6,19,0.2) !important; color: #fff !important; }

    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 32px !important; font-size: 13px; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 32px !important; }

    /* ===== DATATABLES ===== */
    .dataTables_wrapper .dataTable th, .dataTables_wrapper .dataTable td { padding: 6px 10px; }
    table.dataTable tr { height: 30px; }
    .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter, .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_paginate { font-size: 12px; }
    div.dataTables_wrapper div.dataTables_length select { width: 50px !important; }
    #data_table th, td { white-space: nowrap; }
    #data_table tbody tr.selected { background-color: #607d8b !important; }
    #data_table tbody tr.selectedPosting { background-color: #28a745 !important; }
    #data-table tbody tr.selectedNonAktif { background-color: #e74c3c !important; }
    .table td button { margin: 1px !important; }

    /* ===== LOADING ===== */
    #modal-loading {
      position: fixed; top: 0; right: 0; bottom: 0; left: 0;
      text-align: center; padding-top: 100px;
      background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);
      color: white; display: none; z-index: 9999;
    }

    .overlay {
      display: flex; position: fixed; width: 100%; height: 100%;
      top: 0; left: 0; z-index: 9999;
      background: rgba(255,255,255,0.85) url("{{ asset('file/loading.gif') }}") center no-repeat;
      backdrop-filter: blur(2px);
    }

    body.loading { overflow: hidden; }
    body.loading .overlay { display: block; }

    /* ===== MODAL HEADER ===== */
    .modal-content { border: 1px solid var(--border-color); }
    .modal-header {
      background: var(--bg-card);
      color: var(--text-primary);
      border-bottom: 1px solid var(--border-color);
      padding: 12px 16px;
    }
    .modal-header .close {
      color: var(--text-primary);
      text-shadow: none;
      opacity: 0.7;
    }
    .modal-header .close:hover { opacity: 1; }
    .modal-header .modal-title { font-size: 15px; font-weight: 600; }
    body.dark-mode .modal-header { background: #1e293b; color: #e2e8f0; border-bottom-color: rgba(255,255,255,0.06); }
    body.dark-mode .modal-header .close { color: #e2e8f0; }
    body.dark-mode .modal-body,
    body.dark-mode .modal-footer { background: #1e293b; color: #e2e8f0; }
    body.dark-mode .modal-footer { border-top-color: rgba(255,255,255,0.06); }

    /* ===== MISC ===== */
    .container { width: 100% !important; }
    .select2-container { width: 100% !important; }
    .error { color: var(--pln-red); padding-left: 5px; font-size: 12px; }
    .modal-xl { width: 95%; }
    .sweet-overlay { z-index: 1999; }
    .text-xxs { font-size: 12px !important; }
    .card-body-filter { padding: 0.5rem 1rem; }
    .card-footer-filter { padding: .5rem 1.25rem; }
    .radio-group-status { display: flex; gap: 20px; align-items: center; }
    .radio-label { display: flex; align-items: center; gap: 8px; cursor: pointer; }
    .radio-label input[type="radio"] { transform: scale(1.2); }

    body.dark-mode .table { color: var(--text-primary); }
    body.dark-mode .table-striped tbody tr:nth-of-type(odd) { background: rgba(255,255,255,0.02); }
    body.dark-mode .table thead.table-light th,
    body.dark-mode .table thead.table-light td,
    body.dark-mode .table-light,
    body.dark-mode thead.table-light { background: #253244 !important; color: #e2e8f0 !important; }
    body.dark-mode .table-bordered th,
    body.dark-mode .table-bordered td { border-color: rgba(255,255,255,0.08) !important; }

    /* ===== DATATABLES DARK MODE ===== */
    body.dark-mode .dataTables_wrapper { color: var(--text-primary); }
    body.dark-mode table.dataTable { background: var(--bg-card); color: var(--text-primary); }
    body.dark-mode table.dataTable thead th,
    body.dark-mode table.dataTable thead td { background: var(--bg-card); color: var(--text-primary); border-bottom: 1px solid var(--border-color); }
    body.dark-mode table.dataTable tbody tr { background: var(--bg-card); }
    body.dark-mode table.dataTable tbody tr:hover { background: var(--bg-card-hover); }
    body.dark-mode table.dataTable tbody td { border-bottom: 1px solid var(--border-color); }
    body.dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button { color: var(--text-secondary) !important; }
    body.dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button:hover { color: var(--text-primary) !important; background: rgba(255,255,255,0.05); }
    body.dark-mode .dataTables_wrapper .dataTables_paginate .paginate_button.current { color: #fff !important; background: var(--pln-blue) !important; }
    body.dark-mode .dataTables_wrapper .dataTables_filter input { background: var(--bg-input); color: var(--text-primary); border: 1px solid var(--border-color); }
    body.dark-mode .dataTables_wrapper .dataTables_length select { background: var(--bg-input); color: var(--text-primary); border: 1px solid var(--border-color); }

    /* ===== JQXGRID / TREE GRID DARK MODE OVERRIDES ===== */
    body.dark-mode .jqx-widget,
    body.dark-mode .jqx-widget-content,
    body.dark-mode .jqx-widget-header { color: var(--text-primary) !important; }
    body.dark-mode .jqx-grid,
    body.dark-mode .jqx-grid-content,
    body.dark-mode .jqx-grid-header,
    body.dark-mode .jqx-grid-column-header { background: var(--bg-card) !important; border-color: var(--border-color) !important; }
    body.dark-mode .jqx-grid-cell,
    body.dark-mode .jqx-cell { background: var(--bg-card) !important; border-color: var(--border-color) !important; color: var(--text-primary) !important; }
    body.dark-mode .jqx-grid-cell-alt { background: rgba(255,255,255,0.02) !important; }
    body.dark-mode .jqx-grid-pager { background: var(--bg-card) !important; border-color: var(--border-color) !important; color: var(--text-primary) !important; }
    body.dark-mode .jqx-grid-pager .jqx-dropdownlist { background: var(--bg-card); color: var(--text-primary); }
    body.dark-mode .jqx-grid-pager .jqx-input { background: var(--bg-input); color: var(--text-primary); }
    body.dark-mode .jqx-window-header { background: var(--bg-card) !important; color: var(--text-primary) !important; }
    body.dark-mode .jqx-listbox,
    body.dark-mode .jqx-listitem { background: var(--bg-card) !important; color: var(--text-primary) !important; }
    body.dark-mode .jqx-treegrid,
    body.dark-mode .jqx-treegrid > div,
    body.dark-mode .jqx-treegrid-content { background: var(--bg-card) !important; }
    body.dark-mode .jqx-treegrid .jqx-widget-header { background: var(--bg-card) !important; border-color: var(--border-color) !important; }
    body.dark-mode .jqx-treegrid .jqx-widget-content { background: var(--bg-card) !important; border-color: var(--border-color) !important; color: var(--text-primary) !important; }
    body.dark-mode .jqx-treegrid .jqx-treegrid-hierarchy { background: transparent !important; }
    body.dark-mode .jqx-treegrid .jqx-expander { border-color: var(--text-muted); }
    body.dark-mode .jqx-treegrid .jqx-grid-cell { background: var(--bg-card) !important; border-color: var(--border-color) !important; color: var(--text-primary) !important; }
    body.dark-mode .jqx-grid-toolbar,
    body.dark-mode .jqx-widget-toolbar { background: #252526 !important; border-color: #35353A !important; }
    body.dark-mode .jqx-widget input,
    body.dark-mode .jqx-widget .jqx-input,
    body.dark-mode .jqx-widget .jqx-input-group { background: #3E3E42 !important; color: #ffffff !important; border-color: #35353A !important; }
    body.dark-mode .jqx-widget input::placeholder { color: rgba(255,255,255,0.4) !important; }
    body.dark-mode .jqx-widget .jqx-input-group { border-color: #35353A !important; }
    body.dark-mode .jqx-widget .jqx-input-group .jqx-input { background: #3E3E42 !important; }

    /* ===== DROPDOWN HOVER BRIDGE ===== */
    .dropdown-submenu { position: relative; }
    .dropdown-submenu > .dropdown-menu { position: absolute; left: 100%; top: -6px; margin-top: 0 !important; margin-left: 0; display: none; }
    .dropdown-submenu:hover > .dropdown-menu { display: block; }
    .dropdown-submenu::after {
      content: '';
      position: absolute;
      top: 0; right: -8px; bottom: 0; width: 12px;
      z-index: 1;
    }

    @media (max-width: 991.98px) {
      .navbar-collapse { background: var(--bg-card); border-radius: 12px; box-shadow: 0 12px 40px rgba(0,0,0,0.1); padding: 8px; margin-top: 8px; }
      .navbar-nav .nav-item > .nav-link { padding: 10px 14px !important; }
      .dropdown-submenu > .dropdown-menu { position: static; margin-left: 16px; box-shadow: none !important; }
    }
  </style>
</head>
<body class="layout-top-nav">
<div class="wrapper">

  <!-- ===== NAVBAR ===== -->
  <nav class="main-header navbar navbar-expand-lg navbar-white">
    <div class="container-fluid" style="padding:0 20px;">

      <a href="{{ url('/home') }}" class="navbar-brand">
        <img src="{{ asset('template_app') }}/dist/img/pln.png" alt="PLN" style="height:40px;">
        <div class="brand-text">
          <span class="title">OFFLINE DATABASE MCC</span>
          <span class="sub">UIP2B Jawa, Madura dan Bali</span>
        </div>
      </a>

      <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse order-2" id="navbarNav">
        <ul class="navbar-nav">
          @include('layouts.menu_horizontal', ['items' => $menu['data']])
        </ul>
      </div>

      <ul class="navbar-nav ml-auto order-3">
        <li class="nav-item">
          <button class="theme-toggle-btn" id="themeToggle" title="Toggle theme">
            <i class="fas fa-moon" id="themeIcon"></i>
          </button>
        </li>
        <li class="nav-item dropdown user-menu">
          <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" style="display:flex;align-items:center;gap:6px;">
            <img src="{{ asset('file') }}/profil_avatar.jpg" class="user-image img-circle elevation-1" alt="User">
            <span style="font-size:13px;font-weight:500;">{{ $user['username'] ?? '' }}</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-right" style="min-width:200px;">
            <li class="user-header">
              <img src="{{ asset('file') }}/profil_avatar.jpg" class="img-circle elevation-2" alt="User" style="width:64px;height:64px;">
              <p style="font-size:14px;font-weight:600;margin-top:8px;">{{ $user['username'] ?? '' }}</p>
            </li>
            <li class="user-footer" style="display:flex;gap:8px;justify-content:flex-end;">
              <a href="{{ url('/admin/akun') }}" class="btn btn-primary btn-sm">Akun</a>
              <a href="#" class="btn btn-secondary btn-sm btn-logout">Keluar</a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button"><i class="fas fa-expand-arrows-alt"></i></a>
        </li>
      </ul>

    </div>
  </nav>

  <!-- ===== CONTENT ===== -->
  <div class="content-wrapper">
    @yield('content')
  </div>

  <!-- ===== FOOTER ===== -->
  <footer class="main-footer">
    <div class="footer-inner">
      <span class="footer-copy">&copy; {{ date('Y') }} PT. PLN (Persero) UIP2B Jawa, Madura dan Bali</span>
      <span class="footer-version">Aplikasi Offline Database MCC v1.0</span>
    </div>
  </footer>

  <div id="modal-loading">
    <h3><i class="fa fa-spinner fa-spin fa-5x"></i></h3>
    <h3>Memproses...</h3>
  </div>

</div>

  <script src="{{ asset('template_app') }}/plugins/jquery/jquery.min.js"></script>

  <link rel="stylesheet" href="{{ asset('js/jqwidgets/styles/jqx.base.css') }}">
  <link rel="stylesheet" href="{{ asset('js/jqwidgets/styles/jqx.metro.css') }}" id="jqxThemeCss">
  <link rel="stylesheet" href="{{ asset('js/jqwidgets/styles/jqx.metrodark.css') }}" id="jqxThemeDarkCss" disabled>
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
  <script src="{{ asset('js/jqwidgets/jqxgrid.export.js') }}"></script>
  <script src="{{ asset('js/jqwidgets/jqxexport.js') }}"></script>
  <script src="{{ asset('js/jqwidgets/jqxgrid.sort.js') }}"></script>
  <script src="{{ asset('js') }}/xlsx.full.min.js"></script>
  <script src="{{ asset('js/grid-export.js') }}"></script>

  <script src="{{ asset('template_app') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('template_app') }}/dist/js/adminlte.min.js"></script>


  <script src="{{ asset('template_app') }}/plugins/sweetalert2/sweetalert2.all.js"></script>
  <script src="{{ asset('template_app') }}/plugins/sweetalert2/sweetalert2.all.min.js"></script>

  <link rel="stylesheet" href="{{ asset('template_app') }}/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="{{ asset('template_app') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <script src="{{ asset('template_app') }}/plugins/select2/js/select2.full.min.js"></script>
  <script src="{{ asset('template_app') }}/plugins/jquery-validation/jquery.validate.min.js"></script>

  <link rel="stylesheet" href="{{ asset('template_app') }}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">

  <script>
    var mainServerUrl = '{{ url("/") }}';
    var logoutUrl     = '{{ route("logout") }}';
    var currentPath = window.location.pathname;
    var segments = currentPath.split('/');
    var desiredPath = '/' + segments[1];

    /* set global jqx theme early for widgets created via AJAX */
    try { $.jqx = $.jqx || {}; $.jqx.theme = localStorage.getItem('app-theme') === 'dark' ? 'metrodark' : 'metro'; } catch(e) {}

    $(document).on({
      ajaxStart: function(){ $("body").addClass("loading"); },
      ajaxStop: function(){ $("body").removeClass("loading"); }
    });

    $(document).ready(function() {
      $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
      });

      /* ===== JQWIDGETS THEME HELPERS ===== */
      window.applyJqxTheme = function(theme) {
        /* set global default theme for future widgets */
        if (window.jqxWidgetsTheme && window.jqxWidgetsTheme.setTheme) {
          try { window.jqxWidgetsTheme.setTheme(theme); } catch(e) {}
        }
        try { $.jqx = $.jqx || {}; $.jqx.theme = theme; } catch(e) {}
        /* update existing widgets */
        var types = ['jqxGrid','jqxTreeGrid','jqxDataTable','jqxDropDownList','jqxListBox','jqxButton','jqxCheckBox','jqxMenu','jqxScrollBar','jqxLoader','jqxWindow','jqxInput','jqxNumberInput','jqxDateTimeInput','jqxComboBox','jqxTabs','jqxTree','jqxPanel','jqxSplitter'];
        $('.jqx-widget').each(function() {
          var $el = $(this);
          for (var i = 0; i < types.length; i++) {
            var t = types[i];
            if ($el.data(t)) {
              try { $el[t]({ theme: theme }); } catch(e) { try { $el[t]('theme', theme); } catch(e2) {} }
              break;
            }
          }
        });
        ['treeGrid','gensum','accessTreeGrid'].forEach(function(id) {
          var $tg = $('#' + id);
          if ($tg.length && $tg.data('jqxTreeGrid')) {
            try { $tg.jqxTreeGrid({ theme: theme }); } catch(e) {}
          }
        });
        /* brute-force style inputs inside jqx widgets */
        var isDark = theme === 'metrodark';
        $('.jqx-widget input, .jqx-widget .jqx-input, .jqx-widget .jqx-input-group').each(function() {
          var $el = $(this);
          if (isDark) {
            $el.css({ backgroundColor: '#3E3E42', color: '#ffffff', borderColor: '#35353A' });
          } else {
            $el.css({ backgroundColor: '', color: '', borderColor: '' });
          }
        });
      };

      /* ===== THEME STATE ===== */
      var savedTheme = localStorage.getItem('app-theme');
      if (savedTheme === 'dark') {
        $('body').addClass('dark-mode');
        $('#themeIcon').removeClass('fa-moon').addClass('fa-sun');
        $('#jqxThemeCss').prop('disabled', true);
        $('#jqxThemeDarkCss').prop('disabled', false);
      }

      /* ===== HIGHCHARTS THEME ===== */
      window.highchartsTheme = function(isDark) {
        if (typeof Highcharts === 'undefined') return;
        var darkOpts = {
          chart: { backgroundColor: '#1e293b', style: { color: '#e2e8f0' }, borderColor: 'rgba(255,255,255,0.06)' },
          title: { style: { color: '#e2e8f0' } },
          subtitle: { style: { color: '#94a3b8' } },
          xAxis: {
            labels: { style: { color: '#94a3b8' } },
            title: { style: { color: '#e2e8f0' } },
            lineColor: 'rgba(255,255,255,0.1)',
            tickColor: 'rgba(255,255,255,0.1)'
          },
          yAxis: {
            labels: { style: { color: '#94a3b8' } },
            title: { style: { color: '#e2e8f0' } },
            lineColor: 'rgba(255,255,255,0.1)',
            tickColor: 'rgba(255,255,255,0.1)',
            gridLineColor: 'rgba(255,255,255,0.05)'
          },
          legend: { itemStyle: { color: '#e2e8f0' }, itemHoverStyle: { color: '#fff' } },
          plotOptions: { series: { dataLabels: { color: '#e2e8f0' } } },
          colors: ['#4da8da','#e30613','#009639','#f39c12','#9b59b6','#1abc9c']
        };
        var lightOpts = {
          chart: { backgroundColor: '#ffffff', style: { color: '#333' }, borderColor: '#e0e0e0' },
          title: { style: { color: '#333' } },
          subtitle: { style: { color: '#666' } },
          legend: { itemStyle: { color: '#333' } },
          xAxis: { labels: { style: { color: '#666' } }, title: { style: { color: '#333' } }, lineColor: '#ccc', tickColor: '#ccc' },
          yAxis: { labels: { style: { color: '#666' } }, title: { style: { color: '#333' } }, lineColor: '#ccc', tickColor: '#ccc', gridLineColor: '#e6e6e6' },
          colors: ['#003366','#e30613','#009639','#f39c12','#9b59b6','#1abc9c']
        };
        var opts = isDark ? darkOpts : lightOpts;
        /* set global defaults for future charts */
        Highcharts.setOptions(opts);
        /* update existing charts */
        Highcharts.charts.forEach(function(ch) {
          if (ch) { try { ch.update(opts); } catch(e) {} }
        });
      };

      /* initial jqx + highcharts theme (after page scripts) */
      setTimeout(function() {
        if ($('body').hasClass('dark-mode')) {
          applyJqxTheme('metrodark');
        }
        highchartsTheme($('body').hasClass('dark-mode'));
      }, 0);

      /* theme toggle */
      $('#themeToggle').on('click', function() {
        $('body').toggleClass('dark-mode');
        var isDark = $('body').hasClass('dark-mode');
        $('#themeIcon').toggleClass('fa-moon', !isDark).toggleClass('fa-sun', isDark);
        localStorage.setItem('app-theme', isDark ? 'dark' : 'light');
        $('#jqxThemeCss').prop('disabled', isDark);
        $('#jqxThemeDarkCss').prop('disabled', !isDark);
        applyJqxTheme(isDark ? 'metrodark' : 'metro');
        highchartsTheme(isDark);
      });

      /* ===== DROPDOWN-SUBMENU HOVER FIX ===== */
      $('.dropdown-submenu').on('mouseenter', function() {
        clearTimeout($(this).data('timeout'));
        $(this).children('.dropdown-menu').show();
      }).on('mouseleave', function() {
        var $this = $(this);
        $this.data('timeout', setTimeout(function() {
          $this.children('.dropdown-menu').hide();
        }, 250));
      });

      /* ===== ACTIVE MENU ===== */
      $('.navbar-nav .nav-link').each(function() {
        var href = $(this).attr('href');
        if (href && href !== '#' && currentPath.indexOf(href) !== -1 && href !== '{{ url("/home") }}') {
          $(this).addClass('active');
        }
      });

      $('.dropdown-item').each(function() {
        var link = $(this).find('.dropdown-link');
        var href = link.attr('href');
        if (href && href !== '#' && currentPath.indexOf(href) !== -1) {
          $(this).addClass('active');
          $(this).closest('.dropdown-item').addClass('active');
        }
      });

      /* ===== LOGOUT ===== */
      $('.btn-logout').on('click', function (e) {
        e.preventDefault();
        Swal.fire({
          title: 'Warning!',
          text: "Anda yakin akan keluar?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#e30613',
          cancelButtonColor: '#6c757d',
          cancelButtonText: "Tidak",
          confirmButtonText: 'Ya, Keluar'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: logoutUrl,
              type: 'POST',
              headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
              data: {},
              beforeSend: function() { $('#modal-loading').fadeIn('fast'); },
              success: function() { location.href = mainServerUrl; },
              error: function() { location.reload(); },
              complete: function() { $('#modal-loading').fadeOut('fast'); }
            });
          }
        });
      });

      $('.dropdown-item a[href="#"]').on('click', function(e) { e.preventDefault(); });
    });
  </script>

  <script src="{{ asset('js') }}/global.js"></script>
  @stack('scripts')
</body>
</html>
