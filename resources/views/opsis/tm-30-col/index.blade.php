@extends('layouts.layouts_app')

@section('content')

<x-content-header 
    :title="$data->page['name']" 
    :breadcrumbs="[
        ['label' => 'Home', 'url' => '#'],
        ['label' => 'OPSIS', 'url' => '#'],
        ['label' => $data->page['name'], 'url' => '#']
    ]" 
/>

<section class="content">
    <div class="container-fluid">
        <!-- Filter Card -->
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-filter mr-2"></i>FILTER
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="startDate">Start Date</label>
                                    <input type="date" id="startDate" class="form-control form-control-sm">
                                </div>
                            </div>
                        
                            <!-- End Date -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="endDate">End Date</label>
                                    <input type="date" id="endDate" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Tipe Point</label>
                                    <select class="form-control form-control-sm select2" id="filterTipePoint" style="width: 100%;">
                                        <option value="">--Pilih Tipe Point--</option>
                                        @foreach ($data->point_types as $item)
                                            <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Durasi</label>
                                    <select class="form-control form-control-sm select2" id="filterDurasi" style="width: 100%;">
                                        <option value="">--Pilih Durasi--</option>
                                        <option value="2">2 Jam</option>
                                        <option value="4">4 Jam</option>
                                        <option value="6">6 Jam</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>B1 Name</label>
                                    <select class="form-control form-control-sm select2" id="filterLokasi">
                                        <option value="">--Pilih B1 Name--</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>B2 Name</label>
                                    <div class="input-group input-group-sm">
                                        <select class="form-control form-control-sm select2" id="filterTegangan">
                                            <option value="">--Pilih B2 Name--</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>B3 Name</label>
                                    <div class="input-group input-group-sm">
                                        <select class="form-control form-control-sm select2" id="filterBay">
                                            <option value="">--Pilih B3 Name--</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Element</label>
                                    <div class="input-group input-group-sm">
                                        <select class="form-control form-control-sm select2" id="filterElement">
                                            <option value="">--Pilih Element--</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Info</label>
                                    <div class="input-group input-group-sm">
                                        <select class="form-control form-control-sm select2" id="filterInfo">
                                            <option value="">--Pilih Info--</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-md-2">
                                <div class="form-group">
                                    <label>Kesimpulan</label>
                                    <select class="form-control form-control-sm select2" id="filterKesimpulan" style="width: 100%;">
                                        <option value="">--Pilih Kesimpulan--</option>
                                        <option value="VALID">VALID</option>
                                        <option value="INVALID">INVALID</option>
                                    </select>
                                </div>
                            </div> -->
                        </div>
                    </div>
                    <div class="card-footer">
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

        <!-- Main Grid Card -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm rounded">
                    <div class="card-header bg-info text-white">
                        <h3 class="card-title mb-0">FASOP - HISTORY</h3>
                        <div class="card-tools">
                            <button id="refreshButton" class="btn btn-default btn-sm" title="Refresh">
                                <i class="fas fa-sync"></i>
                            </button>
                            <button id="listViewButton" class="btn btn-default btn-sm" title="List View">
                                <i class="fas fa-list"></i>
                            </button>
                            <button id="downloadButton" class="btn btn-default btn-sm" title="Download">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div id="jqxGrid" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</section>

@endsection

