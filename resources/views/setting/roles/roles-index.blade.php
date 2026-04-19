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

<x-content-header 
    :title="$data->page->title" 
    :breadcrumbs="[
        ['label' => 'Home', 'url' => '#'],
        ['label' => $data->page->title, 'url' => '#']
    ]" 
/>

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
                        <div id="roleGrid" style="width: 100%;"></div>
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

@endsection

@push('scripts')
    <script>
        var userAccessRole = @json($userAccessRole);
        var currentPath = window.location.pathname;
        var baseUrl = mainServerUrl + currentPath;
        var roles = [];

        function refresh(result) {
            alertSuccess(result.message);
            loadRoles(); // Reload roles after adding or updating
            $('#modal-data').modal('hide');
        }

        function deleteData(id) {
            if (confirm('Are you sure you want to delete this role?')) {
                $.ajax({
                    url: baseUrl + '/delete/' + id,
                    type: "POST",
                    contentType: "application/json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alertSuccess(response.message);
                        loadRoles(); // Reload roles after delete
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }
        }

        function AccessMenu(id) {
            window.location.href = currentPath + "/detail/" + id;
        }

        function loadRoles() {
            $.ajax({
                url: baseUrl + '/read',
                type: "POST",
                contentType: "application/json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    roles = response.data;
                    
                    // Prepare the data for the grid
                    var data = [];
                    $.each(roles, function(index, role) {
                        data.push({
                            id: role.id,
                            name: role.name,
                            display_name: role.display_name,
                            description: role.description,
                            status: role.status == 1 ? 'Aktif' : 'Nonaktif',
                            status_value: role.status
                        });
                    });

                    // Create the source for the grid
                    var source = {
                        localdata: data,
                        datatype: "array",
                        datafields: [
                            { name: 'id', type: 'number' },
                            { name: 'name', type: 'string' },
                            { name: 'display_name', type: 'string' },
                            { name: 'description', type: 'string' },
                            { name: 'status', type: 'string' },
                            { name: 'status_value', type: 'number' }
                        ]
                    };

                    var dataAdapter = new $.jqx.dataAdapter(source);

                    // Initialize jqxGrid
                    $("#roleGrid").jqxGrid({
                        width: '100%',
                        source: dataAdapter,
                        theme: 'energyblue',
                        columnsresize: true,
                        autoheight: false,  // Added to make grid height dynamic
                        autorowheight: true,  // Added for better row height handling
                        pageable: true,  // Added for pagination if needed
                        pagesize: 10,  // Set your preferred page size
                        columns: [
                            { text: 'Name', datafield: 'name', width: '20%', minwidth: 150 },
                            { text: 'Display', datafield: 'display_name', width: '20%', minwidth: 150 },
                            { text: 'Deskripsi', datafield: 'description', width: '30%', minwidth: 200 },
                            { 
                                text: 'Status', 
                                datafield: 'status', 
                                width: '10%',
                                minwidth: 80,
                                cellsalign: 'center',
                                cellsrenderer: function(row, column, value, rowData) {
                                    if (rowData.status_value == 1) {
                                        return '<small class="badge badge-success">Aktif</small>';
                                    } else {
                                        return '<small class="badge badge-danger">Non</small>';
                                    }
                                }
                            },
                            {
                                text: 'Action',
                                datafield: 'action',
                                width: '20%',
                                minwidth: 150,
                                cellsalign: 'center',
                                cellsrenderer: function(row, column, value, rowData) {
                                    var buttons = '';
                                    
                                    if (userAccessRole && userAccessRole.includes("update")) {
                                        buttons += '<button class="btn btn-xs btn-info mr-1" onclick="detailData(' + rowData.id + ')">E</button>';
                                    }
                                    
                                    if (userAccessRole && userAccessRole.includes("akses_menu")) {
                                        buttons += '<button class="btn btn-xs btn-warning mr-1" onclick="AccessMenu(' + rowData.id + ')">A</button>';
                                    }
                                    
                                    if (userAccessRole && userAccessRole.includes("delete")) {
                                        buttons += '<button class="btn btn-xs btn-danger" onclick="deleteData(' + rowData.id + ')">D</button>';
                                    }
                                    
                                    return buttons;
                                }
                            }
                        ]
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }

        function detailData(id) {
            var roleItem = roles.find(function(item) {
                return item.id === id;
            });

            if (roleItem) {
                urlAction = baseUrl + '/update';

                toggleForm('#form-data', true);
                resetForm('#form-data');

                $('#id').val(roleItem.id);
                $('#name').val(roleItem.name);
                $('#display_name').val(roleItem.display_name);
                $('#description').val(roleItem.description);
                
                // Set status radio button
                if (roleItem.status == 1) {
                    $('#status1').prop('checked', true);
                } else {
                    $('#status0').prop('checked', true);
                }

                $('#modal-data').modal('show');
            } else {
                console.error('Role item not found');
            }
        }

        function tambahData() {
            resetForm('#form-data');
            $('#form-data').validate().resetForm();

            urlAction = baseUrl + '/store';
            toggleForm('#form-data', true);
            $('#modal-data').modal('show');
        }

        $(document).ready(function () {
            loadRoles(); // Initial load of roles

            $('#form-data').validate({
                rules: {
                    name: { required: true, minlength: 3 },
                    display_name: { required: true },
                    description: { required: true },
                    status: { required: true }
                },
                messages: {
                    name: { required: "Kolom Nama wajib diisi.", minlength: "Nama harus terdiri dari minimal 3 karakter." },
                    display_name: { required: "Kolom Display wajib diisi." },
                    description: { required: "Kolom Deskripsi wajib diisi." },
                    status: { required: "Kolom Status wajib dipilih." }
                },
                errorElement: "div",
                errorClass: "text-danger",
                submitHandler: function (form) {
                    var reqData = new FormData(form);
                    ajaxData(urlAction, reqData, refresh, true);
                }
            });
        });
    </script>
@endpush