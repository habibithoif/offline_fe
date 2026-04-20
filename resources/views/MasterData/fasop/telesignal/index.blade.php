@extends('layouts.layouts_app')

@section('content')

<x-content-header 
    :title="$data->page['name']" 
    :breadcrumbs="[
        ['label' => 'Master Data', 'url' => '#'],
        ['label' => 'Fasop', 'url' => '#'],
        ['label' => $data->page['name'], 'url' => '#']
    ]" 
/>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <!-- Filter Card -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="card card-outline card-primary">
                            <div class="card-header card-footer-filter">
                                <h3 class="card-title">
                                    <i class="fas fa-filter mr-2"></i>FILTER
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body card-body-filter">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Region</label>
                                            <select class="form-control form-control-sm select2" style="width: 100%;" data-placeholder="--Pilih Region--" id="filterRegion">
                                                <option value=""></option>
                                                <!-- <option value="all">-- All Region --</option> -->
                                                @foreach ($data->ref_region_filter as $item)
                                                    <option value="{{ $item['id_region'] }}">{{ $item['nama_region'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>B1 Name</label>
                                            <select class="form-control form-control-sm select2" id="filterLokasi">
                                                <option value="">-- All B1 --</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>B2 Name</label>
                                            <div class="input-group input-group-sm">
                                                <select class="form-control form-control-sm select2" id="filterTegangan">
                                                    <option value="">-- All B2 --</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>B3 Name</label>
                                            <div class="input-group input-group-sm">
                                                <select class="form-control form-control-sm select2" id="filterBay">
                                                    <option value="">-- All B3 --</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Element</label>
                                            <div class="input-group input-group-sm">
                                                <select class="form-control form-control-sm select2" id="filterElement">
                                                    <option value="">-- All Element --</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Hitung Kinerja</label>
                                            <div class="input-group input-group-sm">
                                                <select class="form-control form-control-sm select2" data-placeholder="--Pilih Hitung Kinerja--" id="filterHitungKinerja">
                                                    <option value="">--Pilih Hitung Kinerja--</option>
                                                    <option value="1">Ya</option>
                                                    <option value="0">Tidak</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer card-footer-filter">
                                <button id="applyFilters" class="btn btn-primary btn-sm float-right">
                                    <i class="fas fa-search mr-1"></i> Terapkan
                                </button>
                                <button id="resetFilters" class="btn btn-secondary btn-sm float-right mr-2">
                                    <i class="fas fa-undo mr-1"></i> Reset
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm rounded">
                    <div class="card-header bg-info text-white">
                        <h3 class="card-title mb-0">{{ $data->page['name'] }}</h3>
                        <div class="card-tools">
                            <button id="refreshButton" class="btn btn-default btn-xs" title="Refresh">
                                <i class="fas fa-sync"></i> Refresh
                            </button>
                            <!-- <button id="listViewButton" class="btn btn-default btn-xs" title="List View">
                                <i class="fas fa-list"></i>
                            </button> -->
                            <button id="downloadButton" class="btn btn-default btn-xs" title="Download">
                                <i class="fas fa-download"></i> Download
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
          <div class="modal-header" style="background-color: #17a2b8!important; color: white;">
            <h5 class="modal-data-title" id="modalDataLabel">Form Data</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
  
          <div class="modal-body">
            <input type="hidden" name="id" id="id">
            <div class="row">
                <div class="form-group col-6">
                    <label for="id_region">Region</label>
                    <select class="form-control form-control-sm select2" style="width: 100%;" data-placeholder="--Pilih Region--" name="id_region" id="id_region" required>
                        <option value=""></option>
                        @foreach ($data->ref_region as $item)
                            <option value="{{ $item['id_region'] }}">{{ $item['nama'] }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- <div class="form-group col-6">
                    <label for="point_number">Point Number</label>
                    <input type="number" class="form-control form-control-sm" name="point_number" id="point_number" required>
                </div> 
                <div class="form-group col-6">
                    <label for="point_type">Kelompok</label>
                    <input type="text" class="form-control form-control-sm" name="point_type" id="point_type" value="{{ $data->pointtype_name }}" required readonly>
                </div>
                <div class="form-group col-6">
                    <label for="point_type_id">Jenis Point</label>
                    <select class="form-control form-control-sm select2" style="width: 100%;" data-placeholder="--Pilih Jenis Point--" name="point_type_id" id="point_type_id" required>
                        <option value=""></option>
                        @foreach ($data->point_type as $item)
                            <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                        @endforeach
                    </select>
                </div>-->
                <div class="form-group col-6">
                    <label for="path1name">B1 Name</label>
                    <input type="text" class="form-control form-control-sm" name="path1name" id="path1name" required>
                </div>
                <div class="form-group col-6">
                    <label for="path2name">B2 Name</label>
                    <input type="text" class="form-control form-control-sm" name="path2name" id="path2name" required>
                </div>
                <div class="form-group col-6">
                    <label for="path3name">B3 Name</label>
                    <input type="text" class="form-control form-control-sm" name="path3name" id="path3name" required>
                </div>
                <div class="form-group col-6">
                    <label for="path4name">Element Name</label>
                    <input type="text" class="form-control form-control-sm" name="path4name" id="path4name" required>
                </div>
                <div class="form-group col-6">
                    <label for="path5name">Status Info</label>
                    <input type="text" class="form-control form-control-sm" name="path5name" id="path5name">
                </div>
                <div class="form-group col-12 d-flex align-items-center">

                    <!-- Hitung Kinerja -->
                    <div class="d-flex align-items-center mr-5" style="margin-left: 20px;">
                        <label for="hitung_kinerja" class="mb-0 mr-2" style="font-size: 0.85rem;">
                            Hitung Kinerja
                        </label>
                        <input type="checkbox" class="form-check-input" id="hitung_kinerja" name="hitung_kinerja" value="0"
                            style="transform: scale(0.85); margin-top: 2px;">
                    </div>

                    <!-- Status -->
                    <!-- <div class="d-flex align-items-center" style="margin-left: 80px;">
                        <label for="status" class="mb-0 mr-2" style="font-size: 0.85rem;">
                            Status
                        </label>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio"
                                name="status" id="status_aktif" value="1" required
                                style="transform: scale(0.85); margin-top: 2px;">
                            <label class="form-check-label" for="status_aktif" style="font-size: 0.85rem;">
                                Aktif
                            </label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio"
                                name="status" id="status_nonaktif" value="0"
                                style="transform: scale(0.85); margin-top: 2px;">
                            <label class="form-check-label" for="status_nonaktif" style="font-size: 0.85rem;">
                                Non Aktif
                            </label>
                        </div>
                    </div> -->

                </div>



            </div>
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
        var appSettings = []; // Store appSettings data for easy access
        var actionMethod = 'POST';

        function refresh(result) {
            alertSuccess(result.message);
            $("#jqxGrid").jqxGrid('updatebounddata'); // This properly refreshes the grid data
            $('#modal-data').modal('hide');
        }

        function detailData(id) {
            var selectedData = appSettings.find(function(item) {
                return item.id == id;
            });

            if (selectedData) {
                
                toggleForm('#form-data', true);
                resetForm('#form-data');
                $('#form-data').validate().resetForm();
                
                urlAction = baseUrl + '/update';
                actionMethod = 'PUT';
                // console.log('URL Action:', urlAction);
                // console.log('Action Method:', actionMethod);
                // console.log(selectedData);
                
                $('.is-invalid').removeClass('is-invalid');

                $('.modal-data-title').text('Edit ');

                $('#id').val(selectedData.id);
                $('#id_region').val(selectedData.region).trigger('change').prop('disabled', true);
                // $('#point_number').val(selectedData.point_number).prop('disabled', true);
                // $('#point_type_id').val(selectedData.point_type_id).trigger('change').prop('disabled', true);
                $('#path1name').val(selectedData.b1_name).prop('disabled', true);
                $('#path2name').val(selectedData.b2_name).prop('disabled', true);
                $('#path3name').val(selectedData.b3_name).prop('disabled', true);
                $('#path4name').val(selectedData.el_name).prop('disabled', true);
                $('#path5name').val(selectedData.info_name).prop('disabled', true);
                $('#hitung_kinerja').prop('checked', selectedData.kinerja == 1);
                // $('input[name="status"][value="'+selectedData.status+'"]').prop('checked', true);


                $('#modal-data').modal('show');
            } else {
                console.error('Item tidak ditemukan untuk ID:', id);
            }
        }

        function deleteData(id) {
            var selectedData = appSettings.find(function(item) {
                return item.id == id;
            });
            if (!selectedData) {
                alert('Data tidak ditemukan');
                return;
            }

            Swal.fire({
                title: 'Warning!',
                text: `Apakah Anda yakin ingin menghapus data ini ?`,
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

            $('#modal-data-title').text('Tambah Fasop-Telesignal Baru');
            urlAction = baseUrl+'/store';
            actionMethod = 'POST';
            toggleForm('#form-data', true);

            // Enable all fields
            $('#id_region').prop('disabled', false);
            // $('#point_number').prop('disabled', false);
            // $('#point_type_id').prop('disabled', false);
            $('#path1name').prop('disabled', false);
            $('#path2name').prop('disabled', false);
            $('#path3name').prop('disabled', false);
            $('#path4name').prop('disabled', false);
            $('#path5name').prop('disabled', false);
            $('#hitung_kinerja').prop('disabled', false);
            // $('input[name="status"]').prop('disabled', false);
            
            // Set default status to active
            // $('#status1').prop('checked', true);
            
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
                    { name: 'b1_name', type: 'string' },
                    { name: 'b2_name', type: 'string' },
                    { name: 'b3_name', type: 'string' },
                    { name: 'el_name', type: 'string' },
                    { name: 'info_name', type: 'string' },
                    { name: 'b1_text', type: 'string' },
                    { name: 'b2_text', type: 'string' },
                    { name: 'b3_text', type: 'string' },
                    { name: 'el_text', type: 'string' },
                    { name: 'info_text', type: 'string' },
                    { name: 'region', type: 'string' },
                    { name: 'nama_region', type: 'string' },
                    { name: 'kinerja', type: 'bool' },
                    { name: 'path5name', type: 'string' },
                    { name: 'status', type: 'integer' },
                ],
                url: '{{ route("masterdata-fasop-telesignal.read") }}',
                cache: false,
                root: 'data',
                beforeprocessing: function(data) {
                    // appSettings = data.data.Rows; // Store appSettings data
                    // source.totalrecords = data.data.TotalRows;
                   if (data && data.data) {
                        appSettings = data.data;
                        source.totalrecords = data.data.TotalRows;
                    } else {
                        console.error('Invalid data structure:', data);
                        appSettings = [];
                        source.totalrecords = 0;
                    }
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
                autorowheight: true,
                autoheight: false,
                showtoolbar: false,
                editable: false,
                columnsresize: true,
                columnsautoresize: false,
                scrollmode: 'logical',
                scrollbarsize: 10,
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
                },
                rendergridrows: function() {
                    return dataAdapter.records;
                },
                columns: [
                    {
                        text: 'No', // Column header
                        datafield: '', // No datafield needed for row numbers
                        width: 50, // Adjust the width as needed
                        cellsalign: 'center',
                        align: 'center',
                        editable: false,
                        sortable: false,
                        filterable: false,
                        cellsrenderer: function (row, columnfield, value, defaulthtml, columnproperties) {
                            return `<div style="text-align: center; margin-top: 5px;">${row + 1}</div>`;
                        }
                    },
                    { text: 'Region', datafield: 'region', editable: false, width: 150, },
                    { text: 'B1 Name', datafield: 'b1_name', editable: false, width: 200},
                    { text: 'B2 Name', datafield: 'b2_name', editable: false, width: 200},
                    { text: 'B3 Name', datafield: 'b3_name', editable: false, width: 200},
                    { text: 'Element Name', datafield: 'el_name', editable: false, width: 200},
                    { text: 'Info Name', datafield: 'info_name', editable: false, width: 200},
                    { text: 'B1 Text', datafield: 'b1_text', editable: false, width: 200},
                    { text: 'B2 Text', datafield: 'b2_text', editable: false, width: 200},
                    { text: 'B3 Text', datafield: 'b3_text', editable: false, width: 200},
                    { text: 'Element Text', datafield: 'el_text', editable: false, width: 200},
                    { text: 'Info Text', datafield: 'info_text', editable: false, width: 200},
                    {
                        text: 'Hitung Kinerja',
                        datafield: 'kinerja',
                        columntype: 'checkbox',
                        width: 100,
                        editable: false,
                        filtertype: 'bool',
                        cellendedit: function (row, datafield, columntype, oldvalue, newvalue) {
                            // newvalue = true/false
                            var nilai = newvalue ? 1 : 0;
                        }
                    },
                    // { text: 'Status', datafield: 'status', editable: false,
                    //     width: 80,
                    //     cellsrenderer: function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
                    //         var statusHtml = '';
                    //         if (value == true || value == 1) {
                    //             statusHtml = '<span class="badge badge-success">Aktif</span>';
                    //         } else {
                    //             statusHtml = '<span class="badge badge-danger">Nonaktif</span>';
                    //         }
                    //         return '<div style="padding: 5px; text-align: center;">' + statusHtml + '</div>';
                    //     }
                    // },
                    { 
                        text: 'Actions', 
                        datafield: 'id', 
                        width: '10%',
                        cellsalign: 'center',
                        sortable: false,
                        filterable: false, editable: false,pinned: true,
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
                // theme: 'material'
            });

            $("#jqxGrid").on("bindingcomplete", function () {
                console.log("Grid is ready!");

                // $("#jqxGrid").jqxGrid('theme', 'darkblue');
                $("#jqxGrid").jqxGrid('render');
            });
        };

        $(document).ready(function() {

            // Initialize select2 controls
            $('.select2').select2();

            loadData();

            // Rest of your select2 initialization code...
            $('#filterLokasi').select2({
                ajax: {
                    url: '{{ route("cpoint.findValueBy") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            keyword: params.term, 
                            page: params.page || 1,
                            field: 'path1name',
                            point_type: '{{ $data->pointtype_name }}'
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        const response = data.data.data;
                        return {
                            results: response.map(function(item) {
                                return {
                                    id: item, 
                                    text: item 
                                };
                            }),
                            pagination: {
                                more: (params.page * 10) < data.total  
                            }
                        };
                    },
                    cache: true
                },
                allowClear: true,
                placeholder: '--Pilih B1 Name--',
            });

            $('#filterTegangan').select2({
                ajax: {
                    url: '{{ route("cpoint.findValueBy") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            keyword: params.term,  
                            page: params.page || 1,
                            field: 'path2name',
                            point_type: '{{ $data->pointtype_name }}'
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        const response = data.data.data;
                        return {
                            results: response.map(function(item) {
                                return {
                                    id: item, 
                                    text: item 
                                };
                            }),
                            pagination: {
                                more: (params.page * 10) < data.total  
                            }
                        };
                    },
                    cache: true
                },
                allowClear: true,
                placeholder: '--Pilih B2 Name--'
            });

            $('#filterBay').select2({
                ajax: {
                    url: '{{ route("cpoint.findValueBy") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            keyword: params.term,  
                            page: params.page || 1,
                            field: 'path3name',
                            pointtype_name: '{{ $data->pointtype_name }}'
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        const response = data.data.data;
                        return {
                            results: response.map(function(item) {
                                return {
                                    id: item, 
                                    text: item 
                                };
                            }),
                            pagination: {
                                more: (params.page * 10) < data.total  
                            }
                        };
                    },
                    cache: true
                },
                allowClear: true,
                placeholder: '--Pilih B3 Name--'
            });

            $('#filterElement').select2({
                ajax: {
                    url: '{{ route("cpoint.findValueBy") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            keyword: params.term,  
                            page: params.page || 1,
                            field: 'path4name' ,
                            pointtype_name: '{{ $data->pointtype_name }}'
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        const response = data.data.data;
                        return {
                            results: response.map(function(item) {
                                return {
                                    id: item, 
                                    text: item 
                                };
                            }),
                            pagination: {
                                more: (params.page * 10) < data.total  
                            }
                        };
                    },
                    cache: true
                },
                allowClear: true,
                placeholder: '--Pilih Element--'
            });

            $('#refreshButton').on('click', function() {
                $("#jqxGrid").jqxGrid('updatebounddata');
            });

            $('#downloadButton').on('click', async function() {
                // Ambil semua data dari jqxGrid
                var rows = $("#jqxGrid").jqxGrid('getrows');

                // Header custom
                var headers = ["Regional",  "Kelompok", "B1 Name","B2 Name","B3 Name","Element","path5name","Hitung Kinerja","Status"];

                // Buat workbook dan worksheet
                var workbook = new ExcelJS.Workbook();
                var worksheet = workbook.addWorksheet('MasterData');

                // Tambahkan header
                var headerRow = worksheet.addRow(headers);

                // Style header
                headerRow.eachCell(function(cell, colNumber) {
                    cell.fill = {
                        type: 'pattern',
                        pattern: 'solid',
                        fgColor: { argb: 'C5D9F1' } // biru
                    };
                    cell.font = {
                        bold: true,
                        color: { argb: '000000' } // hitam
                    };
                    cell.alignment = { horizontal: 'center', vertical: 'middle' };
                    cell.border = {
                        top: {style:'thin'},
                        left: {style:'thin'},
                        bottom: {style:'thin'},
                        right: {style:'thin'}
                    };
                });

                // Tambahkan data
                rows.forEach(row => {
                    worksheet.addRow([
                        row.nama_region,
                        // row.point_number,
                        row.point_type_id,
                        row.path1name,
                        row.path2name,
                        row.path3name,
                        row.path4name,
                        row.path5name,
                        row.hitung_kinerja == 1 ? "Ya" : "Tidak",
                        // row.status == 1 ? "Aktif" : "Non-aktif"
                    ]);
                });

                // Atur lebar kolom otomatis
                worksheet.columns.forEach(column => {
                    let maxLength = 0;
                    column.eachCell({ includeEmpty: true }, cell => {
                        const columnLength = cell.value ? cell.value.toString().length : 10;
                        if (columnLength > maxLength) maxLength = columnLength;
                    });
                    column.width = maxLength + 2;
                });

                // Export ke file XLSX
                const buffer = await workbook.xlsx.writeBuffer();
                const blob = new Blob([buffer], { type: 'application/octet-stream' });
                saveAs(blob, 'MasterData-fasop-telesignal.xlsx');
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

            $('#applyFilters').on('click', function () {
                // Collect filter values
                var filterRegion = $('#filterRegion').val();
                var filterLokasi = $('#filterLokasi').val();
                var filterTegangan = $('#filterTegangan').val();
                var filterBay = $('#filterBay').val();
                var filterElement = $('#filterElement').val();
                var filterHitungKinerja = $('#filterHitungKinerja').val(); // Get Hitung Kinerja filter value

                // Log the filters for debugging
                // console.log('Filters:', {
                //     region: filterRegion,
                //     lokasi: filterLokasi,
                //     tegangan: filterTegangan,
                //     bay: filterBay,
                //     element: filterElement,
                //     hitung_kinerja: filterHitungKinerja
                // });

                // Apply filter for Region
                if (filterRegion) {
                    var regionFilterGroup = new $.jqx.filter();
                    var regionFilter = regionFilterGroup.createfilter('stringfilter', filterRegion, 'EQUAL');
                    regionFilterGroup.addfilter(0, regionFilter);
                    $("#jqxGrid").jqxGrid('addfilter', 'id_region', regionFilterGroup);
                }

                // Apply filter for Lokasi (path1name)
                if (filterLokasi) {
                    var lokasiFilterGroup = new $.jqx.filter();
                    var lokasiFilter = lokasiFilterGroup.createfilter('stringfilter', filterLokasi, 'EQUAL');
                    lokasiFilterGroup.addfilter(0, lokasiFilter);
                    $("#jqxGrid").jqxGrid('addfilter', 'path1name', lokasiFilterGroup);
                }

                // Apply filter for Tegangan (path2name)
                if (filterTegangan) {
                    var teganganFilterGroup = new $.jqx.filter();
                    var teganganFilter = teganganFilterGroup.createfilter('stringfilter', filterTegangan, 'EQUAL');
                    teganganFilterGroup.addfilter(0, teganganFilter);
                    $("#jqxGrid").jqxGrid('addfilter', 'path2name', teganganFilterGroup);
                }

                // Apply filter for Bay (path3name)
                if (filterBay) {
                    var bayFilterGroup = new $.jqx.filter();
                    var bayFilter = bayFilterGroup.createfilter('stringfilter', filterBay, 'EQUAL');
                    bayFilterGroup.addfilter(0, bayFilter);
                    $("#jqxGrid").jqxGrid('addfilter', 'path3name', bayFilterGroup);
                }

                // Apply filter for Element (path4name)
                if (filterElement) {
                    var elementFilterGroup = new $.jqx.filter();
                    var elementFilter = elementFilterGroup.createfilter('stringfilter', filterElement, 'EQUAL');
                    elementFilterGroup.addfilter(0, elementFilter);
                    $("#jqxGrid").jqxGrid('addfilter', 'path4name', elementFilterGroup);
                }

                // Apply filter for Hitung Kinerja
                if (filterHitungKinerja && filterHitungKinerja !== '') {
                    var hitungKinerjaFilterGroup = new $.jqx.filter();
                    var hitungKinerjaFilter = hitungKinerjaFilterGroup.createfilter(
                        'booleanfilter', 
                        filterHitungKinerja == '1', // Convert "1" to true and "0" to false
                        'EQUAL'
                    );
                    hitungKinerjaFilterGroup.addfilter(0, hitungKinerjaFilter);
                    $("#jqxGrid").jqxGrid('addfilter', 'hitung_kinerja', hitungKinerjaFilterGroup);
                }

                // Apply all filters
                $("#jqxGrid").jqxGrid('applyfilters');
            });

            $('#resetFilters').on('click', function () {
                // Clear all filters
                $("#jqxGrid").jqxGrid('clearfilters');

                // Reset filter dropdowns
                $('#filterRegion').val('').trigger('change');
                $('#filterLokasi').val('').trigger('change');
                $('#filterTegangan').val('').trigger('change');
                $('#filterBay').val('').trigger('change');
                $('#filterElement').val('').trigger('change');
                $('#filterHitungKinerja').val('').trigger('change'); 
            });
            
            // Form validation
            $('#form-data').validate({
                rules: {
                    id_region: { required: true },
                    // point_type_id: { required: true },
                    path1name: { required: true },
                    path2name: { required: true },
                    path3name: { required: true },
                    path4name: { required: true },
                    // status: { required: true }
                },
                messages: {
                    id_region: { required: "Kolom Region wajib diisi." },
                    // point_type_id: { required: "Kolom Kelompok wajib diisi." },
                    path1name: { required: "Kolom B1 Name wajib diisi." },
                    path2name: { required: "Kolom B2 Name wajib diisi." },
                    path3name: { required: "Kolom B3 Name wajib diisi." },
                    path4name: { required: "Kolom Element wajib diisi." },
                    // status: { required: "Kolom Status wajib diisi." }
                },
                submitHandler: function (form) {
                    var reqData = new FormData(form);
                    reqData.set(
                        'hitung_kinerja',
                        $('#hitung_kinerja').is(':checked') ? 1 : 0
                    );
                    ajaxData(urlAction, reqData, refresh, true, true);
                }
            });
        });
</script>
@endpush