@push('scripts')
    <script>
        var userAccess = @json($data->accesses);
        var currentPath = window.location.pathname;
        var baseUrl = mainServerUrl + currentPath;
        var tableData = [];
        var dataAdapter; // Make dataAdapter global to access it later

        function initializeGrid(filterParams = {}) {
            // CSRF Token setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Create data source
            var source = {
                datatype: "json",
                datafields: [
                    { name: 'id', type: 'string' },
                    { name: 'point_number', type: 'string' },
                    { name: 'nama_pointtype', type: 'string' },
                    { name: 'path1', type: 'string' },
                    { name: 'path2', type: 'string' },
                    { name: 'path3', type: 'string' },
                    { name: 'path4', type: 'string' },
                    { name: 'path5', type: 'string' },
                    { name: 'value', type: 'string' },
                    { name: 'durasi', type: 'string' },
                    { name: 'datum_capture', type: 'string' },
                    { name: 'status_1', type: 'string' },
                    { name: 'kesimpulan', type: 'string' },
                ],
                url: '{{ route("fasop.histories.telemetering.read") }}',
                cache: false,
                data: filterParams,
                root: 'Rows',
                beforeprocessing: function(data) {
                    if (data && data.data && data.data.Rows) {
                        tableData = data.data.Rows;
                        source.totalrecords = data.data.TotalRows;
                    } else {
                        console.error('Invalid data structure:', data);
                        tableData = [];
                        source.totalrecords = 0;
                    }
                }
            };

            // Create data adapter
            dataAdapter = new $.jqx.dataAdapter(source);

            // Initialize grid
            $("#jqxGrid").jqxGrid({
                width: '100%',
                source: dataAdapter,
                pageable: true,
                virtualmode: true,
                autorowheight: true,
                autoheight: false,
                showtoolbar: false,
                rendergridrows: function() {
                    return dataAdapter.records;
                },
                columns: [
                    { text: 'No', width: 50, cellsalign: 'center', align: 'center',
                    cellsrenderer: function (row) {
                        return "<div style='padding: 5px;'>" + (row + 1) + "</div>";
                    }
                    },
                    { text: 'Tanggal', datafield: 'point_number', width: 100 },
                    { text: 'B1 Text', datafield: 'path1text', width: 200 },
                    { text: 'B2 Text', datafield: 'path2text', width: 200 },
                    { text: 'B3 Text', datafield: 'path3text', width: 200 },
                    { text: 'Element Text', datafield: 'path4text', width: 200 },
                    { text: 'Info', datafield: 'path5text', width: 200 },
                    { text: '00:30', datafield: '0030', width: 50, cellsalign: 'center' },
                    { text: '01:00', datafield: '0100', width: 50, cellsalign: 'center' },
                    { text: '01:30', datafield: '0130', width: 50, cellsalign: 'center' },
                    { text: '02:00', datafield: '0200', width: 50, cellsalign: 'center' },
                    { text: '02:30', datafield: '0230', width: 50, cellsalign: 'center' },
                    { text: '03:00', datafield: '0300', width: 50, cellsalign: 'center' },
                    { text: '03:30', datafield: '0330', width: 50, cellsalign: 'center' },
                    { text: '04:00', datafield: '0400', width: 50, cellsalign: 'center' },
                    { text: '04:30', datafield: '0430', width: 50, cellsalign: 'center' },
                    { text: '05:00', datafield: '0500', width: 50, cellsalign: 'center' },
                    { text: '05:30', datafield: '0530', width: 50, cellsalign: 'center' },
                    { text: '06:00', datafield: '0600', width: 50, cellsalign: 'center' },
                    { text: '06:30', datafield: '0630', width: 50, cellsalign: 'center' },
                    { text: '07:00', datafield: '0700', width: 50, cellsalign: 'center' },
                    { text: '07:30', datafield: '0730', width: 50, cellsalign: 'center' },
                    { text: '08:00', datafield: '0800', width: 50, cellsalign: 'center' },
                    { text: '08:30', datafield: '0830', width: 50, cellsalign: 'center' },
                    { text: '09:00', datafield: '0900', width: 50, cellsalign: 'center' },
                    { text: '09:30', datafield: '0930', width: 50, cellsalign: 'center' },
                    { text: '10:00', datafield: '1000', width: 50, cellsalign: 'center' },
                    { text: '10:30', datafield: '1030', width: 50, cellsalign: 'center' },
                    { text: '11:00', datafield: '1100', width: 50, cellsalign: 'center' },
                    { text: '11:30', datafield: '1130', width: 50, cellsalign: 'center' },
                    { text: '12:00', datafield: '1200', width: 50, cellsalign: 'center' },
                    { text: '12:30', datafield: '1230', width: 50, cellsalign: 'center' },
                    { text: '13:00', datafield: '1300', width: 50, cellsalign: 'center' },
                    { text: '13:30', datafield: '1330', width: 50, cellsalign: 'center' },
                    { text: '14:00', datafield: '1400', width: 50, cellsalign: 'center' },
                    { text: '14:30', datafield: '1430', width: 50, cellsalign: 'center' },
                    { text: '15:00', datafield: '1500', width: 50, cellsalign: 'center' },
                    { text: '15:30', datafield: '1530', width: 50, cellsalign: 'center' },
                    { text: '16:00', datafield: '1600', width: 50, cellsalign: 'center' },
                    { text: '16:30', datafield: '1630', width: 50, cellsalign: 'center' },
                    { text: '17:00', datafield: '1700', width: 50, cellsalign: 'center' },
                    { text: '17:30', datafield: '1730', width: 50, cellsalign: 'center' },
                    { text: '18:00', datafield: '1800', width: 50, cellsalign: 'center' },
                    { text: '18:30', datafield: '1830', width: 50, cellsalign: 'center' },
                    { text: '19:00', datafield: '1900', width: 50, cellsalign: 'center' },
                    { text: '19:30', datafield: '1930', width: 50, cellsalign: 'center' },
                    { text: '20:00', datafield: '2000', width: 50, cellsalign: 'center' },
                    { text: '20:30', datafield: '2030', width: 50, cellsalign: 'center' },
                    { text: '21:00', datafield: '2100', width: 50, cellsalign: 'center' },
                    { text: '21:30', datafield: '2130', width: 50, cellsalign: 'center' },
                    { text: '22:00', datafield: '2200', width: 50, cellsalign: 'center' },
                    { text: '22:30', datafield: '2230', width: 50, cellsalign: 'center' },
                    { text: '23:00', datafield: '2300', width: 50, cellsalign: 'center' },
                    { text: '23:30', datafield: '2330', width: 50, cellsalign: 'center' },
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
        }

        function refreshGrid(filterParams = {}) {
            // Update the dataAdapter with new parameters
            dataAdapter.data = filterParams;
            dataAdapter._source.data = filterParams;
            
            // Clear previous data
            dataAdapter.dataBind();
            
            // Refresh the grid
            $("#jqxGrid").jqxGrid('updatebounddata');
        }

        function applyCustomFilters() {
            var filterParams = {
                tipe_point: $('#filterTipePoint').val(),
                durasi: $('#filterDurasi').val(),
                lokasi: $('#filterLokasi').val(),
                tegangan: $('#filterTegangan').val(),
                bay: $('#filterBay').val(),
                element: $('#filterElement').val(),
                info: $('#filterInfo').val(),
                kesimpulan: $('#filterKesimpulan').val()
            };
            
            refreshGrid(filterParams);
        }

        function resetFilters() {
            $('.select2').val('').trigger('change');
            refreshGrid();
        }

        $(document).ready(function() {
            // Initialize grid first time
            initializeGrid();

            // Initialize select2 controls
            $('.select2').select2();

            // Set up event handlers
            $('#applyFilters').on('click', applyCustomFilters);
            $('#resetFilters').on('click', resetFilters);
            $('#refreshButton').on('click', function() {
                refreshGrid();
            });

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
                            field: 'path4name',
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
                placeholder: '--Pilih Element--'
            });

            $('#filterInfo').select2({
                ajax: {
                    url: '{{ route("cpoint.findValueBy") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            keyword: params.term,  
                            page: params.page || 1,
                            field: 'path5name',
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
                placeholder: '--Pilih Info--'
            });

            // Apply filters button
            $('#applyFilters').on('click', function() {
                applyCustomFilters();
            });
            
            // Reset filters button
            $('#resetFilters').on('click', function() {
                resetFilters();
            });
            
            // Refresh button functionality
            $('#refreshButton').on('click', function() {
                $("#jqxGrid").jqxGrid('updatebounddata');
            });
            
            // Export to Excel
            $('#downloadButton').on('click', function() {
                $("#jqxGrid").jqxGrid('exportdata', 'xlsx', 'TelemetryData');
            });
            
            // List view button (toggle view or implement custom view)
            $('#listViewButton').on('click', function() {
                // Implement custom view toggle here
                console.log("List view toggle clicked");
            });
        });
    </script>
@endpush