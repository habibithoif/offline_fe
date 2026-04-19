@extends('layouts.layouts_app')

@section('content')

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

<style>
    .loading {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }
    .indent-1 { padding-left: 20px !important; }
    .table > tbody > tr > td {
        vertical-align: middle;
    }
</style>

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1>{{ $data->page->title }}</h1>
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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group col-sm-2">
                        
                        @if($userAccessRole && in_array('create', $userAccessRole))
                            <button type="button" class="btn btn-block btn-outline-primary btn-sm pull-right" onclick="tambahData()">
                                Buat Baru
                            </button>
                        @endif
                        </div>
                        <hr>
                        <table id="setting-roles-table" class="display table table-striped table-bordered table-hover datatable compact" style="width:100%">
                            <thead>
                                <tr>
                                    <th data-orderable="false">#</th>
                                    <th data-orderable="true">Nama</th>
                                    <th data-orderable="true">Display</th>
                                    <th data-orderable="true">Deskripsi</th>
                                    <th data-orderable="true">Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</section>
<!-- /.content -->

<div class="modal fade" id="modal-data" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="form" id="form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="modal-data-title">Form Setting Roles</h3>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control form-control-sm text-sm" name="id" id="id">
                    <div class="row">
                        <div class="form-group col-md-12 form-hide">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control form-control-sm text-sm" name="name" id="name" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12 form-hide">
                            <label for="display_name">Display</label>
                            <input type="text" class="form-control form-control-sm text-sm" name="display_name" id="display_name" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12 form-hide">
                            <label for="description">Deskripsi</label>
                            <input type="text" class="form-control form-control-sm text-sm" name="description" id="description" required>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <label for="status" style="margin-left:10px;">Status</label>
                        <div class="form-group col-lg-6 form-hide radio-group-status">
                            <div class="col-sm-3">
                                <div class="form-group clearfix">
                                    <div class="icheck-primary d-inline">
                                    <input type="radio" name="status" id="status1" value="1" autocomplete="off">
                                        <label for="status1">
                                        Aktif
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group clearfix">
                                    <div class="icheck-danger d-inline">
                                    <input type="radio" name="status" id="status0" value="0"  autocomplete="off">
                                        <label for="status0">
                                        Nonaktif
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Keluar</button>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="modal-data-access" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="form" id="form-data-access">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="modal-data-access-title">Form Setting Akses</h3>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control form-control-sm text-sm" name="role_id" id="role_id">
                    <div class="col-sm-6">
                        <div class="form-group clearfix">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="checkbox_all">
                                <label for="checkbox_all">
                                Semua
                                </label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    @foreach($data->menu_akses ?? [] as $item)
                        <div class="col-sm-6">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="menu{{ $item['id'] }}" name="menu[]" value="{{ $item['id'] }}">
                                    <label for="menu{{ $item['id'] }}">
                                        {{ $item['display_name'] ?? 'Nama Tidak Tersedia' }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row d-flex flex-wrap" style="margin-left:25px;">
                            @foreach(json_decode($item['akses'] ?? '[]', true) as $value)
                                <div class="col-md-auto"> {{-- Gunakan "col-md-auto" agar tidak turun --}}
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="akses{{ $item['id'] }}_{{ $value }}" name="akses[{{ $item['id'] }}][]" value="{{ $value }}">
                                            <label for="akses{{ $item['id'] }}_{{ $value }}">
                                                {{ ucfirst($value) }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if(isset($item['children']) && is_array($item['children']))
                            @foreach($item['children'] as $child)
                            <div class="col-sm-6" style="margin-left:25px;">
                                <div class="form-group clearfix">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="menu{{ $child['id'] }}" name="menu[]" value="{{ $child['id'] }}" data-parent="{{ $item['id'] }}">
                                        <label for="menu{{ $child['id'] }}">
                                            {{ $child['display_name'] ?? 'Nama Tidak Tersedia' }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row d-flex flex-wrap" style="margin-left:50px;">
                                @foreach(json_decode($child['akses'] ?? '[]', true) as $value_child)
                                    <div class="col-md-auto"> {{-- Gunakan "col-md-auto" agar tidak turun --}}
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="akses{{ $child['id'] }}_{{ $value_child }}" name="akses[{{ $child['id'] }}][]" value="{{ $value_child }}" data-parent="{{ $item['id'] }}">
                                                <label for="akses{{ $child['id'] }}_{{ $value_child }}">
                                                    {{ ucfirst($value_child) }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @endforeach
                        @endif
                        <hr>
                    @endforeach

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Keluar</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('template_app') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('template_app') }}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('template_app') }}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('template_app') }}/plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.min.css">

    <script src="{{ asset('template_app') }}/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('template_app') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('template_app') }}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('template_app') }}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="{{ asset('template_app') }}/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('template_app') }}/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="{{ asset('template_app') }}/plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.min.js"></script>
    
    <link rel="stylesheet" href="{{ asset('template_app') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('template_app') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <script src="{{ asset('template_app') }}/plugins/select2/js/select2.full.min.js"></script>
    <script src="{{ asset('template_app') }}/plugins/jquery-validation/jquery.validate.min.js"></script>
    
    <script>
        var userAccessRole = @json($userAccessRole);
        var baseUrl       = mainServerUrl +  '/setting/roles';
        var tableData;

        function refresh(result) {
            alertSuccess(result.message);
            tableData.draw(false);
            $('#modal-data').modal('hide');
            $('#modal-data-access').modal('hide');
        }

        function refresh_access(result) {
            alertSuccess(result.message);
            $('#modal-data-access').modal('hide');
        }

        function detailData(id) {
            var dataSet = tableData.rows().data();
            var data = dataSet.filter(function(index) {
                return index.id == id;
            });

            urlAction = baseUrl+'/update';

            toggleForm('#form-data', true);
            resetForm('#form-data');

            $('#id').val(data[0].id);
            $('#name').val(data[0].name);
            $('#display_name').val(data[0].display_name);
            $('#description').val(data[0].description);
            $('input[name="status"][value="'+ data[0].status +'"]').prop('checked', true);
            
            $('#modal-data').modal('show');
        }

        function checkSelectedMenus(role_id) {
            $.ajax({
                url: mainServerUrl +  '/setting/roles/read_access/'+role_id,
                type: "GET",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    $.each(response.data, function(index, item) {
                        let menuCheckbox = document.getElementById(`menu${item.menu_id}`);
                        if (menuCheckbox) {
                            menuCheckbox.checked = true;
                        }

                        item.akses?.forEach(akses => {
                            let aksesCheckbox = document.getElementById(`akses${item.menu_id}_${akses}`);
                            if (aksesCheckbox) {
                                aksesCheckbox.checked = true;
                            }
                        });

                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }

        function AccessMenu(id) {
            var dataSet = tableData.rows().data();
            var data = dataSet.filter(function(index) {
                return index.id == id;
            });

            urlAction = baseUrl+'/update_access';

            toggleForm('#form-data-access', true);
            resetForm('#form-data-access');
            $('#form-data-access')[0].reset(); 
            $('#form-data-access').find("input[type=checkbox], input[type=radio]").prop("checked", false); // Reset checkbox & radio
            $('#form-data-access').find("select").val(null).trigger("change");

            $('#role_id').val(data[0].id);
            checkSelectedMenus(data[0].id);
            
            $('#modal-data-access-title').text('Form Setting Akses - '+data[0].display_name)
            $('#modal-data-access').modal('show');
        }

        function tambahData() {
            resetForm('#form-data');

            urlAction = baseUrl+'/store';
            $('#modal-data').modal('show');
        }
        

        function renderactive(data, type, row) {
            if (data == true || data == 1) {
                return '<small class="badge badge-success">Aktif</small>';
            }
            return '<small class="badge badge-danger">Nonaktif</small>';
        }

        function renderAction(data, type, row) {
            var button = '';

            if (userAccessRole && userAccessRole.includes("update")) {
                button = '<button type="button" class="btn btn-info btn-xs pull-right" onclick="detailData(\'' + row.id + '\')"><i class="fa fa-pen"></i> Edit</button>';
            }
            if (userAccessRole && userAccessRole.includes("akses_menu")) {
                button += '<button type="button" class="btn btn-warning btn-xs pull-right" onclick="AccessMenu(\'' + row.id + '\')"><i class="fa fa-bars"></i> Akses Menu</button>';
            }
            if (userAccessRole && userAccessRole.includes("delete")) {
                button += '<button type="button" class="btn btn-danger btn-xs pull-right" onclick="deleteData(\'' + row.id + '\', \'' + baseUrl + '/'+row.id+'\')"><i class="fa fa-trash"></i> Hapus</button>';
            }

            return button;
        }


        function setTable() {
            var colDef = [
                {data: 'id', searchable: true, width:'25px', render: renderNumRow },
                {data: 'name', searchable: true},
                {data: 'display_name', searchable: true},
                {data: 'description', searchable: true},
                {data: 'status',width: "30px", searchable: true, render: renderactive, createdCell: function (td) {
                    $(td).css('text-align', 'center');
                }},
                {data: 'id',width: "50px", render: renderAction },
            ];
            var reqData = null;

            var reqOrder = null;

            tableData = setDataTable('#setting-roles-table', baseUrl+'/read', colDef, reqData, reqOrder, null, null, false);

            tableData.on('init.dt', function() {
                tableData.columns().every(function(index) {
                    var column = this;
                    var index_total = (tableData.settings()[0].aoColumns.length)-1;
                    if (index !== 0 && index !== index_total) {
                        var isSearchable = tableData.settings()[0].aoColumns[index].bSearchable;
                
                        if (isSearchable) {  
                            $(column.header()).find('input').remove();

                            var input = $('<input type="text" class="form-control form-control-sm text-sm compact" placeholder="Search" style="width:100%;">')
                                .appendTo($(column.header()))
                                .on('keyup change', function() {
                                    if (column.search() !== this.value) {
                                        column.search(this.value).draw();
                                    }
                                });
                        }
                    }
                });
            });
        }

        $(document).ready(function() {
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })

            $('#checkbox_all').on('click', function() {
                if ($(this).prop('checked')) {
                    $('input[type=checkbox][name*=menu]').prop('checked', true);
                    $('input[type=checkbox][name*=akses]').prop('checked', true);
                } else {
                    $('input[type=checkbox][name*=menu]').prop('checked', false);
                    $('input[type=checkbox][name*=akses]').prop('checked', false);
                }
            })

            // Saat checkbox menu (induk) diubah
            $('input[name="menu[]"]').on('change', function () {
                let menuId = $(this).val();
                let isChecked = $(this).is(':checked');

                // Centang/hapus centang semua akses terkait (hak akses yang ada dalam menu)
                $(`input[name="akses[${menuId}][]"]`).prop('checked', isChecked);

                // Centang/hapus centang semua submenu (anak dari menu yang dicentang)
                //$(`input[name="menu[]"][data-parent="${menuId}"]`).prop('checked', isChecked);
                // Centang/hapus centang semua submenu (anak dari menu yang dicentang)
                let submenus = $(`input[name="menu[]"][data-parent="${menuId}"]`);
                submenus.prop('checked', isChecked);

                // Juga centang semua akses di dalam submenu
                submenus.each(function () {
                    let subMenuId = $(this).val();
                    $(`input[name="akses[${subMenuId}][]"]`).prop('checked', isChecked);
                });
            });

            // Saat submenu (anak) diubah
            $('input[name="menu[]"]').on('change', function () {
                let parentId = $(this).data('parent');
                
                if (parentId) {
                    // Cek apakah semua anak dari parent dicentang
                    let allChecked = 0 !=  $(`input[name="menu[]"][data-parent="${parentId}"]:checked`).length;

                    // Jika semua anak dicentang, maka parent dicentang. Jika tidak, parent tidak dicentang.
                    $(`input#menu${parentId}`).prop('checked', allChecked);
                }
            });

            $('input[name^="akses["]').on('change', function () {
                let menuId = $(this).attr('name').match(/\[([0-9]+)\]/)[1]; // Ambil menu_id dari nama input

                // Jika ada akses dicentang, menu induknya juga dicentang
                let anyChecked = $(`input[name="akses[${menuId}][]"]:checked`).length > 0;
                $(`input#menu${menuId}`).prop('checked', anyChecked);

                let parentId = $(this).data('parent');
                if (parentId) {
                    // Cek apakah semua anak dari parent dicentang
                    let allChecked = 0 !=  $(`input[name="menu[]"][data-parent="${parentId}"]:checked`).length;

                    // Jika semua anak dicentang, maka parent dicentang. Jika tidak, parent tidak dicentang.
                    $(`input#menu${parentId}`).prop('checked', allChecked);
                }
            });
            
            setTable();

            $('#form-data').validate({
                rules : {
                    
                },
                submitHandler:function (form) {
                    var reqData = new FormData(form);

                    ajaxData(urlAction, reqData, refresh, true);
                }
            });

            $('#form-data-access').validate({
                rules : {
                    
                },
                submitHandler:function (form) {
                    var reqData = new FormData(form);

                    ajaxData(urlAction, reqData, refresh_access, true);
                }
            });
        });
    </script>
@endpush
