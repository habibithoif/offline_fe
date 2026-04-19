@extends('layouts.layouts_app')

@section('content')

<x-content-header 
    :title="$data->page['name']" 
    :breadcrumbs="[
        ['label' => 'Home', 'url' => '#'],
        ['label' => 'Setting', 'url' => '#'],
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
        var roles = []; // Store roles data for easy access
        var actionMethod = 'POST';

        function refresh(result) {
            alertSuccess(result.message);
            $("#jqxGrid").jqxGrid('updatebounddata'); // This properly refreshes the grid data
            $('#modal-data').modal('hide');
        }

        function AccessMenu(id) {
            window.location.href = currentPath + "/detail/" + id;
        }

        function detailData(id) {
            var roleItem = roles.find(function(item) {
                return item.id == id;
            });

            if (roleItem) {
                urlAction = baseUrl + '/' + roleItem.id;
                actionMethod = 'PUT';

                toggleForm('#form-data', true);
                resetForm('#form-data');
                
                // Clear any previous validation errors
                $('.is-invalid').removeClass('is-invalid');

                $('#modal-data-title').text('Edit Role');
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

        function deleteData(id) {
            var roleItem = roles.find(function(item) {
                return item.id == id;
            });
            if (!roleItem) {
                alert('Role not found');
                return;
            }

            Swal.fire({
                title: 'Warning!',
                text: `Are you sure you want to delete this role: ${roleItem.name}?`,
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

        function confirmDelete() {
            if (deleteId) {
                var url = baseUrl + '/delete/' + deleteId;
                
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result) {
                        $('#modal-delete').modal('hide');
                        alertSuccess(result.message || "Data berhasil dihapus");
                        $("#jqxGrid").jqxGrid('updatebounddata'); // This properly refreshes the grid data
                    },
                    error: function(xhr) {
                        var errorMessage = "Terjadi kesalahan saat menghapus data";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        alertError(errorMessage);
                    }
                });
            }
        }

        function tambahData() {
            resetForm('#form-data');
            $('#form-data').validate().resetForm();
            
            // Clear any previous validation errors
            $('.is-invalid').removeClass('is-invalid');

            $('#modal-data-title').text('Tambah Role Baru');
            urlAction = baseUrl;
            actionMethod = 'POST';
            toggleForm('#form-data', true);
            
            // Set default status to active
            $('#status1').prop('checked', true);
            
            $('#modal-data').modal('show');
        }
        
        loadRoles = function() {
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
                    { name: 'name', type: 'string' },
                    { name: 'display_name', type: 'string' },
                    { name: 'description', type: 'string' },
                    { name: 'status', type: 'number' },
                    { name: 'created_at', type: 'date' },
                    { name: 'updated_at', type: 'date' }
                ],
                url: '{{ route("roles.read") }}',
                cache: false,
                root: 'Rows',
                beforeprocessing: function(data) {
                    roles = data.data.Rows; // Store roles data
                    source.totalrecords = data.data.TotalRows;
                },
                sort: function() {
                    $("#jqxGrid").jqxGrid('updatebounddata', 'sort');
                },
                filter: function() {
                    $("#jqxGrid").jqxGrid('updatebounddata', 'filter');
                },
                deleterow: function (rowid, commit) {
                    var data = $("#jqxGrid").jqxGrid('getrowdata', rowid);
                    if (data && data.id) {
                        deleteData(data.id); // Use your existing deleteData function
                        commit(true); // You can skip this if you're handling refresh in your own flow
                    } else {
                        commit(false);
                    }
                },
                updaterow: function (rowid, newdata, commit) {
                    var data = $("#jqxGrid").jqxGrid('getrowdata', rowid);
                    if (data && data.id) {
                        detailData(data.id); // Use your existing detailData function (opens modal)
                        commit(true); // Just visually commit — actual update is done via form
                    } else {
                        commit(false);
                    }
                },
            };

            var dataAdapter = new $.jqx.dataAdapter(source);

            // Initialize jQWidgets Grid with action column and fixed status display
            $("#jqxGrid").jqxGrid({
                width: '100%',
                source: dataAdapter,
                pageable: true,
                virtualmode: true,
                pageable: true,
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
                    { text: 'Name', datafield: 'name', width: '20%' },
                    { text: 'Display Name', datafield: 'display_name', width: '20%' },
                    { text: 'Deskripsi', datafield: 'description', width: '30%' },
                    { 
                        text: 'Status', 
                        datafield: 'status', 
                        width: '10%',
                        cellsalign: 'center',
                        cellsrenderer: function(row, columnfield, value, rowData) {
                            var statusHtml = '';
                            if (value == 1) {
                                statusHtml = '<span class="badge badge-success">Aktif</span>';
                            } else {
                                statusHtml = '<span class="badge badge-danger">Non-aktif</span>';
                            }
                            return '<div style="padding: 5px; text-align: center;">' + statusHtml + '</div>';
                        }
                    },
                    { 
                        text: 'Dibuat', 
                        datafield: 'created_at', 
                        width: '10%',
                        cellsformat: 'dd-MM-yyyy'
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

                            if (userAccess && userAccess.includes("akses_menu")) {
                                var accessButton = $(`<button class="btn btn-xs btn-warning" onclick="AccessMenu('${value}')"><i class="fas fa-key"></i></button>`);
                                container.append(accessButton);
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

                $("#jqxGrid").jqxGrid('theme', 'darkblue');
                $("#jqxGrid").jqxGrid('render');
            });
        };

        $(document).ready(function() {
            loadRoles();

            $('#refreshButton').on('click', function() {
                $("#jqxGrid").jqxGrid('updatebounddata');
            });

            $('#downloadButton').on('click', function() {
                $("#jqxGrid").jqxGrid('exportdata', 'xlsx', 'TelemetryData');
            });

            $('#listViewButton').on('click', function() {
                console.log("List view toggle clicked");
            });
            
            // Custom search functionality
            $('#searchButton').on('click', function() {
                var searchValue = $('#searchInput').val();
                if (searchValue) {
                    // Apply filters to multiple columns
                    var filtergroup = new $.jqx.filter();
                    
                    var nameFilter = filtergroup.createfilter('stringfilter', searchValue, 'contains');
                    filtergroup.addfilter(0, nameFilter);
                    
                    var displayNameFilter = filtergroup.createfilter('stringfilter', searchValue, 'contains');
                    filtergroup.addfilter(0, displayNameFilter, 'or');
                    
                    var descFilter = filtergroup.createfilter('stringfilter', searchValue, 'contains');
                    filtergroup.addfilter(0, descFilter, 'or');
                    
                    $("#jqxGrid").jqxGrid('addfilter', 'name', filtergroup);
                    $("#jqxGrid").jqxGrid('applyfilters');
                } else {
                    $("#jqxGrid").jqxGrid('clearfilters');
                }
            });
            
            // Search on Enter key
            $('#searchInput').on('keypress', function(e) {
                if (e.which === 13) {
                    $('#searchButton').click();
                }
            });
            
            // Delete confirmation
            $('#confirm-delete').on('click', function() {
                confirmDelete();
            });
            
            // Form validation
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
                submitHandler: function (form) {
                    var reqData = new FormData(form);
                    ajaxData(urlAction, reqData, refresh, true, true);
                }
            });
        });
</script>
@endpush