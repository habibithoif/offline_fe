@extends('layouts.layouts_app')

@section('content')

<x-content-header 
    :title="$data->page['name']" 
    :breadcrumbs="[
        ['label' => 'Home', 'url' => '#'],
        ['label' => $data->page['name'], 'url' => '#']
    ]" 
/>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm rounded">
                    <div class="card-header bg-info text-white">
                        <h3 class="card-title mb-0">{{ $data->page['name'] }}</h3>
                        <div class="card-tools">
                            <button id="refreshButton" class="btn btn-default btn-xs" title="Refresh">
                                <i class="fas fa-sync"></i>
                            </button>
                            <button id="listViewButton" class="btn btn-default btn-xs" title="List View">
                                <i class="fas fa-list"></i>
                            </button>
                            <button id="downloadButton" class="btn btn-default btn-xs" title="Download">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="form-group col-sm-2">
                            @if($data->accesses && in_array('create', $data->accesses))
                            <button type="button" class="btn btn-block btn-outline-primary btn-xs pull-right" onclick="tambahData()">
                                Buat Baru
                            </button>
                        @endif
                        </div>
                        <hr>
                        <div id="jqxGrid" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-data" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="form" id="form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="modal-data-title">Form Setting Pengguna</h3>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control form-control-sm text-sm" name="id" id="id">
                    <div class="row">
                        <div class="form-group col-md-12 form-hide">
                            <label for="nip">NIP</label>
                            <input type="text" class="form-control form-control-sm text-sm" name="nip" id="nip" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12 form-hide">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control form-control-sm text-sm" name="nama" id="nama" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12 form-hide">
                            <label for="username">Username</label>
                            <input type="text" class="form-control form-control-sm text-sm" name="username" id="username" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12 form-hide">
                            <label for="email">Email</label>
                            <input type="email" class="form-control form-control-sm text-sm" name="email" id="email" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12 form-hide">
                            <label for="no_telp">No Tlp</label>
                            <input type="text" class="form-control form-control-sm text-sm" name="no_telp" id="no_telp" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12 form-hide">
                            <label for="password">Password</label>
                            <input type="password" class="form-control form-control-sm text-sm" name="password" id="password">
                            <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti password</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12 form-hide">
                            <label for="role_id">Roles</label>
                            <select class="form-control text-sm select2" multiple="multiple" name="role_id[]" id="role_id" data-placeholder="Select Role" style="width: 100%;">
                                <option value="0" disabled>-- Pilih Roles --</option>
                                @foreach($data->role_data ?? [] as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['display_name'] }}</option>
                                @endforeach
                            </select>
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
                    <button type="submit" class="btn btn-xs btn-primary">Simpan</button>
                    <button type="button" class="btn btn-xs btn-default" data-dismiss="modal">Keluar</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
    <script>
        var userAccess = @json($data->accesses);
        var currentPath = window.location.pathname;
        var baseUrl = mainServerUrl + currentPath;
        var users = []; // Store users data for easy access
        var actionMethod = 'POST';

        function refresh(result) {
            alertSuccess(result.message);
            $("#jqxGrid").jqxGrid('updatebounddata'); // This properly refreshes the grid data
            $('#modal-data').modal('hide');
        }

        function detailData(id) {
            var userItem = users.find(function(item) {
                return item.id == id;
            });

            if (userItem) {
                urlAction = baseUrl + '/update';
                actionMethod = 'POST';

                toggleForm('#form-data', true);
                resetForm('#form-data');
                
                // Clear any previous validation errors
                $('.is-invalid').removeClass('is-invalid');

                $('#modal-data-title').text('Edit Pengguna');
                $('#id').val(userItem.id);
                $('#nip').val(userItem.nip);
                $('#nama').val(userItem.nama);
                $('#username').val(userItem.username);
                $('#email').val(userItem.email);
                $('#no_telp').val(userItem.no_telp);
                $('#password').val(''); // Clear password field for security
                
                // Set status radio button
                if (userItem.status == 1) {
                    $('#status1').prop('checked', true);
                } else {
                    $('#status0').prop('checked', true);
                }

                // Get roles for this user
                checkSelectedRole(userItem.id);
                
                $('#modal-data').modal('show');
            } else {
                console.error('User item not found');
            }
        }

        function checkSelectedRole(id) {
            $.ajax({
                url: mainServerUrl + '/setting/users/read_role/' + id,
                type: "GET",
                dataType: 'json',
                xhrFields: {
                    withCredentials: true
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                success: function(response) {
                    if (response.success) {
                        let roleIds = response.data.user_role_id.map(role => role.role_id);
                        $('#role_id').val(roleIds).trigger('change');
                    } else {
                        console.error("Error fetching roles:", response.message);
                    }
                },
                error: function(xhr) {
                    console.error("AJAX Error:", xhr.responseText);
                }
            });
        }

        function deleteData(id) {
            var userItem = users.find(function(item) {
                return item.id == id;
            });
            
            if (!userItem) {
                alert('User not found');
                return;
            }

            Swal.fire({
                title: 'Warning!',
                text: `Are you sure you want to delete user: ${userItem.nama}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: "No",
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: baseUrl + '/' + id,
                        type: "DELETE",
                        contentType: "application/json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            alertSuccess(response.message);
                            $("#jqxGrid").jqxGrid('updatebounddata'); // This properly refreshes the grid data
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                        }
                    });
                }
            });
        }

        function tambahData() {
            resetForm('#form-data');
            $('#form-data').validate().resetForm();
            
            // Clear any previous validation errors
            $('.is-invalid').removeClass('is-invalid');

            $('#modal-data-title').text('Tambah Pengguna Baru');
            urlAction = baseUrl + '/store';
            actionMethod = 'POST';
            toggleForm('#form-data', true);
            
            // Set default status to active
            $('#status1').prop('checked', true);
            
            // Mark password as required for new users
            $('#password').attr('required', true);
            
            $('#modal-data').modal('show');
        }
        
        loadData = function() {
            // CSRF Token setup for Laravel
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Create a data source
            var source = {
                datatype: "json",
                datafields: [
                    { name: 'id', type: 'number' },
                    { name: 'nama', type: 'string' },
                    { name: 'username', type: 'string' },
                    { name: 'email', type: 'string' },
                    { name: 'nip', type: 'string' },
                    { name: 'no_telp', type: 'string' },
                    { name: 'status', type: 'number' },
                    { name: 'user_roles', type: 'array' },
                    { name: 'created_at', type: 'date' },
                    { name: 'updated_at', type: 'date' }
                ],
                url: baseUrl + '/read',
                cache: false,
                root: 'Rows',
                beforeprocessing: function(data) {
                    users = data.data.Rows; // Store users data
                    source.totalrecords = data.data.TotalRows;
                },
                sort: function() {
                    $("#jqxGrid").jqxGrid('updatebounddata', 'sort');
                },
                filter: function() {
                    $("#jqxGrid").jqxGrid('updatebounddata', 'filter');
                }
            };

            var dataAdapter = new $.jqx.dataAdapter(source);

            // Initialize jQWidgets Grid with action column
            $("#jqxGrid").jqxGrid({
                width: '100%',
                source: dataAdapter,
                pageable: true,
                virtualmode: true,
                autorowheight: true,
                autoheight: false,
                showtoolbar: false,
                rendertoolbar: function (toolbar) {
                    var me = this;
                    var container = $("<div class='jqx-toolbar'></div>");
                    toolbar.append(container);
                    
                    if (userAccess && userAccess.includes('create')) {
                        container.append('<button id="addrowbutton" class="btn btn-primary btn-xs mr-2"><i class="fas fa-plus mr-1"></i> Add</button>');    
                    }

                    if (userAccess && userAccess.includes('update')) {
                        container.append('<button id="updaterowbutton" class="btn btn-info btn-xs mr-2"><i class="fas fa-edit mr-1"></i> Edit</button>');
                    }

                    if (userAccess && userAccess.includes('delete')) {
                        container.append('<button id="deleterowbutton" class="btn btn-danger btn-xs"><i class="fas fa-trash-alt mr-1"></i> Delete</button>');
                    }

                    // Update row
                    $("#updaterowbutton").on('click', function () {
                        var selectedrowindex = $("#jqxGrid").jqxGrid('getselectedrowindex');
                        if (selectedrowindex >= 0) {
                            var data = $("#jqxGrid").jqxGrid('getrowdata', selectedrowindex);
                            if (data && data.id) {
                                detailData(data.id);
                            }
                        }
                    });

                    // Delete row
                    $("#deleterowbutton").on('click', function () {
                        var selectedrowindex = $("#jqxGrid").jqxGrid('getselectedrowindex');
                        if (selectedrowindex >= 0) {
                            var data = $("#jqxGrid").jqxGrid('getrowdata', selectedrowindex);
                            if (data && data.id) {
                                deleteData(data.id);
                            }
                        }
                    });

                    // Add row
                    $("#addrowbutton").on('click', function () {
                        tambahData();
                    });
                },
                rendergridrows: function() {
                    return dataAdapter.records;
                },
                columns: [
                    { text: 'Nama', datafield: 'nama', width: '20%' },
                    { text: 'Username', datafield: 'username', width: '15%' },
                    { text: 'Email', datafield: 'email', width: '15%' },
                    { text: 'NIP', datafield: 'nip', width: '10%' },
                    { text: 'No Tlp', datafield: 'no_telp', width: '10%' },
                    { 
                        text: 'Roles', 
                        datafield: 'user_roles', 
                        width: '10%',
                        cellsalign: 'center',
                        cellsrenderer: function(row, columnfield, value, rowData) {
                            var rolesHtml = '';
                            if (value && value.length > 0) {
                                for (var i = 0; i < value.length; i++) {
                                    rolesHtml += '<span class="badge badge-info">' + value[i].role_id_app?.display_name + '</span> ';
                                }
                            } else {
                                rolesHtml = '<span class="badge badge-secondary">No Roles</span>';
                            }
                            return '<div style="padding: 5px; text-align: center;">' + rolesHtml + '</div>';
                        }
                    },
                    { 
                        text: 'Status', 
                        datafield: 'status', 
                        width: '10%',
                        cellsalign: 'center',
                        cellsrenderer: function(row, columnfield, value, rowData) {
                            var statusHtml = '';
                            if (value == true || value == 1) {
                                statusHtml = '<span class="badge badge-success">Aktif</span>';
                            } else {
                                statusHtml = '<span class="badge badge-danger">Nonaktif</span>';
                            }
                            return '<div style="padding: 5px; text-align: center;">' + statusHtml + '</div>';
                        }
                    },
                    { 
                        text: 'Actions', 
                        datafield: 'id', 
                        width: '10%',
                        cellsalign: 'center',
                        sortable: false,
                        filterable: false,
                        cellsrenderer: function(row, columnfield, value, rowData) {
                            // Create a container div for the buttons
                            var container = $('<div style="display: flex; justify-content: center; gap: 5px; margin-top: 3px;"></div>');
                            
                            // Add Edit button if user has update access
                            if (userAccess && userAccess.includes('update')) {
                                var editBtn = $(`<button class="btn btn-xs btn-info" onclick="detailData('${value}')"><i class="fas fa-edit"></i></button>`);
                                container.append(editBtn);
                            }
                            
                            // Add Delete button if user has delete access
                            if (userAccess && userAccess.includes('delete')) {
                                var deleteBtn = $(`<button class="btn btn-xs btn-danger" onclick="deleteData('${value}')"><i class="fas fa-trash-alt"></i></button>`);
                                container.append(deleteBtn);
                            }
                            
                            // If no buttons were added (no permissions), show a dash
                            if (container.children().length === 0) {
                                return '<div style="padding: 5px;">-</div>';
                            }
                            
                            return container[0].outerHTML;
                        }
                    }
                ],
                pagermode: 'default',
                pagesize: 20,
                pagesizeoptions: ['5', '10', '20', '50'],
                sortable: true,
                filterable: true,
                showfilterrow: true,
                filtermode: 'excel',
                theme: 'material'
            });

            $("#jqxGrid").on("bindingcomplete", function () {
                console.log("Grid is ready!");
            });
        };

        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4'
            });
            
            $('#role_id').on('change', function() {
                let selectedValues = $(this).val();
                
                if (selectedValues && selectedValues.includes("0")) {
                    selectedValues = selectedValues.filter(value => value !== "0");
                    $(this).val(selectedValues).trigger('change');
                }
            });

            loadData();

            $('#refreshButton').on('click', function() {
                $("#jqxGrid").jqxGrid('updatebounddata');
            });
            
            $('#downloadButton').on('click', function() {
                $("#jqxGrid").jqxGrid('exportdata', 'xlsx', 'TelemetryData');
            });
            
            $('#listViewButton').on('click', function() {
                console.log("List view toggle clicked");
            });
            
            // Form validation
            $('#form-data').validate({
                rules: {
                    nip: { required: true },
                    nama: { required: true },
                    username: { required: true },
                    email: { required: true, email: true },
                    no_telp: { required: true },
                    password: { required: function() {
                        return $('#id').val() === ''; // Required only for new users
                    }, minlength: 8},
                    status: { required: true }
                },
                messages: {
                    nip: { required: "NIP wajib diisi." },
                    nama: { required: "Nama wajib diisi." },
                    username: { required: "Username wajib diisi." },
                    email: { required: "Email wajib diisi.", email: "Format email tidak valid." },
                    no_telp: { required: "No Telepon wajib diisi." },
                    password: { required: "Password wajib diisi untuk pengguna baru." },
                    password: { minlength: "Password minimal 8 karakter." },
                    status: { required: "Status wajib dipilih." }
                },
                submitHandler: function (form) {
                    var reqData = new FormData(form);
                    ajaxData(urlAction, reqData, refresh, true, true);
                }
            });
        });
    </script>
@endpush