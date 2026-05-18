@extends('layouts.layouts_app')

@section('content')

<x-content-header 
    :title="$data->page['name']" 
    :breadcrumbs="[
        ['label' => 'Home', 'url' => '/dashboard/monitoringrtu'],
        ['label' => 'FASOP', 'url' => '#'],
        ['label' => 'Kinerja', 'url' => '#'],
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
                                    <label for="startDate">Bulan</label>
                                    <input type="month" id="startDate" class="form-control form-control-sm">
                                </div>
                            </div>
                        
                            <!-- End Date -->
                            <!-- <div class="col-md-2">
                                <div class="form-group">
                                    <label for="endDate">End Date</label>
                                    <input type="date" id="endDate" class="form-control form-control-sm">
                                </div>
                            </div> -->
                            <!-- <div class="col-md-2">
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
                            </div> -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Region</label>
                                    <select class="form-control form-control-sm select2" id="filterRegion" style="width: 100%;">
                                        <option value="">--Pilih Region--</option>
                                        @foreach ($data->ref_region as $item)
                                            <option value="{{ $item['region'] }}">{{ $item['nama'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> 
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>B1 Name</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm input" id="filterLokasi"></input>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>B2 Name</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm input" id="filterTegangan"></input>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>B3 Name</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm input" id="filterBay"></input>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Element</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm input" id="filterElement"></input>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Info</label>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control form-control-sm input" id="filterInfo"></input>
                                    </div>
                                </div>
                            </div>
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
                        <h3 class="card-title mb-0">KINERJA - TELESIGNAL</h3>
                        <div class="card-tools">
                            <button id="refreshButton" class="btn btn-default btn-sm" title="Refresh">
                                <i class="fas fa-sync"></i>
                            </button>
                            
                            <!-- <button id="listViewButton" class="btn btn-default btn-sm" title="List View">
                                <i class="fas fa-list"></i>
                            </button>
                            <div id="columnDropdown" style="display:none; position:absolute; right:0; z-index:99999;">
                                <div id="columnListBox"></div>
                            </div>  -->
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
        
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm rounded">
                    <div class="card-header bg-info text-white">
                        <h3 class="card-title mb-0">DETAIL KINERJA - TELESIGNAL</h3>
                        <div class="card-tools">
                            <button id="refreshDetailButton" class="btn btn-default btn-sm" title="Refresh">
                                <i class="fas fa-sync"></i>
                            </button>
                            
                            <!-- <button id="listViewDetailButton" class="btn btn-default btn-sm" title="List View">
                                <i class="fas fa-list"></i>
                            </button>
                            <div id="columnDetailDropdown" style="display:none; position:absolute; right:0; z-index:99999;">
                                <div id="columnListBoxDetail"></div>
                            </div>  -->
                            <button id="downloaddDetailButton" class="btn btn-default btn-sm" title="Download">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div id="jqxGridDetail" style="width: 100%;"></div>
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
                    { name: 'id_region', type: 'string' },
                    { name: 'nama_region', type: 'string' },
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
                    { name: 'status', type: 'string' },
                    { name: 'kinerja', type: 'string' },
                    { name: 'rtu_datetime', type: 'string' },
                    { name: 'system_datetime', type: 'string' },
                    { name: 'status', type: 'string' },
                    { name: 'up', type: 'string' },
                    { name: 'down', type: 'string' },
                    { name: 'uptime', type: 'string' },
                    { name: 'downtime', type: 'string' },
                    { name: 'normaltime', type: 'string' },
                    { name: 'faktor', type: 'string' },
                    { name: 'ava', type: 'string' },
                    { name: 'durasi', type: 'string' },
                ],
                url: '{{ route("fasop.kinerja.telesignals.read") }}',
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
                    { text: 'No', width: 50, cellsalign: 'center', align: 'center',datafield: 'id',
                    cellsrenderer: function (row) {
                        return "<div style='padding: 5px;'>" + (row + 1) + "</div>";
                    }
                    },
                    { text: 'Region', datafield: 'nama_region', width: 150 },
                    { text: 'B1 Name', datafield: 'b1_name', width: 150 },
                    { text: 'B2 Name', datafield: 'b2_name', width: 100 },
                    { text: 'B3 Name', datafield: 'b3_name', width: 150 },
                    { text: 'Element', datafield: 'el_name', width: 100 },
                    { text: 'Info', datafield: 'Info_name', width: 100 },
                    { text: 'B1 Text', datafield: 'b1_text', width:150 },
                    { text: 'B2 Text', datafield: 'b2_text', width: 100 },
                    { text: 'B3 Text', datafield: 'b3_text', width: 150 },
                    { text: 'Element Text', datafield: 'el_text', width: 150 },
                    { text: 'Info Text', datafield: 'info_text', width: 100 },
                    { text: 'Faktor Kinerja', datafield: 'faktor', width: 100 },
                    { text: 'Up', datafield: 'up', width: 100 },
                    { text: 'Down', datafield: 'down', width: 100 },
                    { text: 'Normal Time', datafield: 'normaltime', width: 100 },
                    { text: 'Up Time', datafield: 'uptime', width: 100 },
                    { text: 'Down Time', datafield: 'downtime', width: 100 },
                    { text: 'Ava(%)', datafield: 'ava', width: 100 },
                    { text: 'Kinerja', datafield: 'kinerja', width: 100 }
                ],
                pagermode: 'default',
                pagesize: 20,
                pagesizeoptions: ['5', '10', '20', '50'],
                sortable: true,
                filterable: true,
                showfilterrow: true,
                filtermode: 'excel',
                theme: 'metro'
            });
        }

        function refreshGrid(filterParams = {}) {
            // Update the dataAdapter with new parameters
            dataAdapter.data = filterParams;
            dataAdapter._source.data = filterParams;
            
            // Clear previous data
            dataAdapter.dataBind();
            
            // Refresh the grid
            // $("#jqxGrid").jqxGrid('updatebounddata');
        }

        let tgl = new Date().toISOString().slice(0,7);
        $('#startDate').val(tgl);

        function applyCustomFilters() {
            var filterParams = {
                tanggal: $('#startDate').val(),
                id_region: $('#filterRegion').val(),
                b1_name: $('#filterLokasi').val(),
                b2_name: $('#filterTegangan').val(),
                b3_name: $('#filterBay').val(),
                el_name: $('#filterElement').val(),
                info_name: $('#filterInfo').val()
            };
            
            refreshGrid(filterParams);
        }

        function resetFilters() {
            $('.select2').val('').trigger('change');
            $('.input').val('').trigger('change');
            refreshGrid();
        }

        $(document).ready(function() {
            // Initialize grid first time
            let filterParams = {
                tanggal: $('#startDate').val(),
                id_region: $('#filterRegion').val(),
                b1_name: $('#filterLokasi').val(),
                b2_name: $('#filterTegangan').val(),
                b3_name: $('#filterBay').val(),
                el_name: $('#filterElement').val(),
                info_name: $('#filterInfo').val()
            };
            initializeGrid(filterParams);
            // Initialize select2 controls
            // $('.select2').select2();

            // Set up event handlers
            // $('#applyFilters').on('click', applyCustomFilters);
            // $('#resetFilters').on('click', resetFilters);
            // $('#refreshButton').on('click', function() {
            //     refreshGrid();
            // });

            // Rest of your select2 initialization code...
            // $('#filterLokasi').select2({
            //     ajax: {
            //         url: '{{ route("cpoint.findValueBy") }}',
            //         dataType: 'json',
            //         delay: 250,
            //         data: function (params) {
            //             return {
            //                 keyword: params.term, 
            //                 page: params.page || 1,
            //                 field: 'path1'
            //             };
            //         },
            //         processResults: function (data, params) {
            //             params.page = params.page || 1;
            //             const response = data.data.data;
            //             return {
            //                 results: response.map(function(item) {
            //                     return {
            //                         id: item, 
            //                         text: item 
            //                     };
            //                 }),
            //                 pagination: {
            //                     more: (params.page * 10) < data.total  
            //                 }
            //             };
            //         },
            //         cache: true
            //     },
            //     allowClear: true,
            //     placeholder: '--Pilih B1 Name--',
            // });

            // $('#filterTegangan').select2({
            //     ajax: {
            //         url: '{{ route("cpoint.findValueBy") }}',
            //         dataType: 'json',
            //         delay: 250,
            //         data: function (params) {
            //             return {
            //                 keyword: params.term,  
            //                 page: params.page || 1,
            //                 field: 'path2' 
            //             };
            //         },
            //         processResults: function (data, params) {
            //             params.page = params.page || 1;
            //             const response = data.data.data;
            //             return {
            //                 results: response.map(function(item) {
            //                     return {
            //                         id: item, 
            //                         text: item 
            //                     };
            //                 }),
            //                 pagination: {
            //                     more: (params.page * 10) < data.total  
            //                 }
            //             };
            //         },
            //         cache: true
            //     },
            //     allowClear: true,
            //     placeholder: '--Pilih B2 Name--'
            // });

            // $('#filterBay').select2({
            //     ajax: {
            //         url: '{{ route("cpoint.findValueBy") }}',
            //         dataType: 'json',
            //         delay: 250,
            //         data: function (params) {
            //             return {
            //                 keyword: params.term,  
            //                 page: params.page || 1,
            //                 field: 'path3' 
            //             };
            //         },
            //         processResults: function (data, params) {
            //             params.page = params.page || 1;
            //             const response = data.data.data;
            //             return {
            //                 results: response.map(function(item) {
            //                     return {
            //                         id: item, 
            //                         text: item 
            //                     };
            //                 }),
            //                 pagination: {
            //                     more: (params.page * 10) < data.total  
            //                 }
            //             };
            //         },
            //         cache: true
            //     },
            //     allowClear: true,
            //     placeholder: '--Pilih B3 Name--'
            // });

            // $('#filterElement').select2({
            //     ajax: {
            //         url: '{{ route("cpoint.findValueBy") }}',
            //         dataType: 'json',
            //         delay: 250,
            //         data: function (params) {
            //             return {
            //                 keyword: params.term,  
            //                 page: params.page || 1,
            //                 field: 'path4' 
            //             };
            //         },
            //         processResults: function (data, params) {
            //             params.page = params.page || 1;
            //             const response = data.data.data;
            //             return {
            //                 results: response.map(function(item) {
            //                     return {
            //                         id: item, 
            //                         text: item 
            //                     };
            //                 }),
            //                 pagination: {
            //                     more: (params.page * 10) < data.total  
            //                 }
            //             };
            //         },
            //         cache: true
            //     },
            //     allowClear: true,
            //     placeholder: '--Pilih Element--'
            // });

            // $('#filterInfo').select2({
            //     ajax: {
            //         url: '{{ route("cpoint.findValueBy") }}',
            //         dataType: 'json',
            //         delay: 250,
            //         data: function (params) {
            //             return {
            //                 keyword: params.term,  
            //                 page: params.page || 1,
            //                 field: 'path5' 
            //             };
            //         },
            //         processResults: function (data, params) {
            //             params.page = params.page || 1;
            //             const response = data.data.data;
            //             return {
            //                 results: response.map(function(item) {
            //                     return {
            //                         id: item, 
            //                         text: item 
            //                     };
            //                 }),
            //                 pagination: {
            //                     more: (params.page * 10) < data.total  
            //                 }
            //             };
            //         },
            //         cache: true
            //     },
            //     allowClear: true,
            //     placeholder: '--Pilih Element--'
            // });

            
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
            // $("#jqxGrid").jqxGrid('exportdata', 'xls', 'TelemetryData');
            // $.ajaxSetup({
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     }
            // });
            // $("#jqxGrid").jqxGrid('exportdata', 'xls', 'telesignal', true, null, true, '/export/save-file');
            exportGridAll('#jqxGrid','kinerja-telesignal','csv');

        });
        function getlastdayofmonth(str){
            const[year,month] = str.split('-').map(Number);
            const lastdate = new Date(year,month,0);
            return lastdate.toISOString().slice(0,10);
        }
        $("#jqxGrid").on('rowselect', function (event) {
            var tgl1 = $('#startDate').val();
            var tanggal1 = tgl1+'-01T00:00:00';
            var tanggal2 = getlastdayofmonth(tgl1)+'T23:59:59';
            var selectedRowData = event.args.row;
            var detailParams = {   "b1_nameoperator" : "and",
                                    "filtervalue0" : selectedRowData.b1_name,
                                    "filtercondition0" : "EQUAL",
                                    "filteroperator0" : 1,
                                    "filterdatafield0" : "a.b1_name",
                                    "b2_nameoperator" : "and",
                                    "filtervalue1" : selectedRowData.b2_name,
                                    "filtercondition1" : "EQUAL",
                                    "filteroperator1" : 1,
                                    "filterdatafield1" : "a.b2_name",
                                    "b3_nameoperator" : "and",
                                    "filtervalue2" : selectedRowData.b3_name,
                                    "filtercondition2" : "EQUAL",
                                    "filteroperator2" : 1,
                                    "filterdatafield2" : "a.b3_name",
                                    "el_nameoperator" : "and",
                                    "filtervalue3" : selectedRowData.el_name,
                                    "filtercondition3" : "EQUAL",
                                    "filteroperator3" : 1,
                                    "filterdatafield3" : "a.el_name",
                                    "info_nameoperator" : "and",
                                    "filtervalue4" : selectedRowData.info_name,
                                    "filtercondition4" : "EQUAL",
                                    "filteroperator4" : 1,
                                    "filterdatafield4" : "a.info_name",
                                    "filterscount" : 5,
                                    "tanggal1" : tanggal1,
                                    "tanggal2" : tanggal2
                            };
           initializeGridDetail(detailParams);
        });

        function initializeGridDetail(filterParams = {}) {
            // CSRF Token setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Create data source
            var sourceDetail = {
                datatype: "json",
                datafields: [
                    { name: 'id', type: 'string' },
                    { name: 'id_region', type: 'string' },
                    { name: 'nama_region', type: 'string' },
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
                    { name: 'status', type: 'string' },
                    { name: 'kinerja', type: 'string' },
                    { name: 'rtu_datetime', type: 'string' },
                    { name: 'system_datetime', type: 'string' },
                    { name: 'durasi', type: 'string' },
                ],
                url: '{{ route("fasop.histories.telesignals.read") }}',
                cache: false,
                data: filterParams,
                root: 'Rows',
                beforeprocessing: function(data) {
                    if (data && data.data && data.data.Rows) {
                        tableData = data.data.Rows;
                        sourceDetail.totalrecords = data.data.TotalRows;
                    } else {
                        console.error('Invalid data structure:', data);
                        tableData = [];
                        sourceDetail.totalrecords = 0;
                    }
                }
            };

            // Create data adapter
            dataAdapterDetail = new $.jqx.dataAdapter(sourceDetail);

            // Initialize grid
            $("#jqxGridDetail").jqxGrid({
                width: '100%',
                source: dataAdapterDetail,
                pageable: true,
                virtualmode: true,
                autorowheight: true,
                autoheight: false,
                showtoolbar: false,
                rendergridrows: function() {
                    return dataAdapterDetail.records;
                },
                columns: [
                    { text: 'No', width: 50, cellsalign: 'center', align: 'center',
                        cellsrenderer: function (row) {
                            return "<div style='padding: 5px;'>" + (row + 1) + "</div>";
                        }
                    },
                    { text: 'Region', datafield: 'nama_region', width: 150 },
                    { text: 'B1 Name', datafield: 'b1_name', width: 150 },
                    { text: 'B2 Name', datafield: 'b2_name', width: 100 },
                    { text: 'B3 Name', datafield: 'b3_name', width: 150 },
                    { text: 'Element Name', datafield: 'el_name', width: 150 },
                    { text: 'Info Name', datafield: 'info_name', width: 100 },
                    { text: 'B1 Text', datafield: 'b1_text', width:150 },
                    { text: 'B2 Text', datafield: 'b2_text', width: 100 },
                    { text: 'B3 Text', datafield: 'b3_text', width: 150 },
                    { text: 'Element Text', datafield: 'el_text', width: 150 },
                    { text: 'Info Text', datafield: 'info_text', width: 100 },
                    { text: 'Datetime RTU', datafield: 'rtu_datetime', width: 200 },
                    { text: 'Datetime Sistem', datafield: 'system_datetime', width: 200 },
                    { text: 'Status', datafield: 'status', width: 200 },
                ],
                pagermode: 'default',
                pagesize: 20,
                pagesizeoptions: ['5', '10', '20', '50'],
                sortable: true,
                filterable: true,
                showfilterrow: true,
                filtermode: 'excel',
                theme: 'metro'
            });
        }

        // Refresh button functionality
        $('#refreshDetailButton').on('click', function() {
            $("#jqxGridDetail").jqxGrid('clearselection');
            $("#jqxGridDetail").jqxGrid('updatebounddata');
        });
        
        // Export to Excel
        $('#downloadDetailButton').on('click', function() {
            exportGridAll('#jqxGridDetail','Detail-kinerja-telemetering','csv');

        });

        // columns = $("#jqxGrid").jqxGrid('columns');
        
        // // Init jqxListBox
        // const listBoxData = columns.map(col => ({
        //     label: col.text,
        //     value: col.datafield,
        //     checked: true
        // }));

        // $("#columnListBox").jqxListBox({
        //     source: listBoxData,
        //     checkboxes: true,
        //     width: 200,
        //     height: 250
        // });

        // // List view button (toggle view or implement custom view)
        // $('#listViewButton').on('click', function() {
        //     $('#columnDropdown').toggle();
        // });

        // // Hide when clicking outside
        // $(document).on('click', function (e) {
        //     if (!$(e.target).closest('#listViewButton, #columnDropdown').length) {
        //         $('#columnDropdown').hide();
        //     }
        // });
        
        // // Column show/hide on check/uncheck
        // $('#columnListBox').on('checkChange', function (event) {
        //     const item = event.args.item;
        //     if (item.checked) {
        //         $("#jqxGrid").jqxGrid('showcolumn', item.value);
        //     } else {
        //         $("#jqxGrid").jqxGrid('hidecolumn', item.value);
        //     }
        // });
    </script>
@endpush