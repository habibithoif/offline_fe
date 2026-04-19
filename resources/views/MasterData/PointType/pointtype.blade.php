@extends('layouts.layouts_app')

@section('content')
<style>
    .jqx-tree-grid-collapse-button, .jqx-tree-grid-expand-button {
        margin-right: 8px;
        font-size: 16px;
    }
    .jqx-tree-grid-title {
        font-weight: bold;
    }
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    .custom-switch .custom-control-label::before {
        width: 2.25rem;
        height: 1.25rem;
    }
    .custom-switch .custom-control-label::after {
        width: calc(1.25rem - 4px);
        height: calc(1.25rem - 4px);
    }
</style>

@php
    $currentPath = request()->path();
    $userAccessRole = null;

    foreach ($menu['data'] as $item) {
        if (isset($item['children']) && is_array($item['children'])) {
            foreach ($item['children'] as $child) {
                if ($child['path'] === $currentPath) {
                    $userAccessRole = json_decode($child['akses'], true);
                    break 2; // Hentikan loop jika ketemu
                }
            }
        }
    }
@endphp

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Point Type Management</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">{{ $data->page->title }}</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-sitemap mr-2"></i>
                            {{ $data->page->title }}
                        </h3>
                        <div class="card-tools">
                            @if($userAccessRole && in_array('create', $userAccessRole))
                                <button type="button" class="btn btn-sm btn-primary" onclick="tambahData()">
                                    <i class="fas fa-plus"></i> Buat Baru
                                </button>
                            @endif
                            <button type="button" class="btn btn-sm btn-default ml-2" onclick="Reload()">
                                <i class="fas fa-sync-alt"></i> Reload
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="gensum"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Point Type States Section -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-list-alt mr-2"></i>
                            Pointtype State
                        </h3>
                        <div class="card-tools">
                            @if($userAccessRole && in_array('create', $userAccessRole))
                                <button type="button" class="btn btn-sm btn-info" onclick="tambahDataDetail()">
                                    <i class="fas fa-plus"></i> Buat Baru
                                </button>
                                <button type="button" class="btn btn-sm btn-info ml-2" onclick="CopyPointState()">
                                    <i class="fas fa-copy"></i> Copy Point State
                                </button>
                            @endif
                            <button type="button" class="btn btn-sm btn-default ml-2" onclick="ReloadDetail()">
                                <i class="fas fa-sync-alt"></i> Reload
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="gensum_detail"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Point Type Modal -->
<div class="modal fade" id="modal-data" role="dialog" aria-labelledby="pointTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title" id="modal-data-title">Form Point Type</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form" id="form-data">
                <div class="modal-body">
                    <input type="hidden" class="form-control" name="id" id="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nama Point</label>
                                <input type="text" class="form-control" name="name" id="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jenispoint">Jenis Point</label>
                                <select class="form-control select2" id="jenispoint" name="jenispoint" required>
                                    <option value="">Pilih Jenis Point</option>
                                    <option value="TELEMETERIGN">TELEMETERIGN</option>
                                    <option value="TELESIGNAL">TELESIGNAL</option>
                                    <option value="MASTER STATION">MASTER STATION</option>
                                    <option value="RTU">RTU</option>
                                    <!-- <option value="TELEKOMUNIKASI">TELEKOMUNIKASI</option> -->
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_induk_pointtype">Parent</label>
                                <select class="form-control select2" name="id_induk_pointtype" id="id_induk_pointtype">
                                    <option value="0">-- Pilih Parent --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <div class="d-flex align-items-center">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="status1" name="status" class="custom-control-input" value="1" checked>
                                        <label class="custom-control-label" for="status1">Aktif</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="status0" name="status" class="custom-control-input" value="0">
                                        <label class="custom-control-label" for="status0">Nonaktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Point Type State Modal -->
