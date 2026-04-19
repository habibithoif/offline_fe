@extends('layouts.layouts_app')

@section('content')
@php
    $months = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
@endphp

<x-content-header 
    :title="$data->page['name']" 
    :breadcrumbs="[
        ['label' => 'Home', 'url' => '#'],
        ['label' => 'FASOP', 'url' => '#'],
        ['label' => 'Dashboard', 'url' => '#'],
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
                                            <select class="form-control form-control-sm select2" style="width: 100%;"  id="filterRegion">
                                                <option value="all">-- Semua Region --</option>
                                                @foreach ($data->ref_region as $item)
                                                    <option value="{{ $item['id_region'] }}">{{ $item['nama'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Tahun</label>
                                            <input type="number" class="form-control form-control-sm" id="filterBulanTahun" />
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow-sm rounded">
                            <div class="card-header bg-info text-white d-flex flex-column justify-content-center align-items-center">
                                <h3 class="card-title mb-1">TARGET BULANAN </h3>
                            </div>
                            <div class="card-body p-3">
                                <div id="chart-target-bulanan" style="width: 100%; height: 400px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="card shadow-sm rounded">
                            <div class="card-header bg-info">
                                <h3 class="card-title mb-1">TARGET BULANAN </h3>
                            </div>
                            <div class="card-body p-3">
                                <div id="jqxGrid" style="width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

@endsection

@push('scripts')
    <script>
        var userAccess = @json($data->accesses);
        var currentPath = window.location.pathname;
        var baseUrl = mainServerUrl + currentPath;
        var dataTable = []; // Store dataTable data for easy access
        var actionMethod = 'POST';

        function refresh(result) {
            alertSuccess(result.message);
        }

        function loadCharts() {
            // Get the selected Region and Bulan-Tahun filters
            const filterRegion = $('#filterRegion').val();
            const filterYear = $('#filterBulanTahun').val();

            if (!filterYear) {
                alert('Please select a valid year!');
                return;
            }

            // Update the header text
            $('.headerBulan1').text(filterYear);

            const categories = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            // Dummy data for multiple regions
            const regionData = {
                'TELEMETERING': Array.from({ length: categories.length }, () => Math.floor(Math.random() * 20) + 80), // Random data
                'TELESIGNAL': Array.from({ length: categories.length }, () => Math.floor(Math.random() * 20) + 70),
                'RTU': Array.from({ length: categories.length }, () => Math.floor(Math.random() * 20) + 60),
                'MASTER': Array.from({ length: categories.length }, () => Math.floor(Math.random() * 20) + 60),
                'RC': Array.from({ length: categories.length }, () => Math.floor(Math.random() * 20) + 60)
            };

            // Generate series data based on the selected region
            const series = [];
            if (filterRegion === 'all') {
                // Add all regions if "all" is selected
                for (const region in regionData) {
                    series.push({
                        name: region.toUpperCase(),
                        data: regionData[region]
                    });
                }
            } else if (regionData['region1']) {
                // Add only the selected region
                series.push({
                    name: filterRegion.toUpperCase(),
                    data: regionData['region1']
                });
            }

            // Update the AVA TM Harian chart with dummy data
            Highcharts.chart('chart-target-bulanan', {
                chart: {
                    type: 'line'
                },
                title: {
                    text: `Target Tahun ${filterYear}`,
                    style: {
                        fontSize: '12px', // Set the font size
                        fontWeight: 'normal' // Remove bold styling
                    }
                },
                xAxis: {
                    categories: categories
                },
                yAxis: {
                    title: {
                        text: 'Persentase (%)'
                    },
                    min: 0,
                    max: 100
                },
                series: series,
                plotOptions: {
                    series: {
                        cursor: 'pointer',
                        events: {
                            click: function (event) {
                                // Trigger loadData when a series is clicked
                                const seriesName = this.name; // Get the name of the clicked series
                                const category = categories[event.point.index]; // Get the clicked category (month)
                                console.log(`Series: ${seriesName}, Month: ${category}`);

                                // Call loadData with the series name and category
                                loadData(seriesName, category);
                            }
                        }
                    }
                }
            });


            // // Fetch data for AVA TM Harian chart
            // $.ajax({
            //     url: '/api/ava-tm-harian', // Replace with your API endpoint for AVA TM
            //     method: 'GET',
            //     data: {
            //         region: filterRegion,
            //         year: year,
            //         month: month
            //     },
            //     success: function (response) {
            //         const categories = response.days; // Example: ['1', '2', '3', ...]
            //         const data = response.values; // Example: [95, 92, 90, ...]

            //         // Update the AVA TM Harian chart
            //         Highcharts.chart('chart-ava-tm-harian', {
            //             chart: {
            //                 type: 'line'
            //             },
            //             title: {
            //                 text: 'AVA TM Harian'
            //             },
            //             xAxis: {
            //                 categories: categories
            //             },
            //             yAxis: {
            //                 title: {
            //                     text: 'Persentase (%)'
            //                 },
            //                 min: 0,
            //                 max: 100
            //             },
            //             series: [{
            //                 name: 'AVA TM',
            //                 data: data
            //             }]
            //         });
            //     },
            //     error: function (error) {
            //         console.error('Error fetching AVA TM data:', error);
            //         alert('Failed to fetch AVA TM data. Please try again.');
            //     }
            // });

            // // Fetch data for AVA TS Harian chart
            // $.ajax({
            //     url: '/api/ava-ts-harian', // Replace with your API endpoint for AVA TS
            //     method: 'GET',
            //     data: {
            //         region: filterRegion,
            //         year: year,
            //         month: month
            //     },
            //     success: function (response) {
            //         const categories = response.days; // Example: ['1', '2', '3', ...]
            //         const data = response.values; // Example: [85, 88, 90, ...]

            //         // Update the AVA TS Harian chart
            //         Highcharts.chart('chart-ava-ts-harian', {
            //             chart: {
            //                 type: 'line'
            //             },
            //             title: {
            //                 text: 'AVA TS Harian'
            //             },
            //             xAxis: {
            //                 categories: categories
            //             },
            //             yAxis: {
            //                 title: {
            //                     text: 'Persentase (%)'
            //                 },
            //                 min: 0,
            //                 max: 100
            //             },
            //             series: [{
            //                 name: 'AVA TS',
            //                 data: data
            //             }]
            //         });
            //     },
            //     error: function (error) {
            //         console.error('Error fetching AVA TS data:', error);
            //         alert('Failed to fetch AVA TS data. Please try again.');
            //     }
            // });
        }


        loadData = function (seriesName, category) {
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
                    { name: 'point_number', type: 'string' },
                    { name: 'point_type_id', type: 'string' },
                    { name: 'path1name', type: 'string' },
                    { name: 'path2name', type: 'string' },
                    { name: 'path3name', type: 'string' },
                    { name: 'path4name', type: 'string' },
                    { name: 'id_region', type: 'string' },
                    { name: 'nama_region', type: 'string' },
                    { name: 'hitung_kinerja', type: 'bool' },
                    { name: 'informasi', type: 'string' },
                    { name: 'status', type: 'integer' },
                ],
                url: '{{ route("cpoint.read") }}',
                cache: false,
                root: 'Rows',
                beforeprocessing: function(data) {
                    // appSettings = data.data.Rows; // Store appSettings data
                    // source.totalrecords = data.data.TotalRows;
                    if (data && data.data && data.data.Rows) {
                        appSettings = data.data.Rows;
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
                theme: 'custom',
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
                        text: '#', // Column header
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
                    { text: 'Region', datafield: 'nama_region', editable: false, width: 150, },
                    { text: 'B1 Name', datafield: 'path1name', editable: false},
                    { text: 'B2 Name', datafield: 'path2name', editable: false},
                    { text: 'B3 Name', datafield: 'path3name', editable: false},
                    { text: 'Element', datafield: 'path4name', editable: false},
                    {
                        text: 'Hitung Kinerja',
                        datafield: 'hitung_kinerja',
                        columntype: 'checkbox',
                        width: 100,
                        editable: false,
                        filtertype: 'bool',
                        cellendedit: function (row, datafield, columntype, oldvalue, newvalue) {
                            // newvalue = true/false
                            var nilai = newvalue ? 1 : 0;
                        }
                    },
                    { text: 'Status', datafield: 'status', editable: false,
                        width: 80,
                        cellsrenderer: function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
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
                        filterable: false, editable: false,
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

                $("#jqxGrid").jqxGrid('theme', 'darkblue');
                $("#jqxGrid").jqxGrid('render');
            });
        };

        $(document).ready(function() {

            // Initialize select2 controls
            $('.select2').select2();
            // Set default year to the current year
            const now = new Date();
            const currentYear = now.getFullYear();
            $('#filterBulanTahun').val(currentYear);


            // When the Bulan-Tahun filter changes
            $('#filterBulanTahun').on('change', function () {
                const selectedValue = $(this).val(); // Get the selected value (e.g., "2025-11")
                
                if (selectedValue) {
                    const year = selectedValue.split('-')[0]; // Extract the year
                    $('.headerBulan1').text(year); 
                } else {
                    // Reset the header text if no value is selected
                    $('.headerBulan1').text('-');
                }
            });

            loadCharts(); // Initial load with default filters
            $('#applyFilters').on('click', function () {

                loadCharts();
                
            });
            loadData();
            
        });
</script>
@endpush