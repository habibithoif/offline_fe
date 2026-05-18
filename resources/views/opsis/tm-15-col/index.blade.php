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
                                    <label for="startDate">Tanggal</label>
                                    <input type="date" id="startDate" class="form-control form-control-sm">
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

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm rounded">
                    <div class="card-header bg-info text-white">
                        <h3 class="card-title mb-0">OPSIS - TELEMETERING HARIAN 15 MENIT</h3>
                        <div class="card-tools">
                            <button id="refreshButton" class="btn btn-default btn-sm" title="Refresh">
                                <i class="fas fa-sync"></i>
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
                    <label for="tanggal">Tanggal</label>
                    <input type="date" id="tanggal" class="form-control form-control-sm" required>
                </div>
                <div class="form-group col-3">
                    <label>Jam Awal</label>
                    <select class="form-control form-control-sm select2" style="width: 100%;" name="jam_awal" id="jam_awal" required></select>
                </div>

                <div class="form-group col-3">
                    <label>Jam Akhir</label>
                    <select class="form-control form-control-sm select2" style="width: 100%;" name="jam_akhir" id="jam_akhir" required></select>
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
        var tableData = [];
        var dataAdapter;

        function generate15minFields() {
            var fields = [];
            for (var h = 0; h <= 24; h++) {
                for (var m = 0; m < 60; m += 15) {
                    if (h === 24 && m > 0) break;
                    var hh = String(h).padStart(2, '0');
                    var mm = String(m).padStart(2, '0');
                    var name = 'm' + hh + mm;
                    if (name === 'm0000') continue;
                    fields.push({ name: name });
                }
            }
            return fields;
        }

        function generate15minColumns() {
            var cols = [];
            for (var h = 0; h <= 24; h++) {
                for (var m = 0; m < 60; m += 15) {
                    if (h === 24 && m > 0) break;
                    var hh = String(h).padStart(2, '0');
                    var mm = String(m).padStart(2, '0');
                    var name = 'm' + hh + mm;
                    if (name === 'm0000') continue;
                    cols.push({
                        text: hh + ':' + mm,
                        datafield: name,
                        width: 100,
                        cellsalign: 'center'
                    });
                }
            }
            return cols;
        }

        function generateTimeOptions(selectId) {
            const select = document.getElementById(selectId);
            select.innerHTML = '';

            for (var h = 0; h <= 24; h++) {
                for (var m = 0; m < 60; m += 15) {
                    if (h === 24 && m > 0) break;
                    var hh = String(h).padStart(2, '0');
                    var mm = String(m).padStart(2, '0');
                    var time = hh + ':' + mm;
                    if (time === '00:00') continue;
                    var opt = document.createElement('option');
                    opt.value = time;
                    opt.text = time;
                    select.appendChild(opt);
                }
            }
        }

        function refresh(result) {
            alertSuccess(result.message);
            $('#modal-data').modal('hide');
        }

        function initializeGrid(filterParams = {}) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var baseFields = [
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
                { name: 'datum', type: 'string' }
            ];
            var timeFields = generate15minFields();
            var allFields = baseFields.concat(timeFields);

            var baseColumns = [
                { text: 'No', width: 50, cellsalign: 'center', align: 'center',
                    cellsrenderer: function (row) {
                        return "<div style='padding: 5px;'>" + (row + 1) + "</div>";
                    }
                },
                { text: 'Region', datafield: 'nama_region', width: 200 },
                { text: 'Tanggal', datafield: 'datum', width: 200 },
                { text: 'B1 Name', datafield: 'b1_name', width: 200 },
                { text: 'B2 Name', datafield: 'b2_name', width: 100 },
                { text: 'B3 Name', datafield: 'b3_name', width: 200 },
                { text: 'Element Name', datafield: 'el_name', width: 100 },
                { text: 'Info Name', datafield: 'info_name', width: 100 }
            ];
            var timeColumns = generate15minColumns();
            var allColumns = baseColumns.concat(timeColumns);

            var source = {
                datatype: "json",
                datafields: allFields,
                url: '{{ route("opsis.tm-15-col.read") }}',
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

            dataAdapter = new $.jqx.dataAdapter(source);

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
                columns: allColumns,
                pagermode: 'default',
                pagesize: 10,
                pagesizeoptions: ['5', '10', '20', '50'],
                sortable: true,
                filterable: true,
                showfilterrow: true,
                filtermode: 'excel',
                theme: 'metro'
            });
        }

        function refreshGrid(filterParams = {}) {
            dataAdapter.data = filterParams;
            dataAdapter._source.data = filterParams;
            dataAdapter.dataBind();
        }

        let now = new Date().toISOString().slice(0, 10);
        $('#startDate').val(now);

        function applyCustomFilters() {
            var filterParams = {
                startDate: $('#startDate').val(),
                id_region: $('#filterRegion').val(),
                lokasi: $('#filterLokasi').val(),
                tegangan: $('#filterTegangan').val(),
                bay: $('#filterBay').val(),
                element: $('#filterElement').val(),
                info: $('#filterInfo').val(),
            };
            refreshGrid(filterParams);
        }

        function resetFilters() {
            $('.select2').val('').trigger('change');
            refreshGrid();
        }

        $(document).ready(function() {
            let filterParams = {
                startDate: $('#startDate').val(),
                id_region: $('#filterRegion').val(),
                lokasi: $('#filterLokasi').val(),
                tegangan: $('#filterTegangan').val(),
                bay: $('#filterBay').val(),
                element: $('#filterElement').val(),
                info: $('#filterInfo').val(),
            };
            initializeGrid(filterParams);

            generateTimeOptions('jam_awal');
            generateTimeOptions('jam_akhir');

            document.getElementById('jam_akhir').addEventListener('change', function() {
                let awal = document.getElementById('jam_awal').value;
                let akhir = this.value;
                if (akhir <= awal) {
                    alert('Jam akhir harus lebih besar dari jam awal');
                    this.value = '';
                }
            });
        });

        $('#applyFilters').on('click', function() {
            applyCustomFilters();
        });

        $('#resetFilters').on('click', function() {
            resetFilters();
        });

        $('#refreshButton').on('click', function() {
            $("#jqxGrid").jqxGrid('updatebounddata');
        });

        $('#downloadButton').on('click', function() {
            resetForm('#form-data');
            $('#form-data').validate().resetForm();
            let tgl = new Date($('#startDate').val()).toISOString().slice(0, 10);
            $('#tanggal').val(tgl);
            $('.is-invalid').removeClass('is-invalid');
            $('#modalDataLabel').text('Download Data');
            urlAction = baseUrl + '/download';
            actionMethod = 'GET';
            toggleForm('#form-data', true);
            $('#modal-data').modal('show');
        });

        function generateTimeHeadersByRange(jamAwal, jamAkhir) {
            function toField(jam) {
                return 'm' + jam.replace(':', '');
            }

            function next15Menit(jam) {
                let [h, m] = jam.split(':').map(Number);
                m += 15;
                if (m === 60) { m = 0; h += 1; }
                return String(h).padStart(2, '0') + ':' + String(m).padStart(2, '0');
            }

            let headers = [];
            let current = jamAwal;
            let safety = 0;

            while (true) {
                headers.push({ field: toField(current), label: current });
                if (current === jamAkhir) break;
                current = next15Menit(current);
                safety++;
                if (safety > 200) { console.error('Loop tidak berhenti'); break; }
            }
            return headers;
        }

        $('#form-data').validate({
            rules: {
                tanggal: { required: true },
                jam_awal: { required: true },
                jam_akhir: { required: true }
            },
            messages: {
                tanggal: { required: "Kolom Tanggal wajib diisi." },
                jam_awal: { required: "Kolom jam awal wajib diisi." },
                jam_akhir: { required: "Kolom jam akhir wajib diisi." }
            },
            submitHandler: function (form) {
                const formData = new FormData(form);
                const jam_awal = formData.get('jam_awal');
                const jam_akhir = formData.get('jam_akhir');

                const baseHeaders = [
                    { field: 'datum', label: 'Tanggal' },
                    { field: 'b1_name', label: 'B1 Name' },
                    { field: 'b2_name', label: 'B2 Name' },
                    { field: 'b3_name', label: 'B3 Name' },
                    { field: 'el_name', label: 'Element Name' },
                    { field: 'info_name', label: 'Info Name' }
                ];

                const timeHeaders = generateTimeHeadersByRange(jam_awal, jam_akhir);
                const headers = [...baseHeaders, ...timeHeaders];
                exportGridAll('#jqxGrid', 'tm_harian_15m', 'xlsx', headers);
                $('#modal-data').modal('hide');
            }
        });
    </script>
@endpush