<div class="modal fade" id="modal_form_detail" role="dialog" aria-labelledby="pointTypeStateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title" id="title_detail">Form Pointtype State</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_detail">
                <div class="modal-body">
                    <input type="hidden" name="id_pointtype_state" id="id_pointtype_state">
                    <input type="hidden" name="id_pointtype" id="id_pointtype1">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="value">Value</label>
                                <input class="form-control" id="value" name="statekey" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quality_code">Quality Code</label>
                                <input name="quality_code" id="quality_code" type="text" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="state_label">State Label</label>
                                <input name="name" id="state_label" type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Normal/Up/Valid</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="validasi" name="valid" value="1">
                                    <label class="custom-control-label" for="validasi"></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="status2" name="status" value="1" checked>
                                    <label class="custom-control-label" for="status2"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                    <button type="submit" class="btn btn-info"><i class="fas fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Copy Point State Modal -->
<div class="modal fade" id="modal_form_copy" role="dialog" aria-labelledby="copyPointStateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title" id="title_copy">Copy Point State</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_copy">
                <div class="modal-body">
                    <input type="hidden" name="id_pointtypetarget" id="id_pointtype2">
                    <div class="form-group">
                        <label for="pkey">Pointtype Source</label>
                        <select name="id_pointtypesource" id="pkey" class="form-control select2" required>
                            <option value="">Select Pointtype</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                    <button type="submit" class="btn btn-info"><i class="fas fa-copy"></i> Copy</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<link rel="stylesheet" href="{{ asset('template_app') }}/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="{{ asset('template_app') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<script src="{{ asset('template_app') }}/plugins/select2/js/select2.full.min.js"></script> 
<script src="{{ asset('template_app') }}/plugins/jquery-validation/jquery.validate.min.js"></script>

    <script>
        // Your existing JavaScript code remains the same
        // Only the UI elements have been updated
        var userAccessRole = @json($userAccessRole);
        var baseUrl       = mainServerUrl +  '/masterdata/pointtype';
        var baseUrlDetail = mainServerUrl +  '/masterdata/pointtypestate';

        function refresh(result) {
            setgensum();
            alertSuccess(result.message);
            $('#modal-data').modal('hide');
        }

        function refresh1(result) {
            setgriddetail(result.data.id_pointtype);
            alertSuccess(result.message);
            $('#modal_form_detail').modal('hide');
        }

        function Reload() {
            $("#gensum").jqxTreeGrid('updateBoundData');
        }

        function ReloadDetail () {
            var selectedrow = $("#gensum").jqxTreeGrid('getSelection');
            if(selectedrow.length !== 0){
                setgriddetail(selectedrow[0].id);
            }else{
                setgriddetail(0);
            }
        }

        function get_datadropdownlist(selectedId = null, baseUrl, id, param=null){
            $.ajax({
                url: baseUrl+'/read',
                type: "POST",
                contentType: "application/json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data : param,
                success: function(response) {
                    let select = $('#'+id);
                    select.html('<option value="0">-- Pilih Parent --</option>'); // Reset options
                    
                    $.each(response.data, function(index, item) {
                        select.append(`<option value="${item.id}">${item.name}</option>`);
                    });

                    if (selectedId !== null) {
                        setTimeout(() => {
                            select.val(selectedId).trigger('change');
                        }, 100); // Small delay to ensure options are rendered
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }

        function renderactive(data, type, row) {
            if (row == true || row == 1) {
                return '<small class="badge badge-success">Aktif</small>';
            }
            return '<small class="badge badge-danger">Nonaktif</small>';
        }

        function detailData(id) {
            urlAction = baseUrl+'/update';
            toggleForm('#form-data', true);
            resetForm('#form-data');
            var dataRecord = $("#gensum").jqxTreeGrid('getSelection');
            $('#id').val(dataRecord[0].id);
            $('#name').val(dataRecord[0].name);
            $('#jenispoint').val(dataRecord[0].jenispoint);
            get_datadropdownlist(dataRecord[0].id_induk_pointtype, baseUrl, 'id_induk_pointtype');
            $('#id_induk_pointtype').val(dataRecord[0].id_induk_pointtype);
            $('#status').val(dataRecord[0].status);
            $('input[name="status"][value="'+ dataRecord[0].status +'"]').prop('checked', true);
            $('#modal-data').modal('show');
        }

        function detailDataDetail(id) {
            urlAction = baseUrlDetail+'/update';
            toggleForm('#form_detail', true);
            resetForm('#form_detail');
            var dataRecord = $("#gensum_detail").jqxGrid('getrowdata',id);
            $('#id_pointtype1').val(dataRecord.id_pointtype);
            $('#id_pointtype_state').val(dataRecord.id_pointtype_state);
            $('#value').val(dataRecord.statekey);
            $('#quality_code').val(dataRecord.quality_code);
            $('#state_label').val(dataRecord.name);
            if(dataRecord.valid = 0){
                $("#validasi").prop("checked", false);
            }else{
                $("#validasi").prop("checked", true);
            }
            if(dataRecord.status = 1){
                $("#status2").prop("checked", true);
            }else{
                $("#status2").prop("checked", false);
            }
            $('#modal_form_detail').modal('show');
        }

        function tambahData() {
            resetForm('#form-data');
            $('#form-data').validate().resetForm();
            get_datadropdownlist('', baseUrl, 'id_induk_pointtype');
            urlAction = baseUrl+'/store';
            $('#modal-data').modal('show');
        }

        function tambahDataDetail() {
            var selectedrow = $("#gensum").jqxTreeGrid('getSelection');
            if(selectedrow.length == 1){
                resetForm('#form_detail');
                $('#form_detail').validate().resetForm();
                $('#id_pointtype1').val(selectedrow[0].id);
                urlAction = baseUrlDetail+'/store';
                $('#modal_form_detail').modal('show');
                $('#title_detail').text('Tambah Data State'); // Set Title to Bootstrap modal title
            }else{
                alert('Silahkan Pilih Pointtype Terlebih Dahulu');
            }
        }

        function CopyPointState() {
            var selectedrow = $("#gensum").jqxTreeGrid('getSelection');
            if(selectedrow.length == 1){
                resetForm('#form_copy');
                $('#form_copy').validate().resetForm();
                get_datadropdownlist('', baseUrl, 'pkey');
                $('#id_pointtype2').val(selectedrow[0].id);
                urlAction = baseUrlDetail+'/copy';
                $('#modal_form_copy').modal('show'); // show bootstrap modal
                $('#title_copy').text('Pilih Pointtype yang akan di copy'); // Set Title to Bootstrap modal title
            }else{
                alert('Silahkan Pilih Pointtype Terlebih Dahulu');
            }
        }

        function renderAction(data, type, row) {
            var button = '';
            if (userAccessRole && userAccessRole.includes("update")) {
                button = '<button type="button" class="btn btn-info btn-xs pull-right" onclick="detailData(\'' + data + '\')"><i class="fa fa-pen"></i> Edit</button>';
            }
            if (userAccessRole && userAccessRole.includes("delete")) {
                button += '<button type="button" class="btn btn-danger btn-xs pull-right" onclick="deleteData(\'' + data + '\', \'' + baseUrl + '/'+ data +'\')"><i class="fa fa-trash"></i> Hapus</button>';
            }
            return button;
        }

        function renderActionDetail(row, column, value, defaultHtml, columnSettings, rowData) {
            var button = '';
            if (userAccessRole && userAccessRole.includes("update")) {
                button = '<button type="button" class="btn btn-info btn-xs pull-right" onclick="detailDataDetail(\'' + row + '\')"><i class="fa fa-pen"></i> Edit</button>';
            }
            if (userAccessRole && userAccessRole.includes("delete")) {
                button += '<button type="button" class="btn btn-danger btn-xs pull-right" onclick="deleteData(\'' + rowData.id_pointtype_state + '\', \'' + baseUrlDetail + '/'+ rowData.id_pointtype_state +'\',refresh1)"><i class="fa fa-trash"></i> Hapus</button>';
            }
            return button;
        }

        function setgensum(){
            $.ajax({
                url: baseUrl + '/read' ,  
                method: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function(data) {
                    $('#modal-loading').fadeIn('fast');
                },
                success: function (data) {
                    var source = {
                        localdata: data,  
                        datatype: "json",
                        id: 'id',
                        datafields: [
                            { name: 'id', type: 'string' },
                            { name: 'name', type: 'string' },
                            { name: 'jenispoint', type: 'number' },
                            { name: 'id_induk_pointtype', type: 'string' },
                            { name: 'status', type: 'string' }
                        ],
                        root: 'data',
                        hierarchy:
                        {
                            keyDataField: { name: 'id' },
                            parentDataField: { name: 'id_induk_pointtype' }
                        },
                    };

                    var dataAdapter = new $.jqx.dataAdapter(source);
                    
                    // Konfigurasi jqxGrid
                    $("#gensum").jqxTreeGrid({
                        width: "100%",
                        source: dataAdapter,
                        columnsResize: true,
                        filterable: true,
                        showStatusbar: true,
                        sortable: true,
                        autoRowHeight: false,
                        height: '400px',
                        editable: true,
                        editSettings: { saveOnPageChange: true, saveOnBlur: true, saveOnSelectionChange: false, cancelOnEsc: true, saveOnEnter: true, editOnDoubleClick: false, editOnF2: false },                   
                        columns: [
                            { text: 'Name', datafield: 'name', width: 250 },
                            { text: 'ID', datafield: 'id', width: 100 },
                            { text: 'Jenis Point', datafield: 'jenispoint', width: 150 },
                            { text: 'Status', datafield: 'status', width: 100, align: "center", cellsAlign: 'center',  cellsRenderer: renderactive },
                            { text: 'Action', cellsAlign: 'center', align: "center", columnType: 'none', editable: false, sortable: false, dataField: null, width: 200, cellsRenderer: renderAction }
                        ]
                    });
                },
                error: function (error) {
                    console.log('Error loading data', error);
                },
                complete: function(data) {
                    $('#modal-loading').fadeOut('fast');
                }
            });
        }

        function setgriddetail(id){
            $.ajax({
                url: baseUrlDetail+'/read',
                type: "POST",
                contentType: "application/json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: JSON.stringify({
                    id_pointtype : id
                }),
                beforeSend: function(data) {
                    $('#modal-loading').fadeIn('fast');
                },
                success: function (data) {
                    var source = {
                        localdata: data,  
                        datatype: "json",
                        id: 'id_pointtype_state',
                        datafields: [
                            { name: 'id_pointtype_state'},
                            { name: 'name', type: 'string'},
                            { name: 'quality_code', type: 'string'},
                            { name: 'statekey', type: 'string'},
                            { name: 'valid', type: 'bool'},
                            { name: 'status', type: 'bool'},
                            { name: 'id_pointtype', type: 'integer'}
                        ],
                        root: 'data'
                    };

                    var dataAdapter = new $.jqx.dataAdapter(source);
                    
                    // Konfigurasi jqxGrid
                    $("#gensum_detail").jqxGrid({
                        width: "100%",
                        source: dataAdapter,
                        pageSizeOptions: ['10', '25', '50', '100', '500', '1000'],
                        columnsResize: true,
                        // showtoolbar: false,
                        filterable: true,
                        // showfiltercolumnbackground:true,
                        showStatusbar: true,
                        sortable: true,
                        pageable: true,
                        pageSize: 20,
                        selectionmode: 'multiplecellsadvanced',
                        // theme: theme,
                        // rendergridrows: function() {
                        // 	return apgensum.records;
                        // },
                        // editable: true,
                        height: '400px',
                        columns: [
                            { text: 'ID', datafield: 'id_pointtype_state', width: 100 },
                            { text: 'Value', datafield: 'statekey', width: 250 },
                            { text: 'Quality Code', datafield: 'quality_code', width: 100 },
                            { text: 'State Label', datafield: 'name', width: 100 },
                            { text: 'Validasi', datafield: 'valid', width: 100 },
                            { text: 'Status', datafield: 'status', width: 100, align: "center", cellsAlign: 'center',  cellsRenderer: renderactive },
                            { text: 'Action', cellsAlign: 'center', align: "center", columnType: 'none', editable: false, sortable: false, dataField: null, width: 200, cellsRenderer: renderActionDetail }
                        ]
                    });
                },
                error: function (error) {
                    console.log('Error loading data', error);
                },
                complete: function(data) {
                    $('#modal-loading').fadeOut('fast');
                }
            });
        }

        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
            
            // Initialize the tree grid with expand/collapse buttons
            setgensum();
            setgriddetail(0);
            
            $('#form-data').validate({
                rules: {
                        jenispoint: {
                            required: true
                        },
                        name: {
                            required: true,
                            minlength: 3
                        }
                    },
                messages: {
                    name: {
                        required: "Kolom Nama wajib diisi.",
                        minlength: "Nama harus terdiri dari minimal 3 karakter."
                    },
                    jenispoint: {
                        required: "Kolom Jenis Point wajib diisi."
                    }
                },
                errorElement: "div",
                errorClass: "text-danger",
                submitHandler:function (form) {
                    var reqData = new FormData(form);
                    ajaxData(urlAction, reqData, refresh, true);
                }
            });

            $('#form_detail').validate({
                rules: {
                    id_pointstate: {
                        required: true
                    },
                    name: {
                        required: true,
                        minlength: 3
                    },
                    quality_code: {
                        required: true
                    }
                },
                messages: {
                    id_pointstate: {
                        required: "Kolom ID Point Type wajib diisi."
                    },
                    name: {
                        required: "Kolom State Key wajib diisi.",
                        minlength: "Nama harus terdiri dari minimal 3 karakter."
                    },
                    quality_code: {
                        required: "Kolom Quality Code wajib diisi."
                    }
                },
                errorElement: "div",
                errorClass: "text-danger",
                submitHandler:function (form) {
                    var reqData = new FormData(form);
                    ajaxData(urlAction, reqData, refresh1, true);
                }
            });

            $('#form_copy').validate({
                rules: {
                    id_pointstatesource: {
                        required: true
                    },
                    id_pointstatetarget: {
                        required: true
                    }
                },
                messages: {
                    id_pointstatesource: {
                        required: "Kolom ID Point Type State Source wajib diisi."
                    },
                    id_pointstatetarget: {
                        required: "Kolom ID Point Type State Target wajib diisi."
                    }
                },
                errorElement: "div",
                errorClass: "text-danger",
                submitHandler:function (form) {
                    var reqData = new FormData(form);
                    ajaxData(urlAction, reqData, refresh1, true);
                }
            });
           
            setgensum(); 
            setgriddetail(0);

            $('#gensum').on('rowSelect', function (event) {
                let dataRecord = $("#gensum").jqxTreeGrid('getSelection');
                let id_pointtype = dataRecord[0].id;
                $.ajax({
                    url: baseUrlDetail+'/read',
                    type: "POST",
                    contentType: "application/json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: JSON.stringify({
                        id_pointtype : id_pointtype
                    }),
                    success: function(response) {
                        // Clear grid sebelum memasukkan data baru
                        $("#gensum_detail").jqxGrid('clear');
                        data = response.data;
                        // Masukkan data detail ke dalam jqxGrid
                        for (var i = 0; i < data.length; i++) {
                            $("#gensum_detail").jqxGrid('addrow', null, {
                                id_pointtype: data[i].id_pointtype,
                                id_pointtype_state: data[i].id_pointtype_state,
                                statekey: data[i].statekey,
                                quality_code: data[i].quality_code,
                                name: data[i].name,
                                valid: data[i].valid,
                                status: data[i].status
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>
@endpush
