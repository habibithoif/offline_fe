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
                                                    <option value="{{ $item['id'] }}">{{ $item['nama'] }}</option>
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
                    <div class="col-md-6">
                        <div class="card shadow-sm rounded">
                            <div class="card-header bg-info text-white d-flex flex-column justify-content-center align-items-center">
                                <h3 class="card-title mb-1">AVA TM TAHUN : <span class="headerBulan1">-</span></h3>
                            </div>
                            <div class="card-body p-3">
                                <div id="chart-ava-tm-harian" style="width: 100%; height: 400px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm rounded">
                            <div class="card-header bg-info text-white d-flex flex-column justify-content-center align-items-center">
                                <h3 class="card-title mb-1">AVA TS TAHUN : <span class="headerBulan1">-</span></h3>
                            </div>
                            <div class="card-body p-3">
                                <div id="chart-ava-ts-harian" style="width: 100%; height: 400px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card shadow-sm rounded">
                            <div class="card-header bg-info text-white d-flex flex-column justify-content-center align-items-center">
                                <h3 class="card-title mb-1">AVA RTU TAHUN : <span class="headerBulan1">-</span></h3>
                            </div>
                            <div class="card-body p-3">
                                <div id="chart-ava-rtu-harian" style="width: 100%; height: 400px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm rounded">
                            <div class="card-header bg-info text-white d-flex flex-column justify-content-center align-items-center">
                                <h3 class="card-title mb-1">AVA MASTER TAHUN : <span class="headerBulan1">-</span></h3>
                            </div>
                            <div class="card-body p-3">
                                <div id="chart-ava-master-harian" style="width: 100%; height: 400px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card shadow-sm rounded">
                            <div class="card-header bg-info text-white d-flex flex-column justify-content-center align-items-center">
                                <h3 class="card-title mb-1">AVA RC TAHUN : <span class="headerBulan1">-</span></h3>
                            </div>
                            <div class="card-body p-3">
                                <div id="chart-ava-rc-harian" style="width: 100%; height: 400px;"></div>
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
                'region1': Array.from({ length: categories.length }, () => Math.floor(Math.random() * 20) + 80), // Random data
                'region2': Array.from({ length: categories.length }, () => Math.floor(Math.random() * 20) + 70),
                'region3': Array.from({ length: categories.length }, () => Math.floor(Math.random() * 20) + 60)
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
            Highcharts.chart('chart-ava-tm-harian', {
                chart: {
                    type: 'line'
                },
                title: {
                    text: `AVABILITY TM <br>Tahun ${filterYear}`,
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
                series: series
            });

            // Update the AVA TS Harian chart with dummy data
            Highcharts.chart('chart-ava-ts-harian', {
                chart: {
                    type: 'line'
                },
                title: {
                    text: `AVABILITY TS <br>Tahun ${filterYear}`,
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
                series: series
            });

            // Update the AVA TS Harian chart with dummy data
            Highcharts.chart('chart-ava-rtu-harian', {
                chart: {
                    type: 'line'
                },
                title: {
                    text: `AVABILITY RTU <br>Tahun ${filterYear}`,
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
                series: series
            });

            // Update the AVA TS Harian chart with dummy data
            Highcharts.chart('chart-ava-master-harian', {
                chart: {
                    type: 'line'
                },
                title: {
                    text: `AVABILITY MASTER <br>Tahun ${filterYear}`,
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
                series: series
            });

            // Update the AVA TS Harian chart with dummy data
            Highcharts.chart('chart-ava-rc-harian', {
                chart: {
                    type: 'line'
                },
                title: {
                    text: `AVABILITY RC <br>Tahun ${filterYear}`,
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
                series: series
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
            
        });
</script>
@endpush