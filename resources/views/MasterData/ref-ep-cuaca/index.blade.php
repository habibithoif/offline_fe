@extends('layouts.layouts_app')

@section('content')

<x-content-header 
    :title="$data->page['name']" 
    :breadcrumbs="[
        ['label' => 'Home', 'url' => '#'],
        ['label' => 'Master Data', 'url' => '#'],
        ['label' => 'Opsisdis', 'url' => '#'],
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

<div class="modal fade" id="modal-data" tabindex="-1" role="dialog" aria-labelledby="modalDataLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form id="form-data">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalDataLabel">Form Data</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
  
          <div class="modal-body">
            <input type="hidden" name="id" id="id">
  
            <div class="form-group">
              <label for="name">Nama</label>
              <input type="text" class="form-control" name="nama" id="nama" required>
            </div>
  
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Keluar</button>
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
        var cuacas = []; // Store cuacas data for easy access
        var actionMethod = 'POST';

        function refresh(result) {
            alertSuccess(result.message);
            $("#jqxGrid").jqxGrid('updatebounddata'); // This properly refreshes the grid data
            $('#modal-data').modal('hide');
        }

        function detailData(id) {
            var selectedData = cuacas.find(function(item) {
                return item.id == id;
            });

            if (selectedData) {
                urlAction = baseUrl + '/' + selectedData.id;
                actionMethod = 'PUT';

                toggleForm('#form-data', true);
                resetForm('#form-data');
                
                $('.is-invalid').removeClass('is-invalid');

                $('#modal-data-title').text('Edit ' + selectedData.nama);
                $('#id').val(selectedData.id);
                $('#nama').val(selectedData.nama);

                $('#modal-data').modal('show');
            } else {
                console.error('Cuaca item not found');
            }
        }

        function deleteData(id) {
            var selectedData = cuacas.find(function(item) {
                return item.id == id;
            });
            if (!selectedData) {
                alert('Cuaca not found');
                return;
            }

            Swal.fire({
                title: 'Warning!',
                text: `Are you sure you want to delete this role: ${selectedData.nama}?`,
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

            $('#modal-data-title').text('Tambah Cuaca Baru');
            urlAction = baseUrl;
            actionMethod = 'POST';
            toggleForm('#form-data', true);
            
            // Set default status to active
            $('#status1').prop('checked', true);
            
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
                    { name: 'id', type: 'string' },
                    { name: 'nama', type: 'string' },
                    { name: 'created_at', type: 'date' },
                    { name: 'updated_at', type: 'date' }
                ],
                url: '{{ route("cuacas.read") }}',
                cache: false,
                root: 'Rows',
                beforeprocessing: function(data) {
                    cuacas = data.data.Rows; // Store cuacas data
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
                height: 500,
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
                    { text: 'Name', datafield: 'nama' },
                    { 
                        text: 'Dibuat', 
                        datafield: 'created_at', 
                        cellsformat: 'dd-MM-yyyy'
                    },
                    { 
                        text: 'Diubah', 
                        datafield: 'updated_at', 
                        cellsformat: 'dd-MM-yyyy'
                    },
                    { 
                        text: 'Actions', 
                        datafield: 'id', 
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
                },
                submitHandler: function (form) {
                    var reqData = new FormData(form);
                    ajaxData(urlAction, reqData, refresh, true, true);
                }
            });
        });
</script>
@endpush