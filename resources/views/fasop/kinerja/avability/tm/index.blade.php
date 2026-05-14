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
                                    <label>Kinerja</label>
                                    <select class="form-control form-control-sm select2" id="filterKinerja" style="width: 100%;">
                                        <option value="">--Pilih Kinerja--</option>
                                        <option value="harian">Harian</option>
                                        <option value="bulanan">Bulanan</option>
                                    </select>
                                </div>
                            </div> 
                            <div class="col-md-2" id="start" style="display:none;">
                                <div class="form-group">
                                    <label for="startDate">Start Date</label>
                                    <input type="date" id="startDate" class="form-control form-control-sm">
                                </div>
                            </div>
                        
                            <div class="col-md-2" id="end" style="display:none;">
                                <div class="form-group">
                                    <label for="endDate">End Date</label>
                                    <input type="date" id="endDate" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="col-md-2" id="bulan" style="display:none;">
                                <div class="form-group">
                                    <label for="filterBulan">Bulan</label>
                                    <input type="month" id="filterBulan" class="form-control form-control-sm">
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

        <div class="row justify-content-center">
            <!-- Tabel 1 -->
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-body p-2">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>Jumlah Telemetering</th>
                                        <th>Jumlah Telemetering Yang Tidak Akurat</th>
                                        <th>Jumlah Prosentase Akurasi Telemetering (%)</th>
                                    </tr>
                                </thead>
                                <tbody id="tabel_rekap">
                                    <!-- <tr>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>100</td>
                                    </tr> -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Grid Card -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm rounded">
                    <div class="card-header bg-info text-white">
                        <h3 class="card-title mb-0">KINERJA - TELEMETERING</h3>
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
                        <h3 class="card-title mb-0">DETAIL KINERJA - TELEMETERING</h3>
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
                            <button id="downloadDetailButton" class="btn btn-default btn-sm" title="Download">
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

        //set tanggal
        let thisday = new Date().toISOString().slice(0,10);
        let yesterday = new Date();
        yesterday.setDate(yesterday.getDate() - 1);
        let daybefore = yesterday.toISOString().slice(0, 10);
        let tgl = new Date().toISOString().slice(0,7);

        $('#startDate').val(formatWIB(daybefore));
        $('#endDate').val(thisday);
        $('#filterBulan').val(formatWIB(tgl));
        $('#filterRegion').val(0);

        //show or hide tanggal
        $('#filterKinerja').on('change', function () {
            if ($(this).val() === 'harian') {
                $('#start').show();
                $('#end').show();
                $('#bulan').hide();
            } else if ($(this).val() === 'bulanan') {
                $('#start').hide();
                $('#end').hide();
                $('#bulan').show();
            }else{
                $('#start').hide();
                $('#end').hide();
                $('#bulan').hide();
            }
        });

        function initializeGrid() {
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
                    { name: 'alltime', type: 'string' },
                    { name: 'faktor', type: 'string' },
                    { name: 'ava', type: 'string' },
                    { name: 'event', type: 'array' },
                ],
                cache: false,
                root: 'detail',
                beforeprocessing: function(data) {
                    const tbody = document.getElementById('tabel_rekap');

                    // kosongkan isi tabel dulu
                    tbody.innerHTML = '';

                    let html = '';

                    var data_rekap = data.payload.rekap;
                    if(data_rekap.length != 0){
                        html += `
                            <tr>
                                <td>${data_rekap.total_up}</td>
                                <td>${data_rekap.total_down}</td>
                                <td>${data_rekap.total_ava}</td>
                            </tr>
                        `;
                    }
                    
                    // replace isi baru
                    tbody.innerHTML = html;
                   
                    if (data && data.payload && data.payload.detail) {
                        tableData = data.payload.detail;
                        source.totalrecords = tableData.length;
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
                    { text: 'Normal Time', datafield: 'alltime', width: 100 },
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
                theme: 'material'
            });
        }

        function refreshGrid(filterParams = {}) {
            // Update the dataAdapter with new parameters
            var url = '{{ route("fasop.avability.telemetering.read") }}';
            dataAdapter._source.url= url;
            dataAdapter.data = filterParams;
            dataAdapter._source.data = filterParams;
            
            // Clear previous data
            // dataAdapter.dataBind();
            $("#jqxGrid").jqxGrid('updatebounddata');
        }

        function applyCustomFilters() {
            if($('#filterKinerja').val()==='bulanan'){
                var tgl = $('#filterBulan').val();
                var mulai = tgl +'-01';

                const [year, month] = tgl.split('-').map(Number);
                const lastDate = new Date(year, month, 0);

                var selesai =
                    `${lastDate.getFullYear()}-${
                        String(lastDate.getMonth() + 1).padStart(2, '0')
                    }-${
                        String(lastDate.getDate()).padStart(2, '0')
                    }`;
                var rekap='bulan';
            }else{
                var mulai = $('#startDate').val();
                var selesai = $('#endDate').val();
                var rekap = 'hari';
            }
            
            var filterParams = {
                mulai: mulai,
                selesai: selesai,
                rekap: rekap,
                region: $('#filterRegion').val(),
                tbl: {
                    "tbl_ref": "scd_ref_tm",
                    "tbl_his": "scd_his_tm",
                    "tbl_kin": "scd_kin_tm",
                    "tbl_rtl_harian": "scd_tm_rtl_harian",
                    "jenis_kinerja": "TM"
                }
            };
            
            refreshGrid(filterParams);
        }

        function resetFilters() {
            $('.select2').val('').trigger('change');
            $('.input').val('').trigger('change');
            // refreshGrid();
            applyCustomFilters();
        }

        $(document).ready(function() {
            // Initialize grid first time
            initializeGrid();
            applyCustomFilters();
            initializeGridDetail();
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
            $("#jqxGrid").jqxGrid('clearselection');
            $("#jqxGrid").jqxGrid('updatebounddata');
        });
        
        // Export to Excel
        $('#downloadButton').on('click', function() {
            exportGridAll('#jqxGrid','kinerja-telemetering','csv');

        });

        function initializeGridDetail() {
            // Create data source
            var sourceDetail = {
                datatype: "array",
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
                cache: false,
                beforeprocessing: function(data) {
                    if (data) {
                        tableData = data;
                        sourceDetail.totalrecords = data.length;
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
                theme: 'material'
            });
        }
        
        $("#jqxGrid").on('rowselect', function (event) {
            const row = event.args.row;

            const detail = row.event;
            dataAdapterDetail._source.data = detail;
            // Clear previous data
            // dataAdapterDetail.dataBind();
            $("#jqxGridDetail").jqxGrid('updatebounddata');

        });

        // Refresh button functionality
        $('#refreshDetailButton').on('click', function() {
            $("#jqxGridDetail").jqxGrid('clearselection');
            $("#jqxGridDetail").jqxGrid('updatebounddata');
        });
        
        // Export to Excel
        $('#downloadDetailButton').on('click', function() {
            exportGridLocal('#jqxGridDetail','Detail-kinerja-telemetering','csv');
        });

    </script>
@endpush