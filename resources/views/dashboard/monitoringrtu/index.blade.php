@extends('layouts.layouts_app')

@section('content')

<x-content-header 
    :title="$data->page['name']" 
    :breadcrumbs="[
        ['label' => 'Home', 'url' => '#'],
        ['label' => 'Dashboard', 'url' => '#'],
        ['label' => 'Monitoring RTU', 'url' => '#'],
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
                                    <label>Unit</label>
                                    <select class="form-control form-control-sm select2" id="filterTipePoint" style="width: 100%;">
                                        <option value="">--Pilih Unit--</option>
                                        @foreach ($data->ref_region as $item)
                                            <option value="{{ $item['id_region'] }}">{{ $item['nama'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="date" id="startDate" class="form-control form-control-sm">
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
            
        }

        function resetFilters() {
            $('.select2').val('').trigger('change');
        }

        $(document).ready(function() {

            // Initialize select2 controls
            $('.select2').select2();

            // Set up event handlers
            $('#applyFilters').on('click', applyCustomFilters);
            $('#resetFilters').on('click', resetFilters);

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
                            field: 'path1'
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
                            field: 'path2' 
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
                            field: 'path3' 
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
                            field: 'path4' 
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
                            field: 'path5' 
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

            // Apply filters button
            $('#applyFilters').on('click', function() {
                applyCustomFilters();
            });
            
            // Reset filters button
            $('#resetFilters').on('click', function() {
                resetFilters();
            });
            
            // List view button (toggle view or implement custom view)
            $('#listViewButton').on('click', function() {
                // Implement custom view toggle here
                console.log("List view toggle clicked");
            });
        });
    </script>
@endpush