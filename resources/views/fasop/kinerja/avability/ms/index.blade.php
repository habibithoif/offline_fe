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
                            <!-- <div class="col-md-2">
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
                        <h3 class="card-title mb-0">KINERJA SERVER DAN WORKSTATION</h3>
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
                        <h3 class="card-title mb-0">DETAIL KINERJA SERVER DAN WORKSTATION</h3>
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

        function secondsToDuration(seconds) {
            seconds = Number(seconds);
            const hari = Math.floor(seconds / 86400);
            const jam = Math.floor((seconds % 86400) / 3600);
            const menit = Math.floor((seconds % 3600) / 60);
            const detik = seconds % 60;
            return `${hari} Hari ${jam} Jam ${menit} Menit ${detik} Detik`;
        }

        function secondsToHours(seconds) {
            const hours = Number(seconds) / 3600;
            return `${hours.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 3
            })} Jam`;
        }

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
                    { name: 'normaltime', type: 'string' },
                    { name: 'faktor', type: 'string' },
                    { name: 'ava', type: 'string' },
                    { name: 'event', type: 'array' },
                ],
                cache: false,
                root: 'detail',
                beforeprocessing: function(data) {
                    if (data && data.payload && data.payload.detail) {
                        tableData = data.payload.detail;
                        source.totalrecords = tableData.length;
                    } else {
                        console.error('Invalid data structure:', data);
                        tableData = [];
                        source.totalrecords = 0;
                    }
                },
                beforeLoadComplete: function(records) {
                    return records.map(row => {
                        return {
                            ...row,
                            tanggal:  $('#startDate').val(),
                            time_seconds: row.downtime + " Detik",
                            time_duration: secondsToDuration(row.downtime),
                            time_hours: secondsToHours(row.downtime)
                        };
                    });
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
                    // { text: 'No', width: 50, cellsalign: 'center', align: 'center',datafield: 'id',
                    // cellsrenderer: function (row) {
                    //     return "<div style='padding: 5px;'>" + (row + 1) + "</div>";
                    // }
                    // },
                    { text: 'Region', datafield: 'nama_region', width: '15%' },
                    { text: 'Server / Workstation', datafield: 'b3_name', width: '20%' },
                    { text: 'Waktu', datafield: 'tanggal', width: '10%' },
                    { text: 'Jumlah Detik Down', datafield: 'time_seconds', width: '20%' },
                    { text: 'Jumlah Waktu Down', datafield: 'time_duration', width: '20%' },
                    { text: 'Jumlah Down (JAM)', datafield: 'time_hours', width: '15%' },
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
            var url = '{{ route("fasop.avability.master-stations.read") }}';
            dataAdapter._source.url= url;
            dataAdapter.data = filterParams;
            dataAdapter._source.data = filterParams;
            $("#jqxGrid").jqxGrid('updatebounddata');
        }

        let tgl = new Date().toISOString().slice(0,7);
        $('#startDate').val(tgl);

        function applyCustomFilters() {
            var tgl = $('#startDate').val();
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

            var filterParams = {
                mulai: mulai,
                selesai: selesai,
                rekap: rekap,
                region: $('#filterRegion').val(),
                tbl: {
                    "tbl_ref": "scd_ref_ms",
                    "tbl_his": "scd_his_ms",
                    "tbl_kin": "scd_kin_ms",
                    "tbl_rtl_harian": "scd_ms_rtl_harian",
                    "jenis_kinerja": "MS"
                }
            };
            
            refreshGrid(filterParams);
        }

        function resetFilters() {
            $('.select2').val('').trigger('change');
            $('.input').val('').trigger('change');
            refreshGrid();
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
            $("#jqxGrid").jqxGrid('updatebounddata');
        });
        
        // Export to Excel
        $('#downloadButton').on('click', function() {
            exportGridAll('#jqxGrid','kinerja-master-stations','csv');
        });

        $("#jqxGrid").on('rowselect', function (event) {
            const row = event.args.row;
            const detail = row.event;
            dataAdapterDetail._source.data = detail;
            $("#jqxGridDetail").jqxGrid('updatebounddata');
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
                    { text: 'No', width: '3%', cellsalign: 'center', align: 'center',
                        cellsrenderer: function (row) {
                            return "<div style='padding: 5px;'>" + (row + 1) + "</div>";
                        }
                    },
                    { text: 'Region', datafield: 'nama_region', width: '15%' },
                    { text: 'Server / Workstation', datafield: 'b1_name', width: '22%' },
                    { text: 'Tanggal', datafield: 'b2_name', width: '20%' },
                    { text: 'Jam', datafield: 'b3_name', width: '20%' },
                    { text: 'Status', datafield: 'el_name', width: '20%' },
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