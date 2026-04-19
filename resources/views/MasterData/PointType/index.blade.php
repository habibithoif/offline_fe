@extends('layouts.layouts_app')

@section('content')

<x-content-header 
    :title="$data->page['name']" 
    :breadcrumbs="[
        ['label' => 'Home', 'url' => '#'],
        ['label' => 'Master Data', 'url' => '#'],
        ['label' => $data->page['name'], 'url' => '#']
    ]" 
/>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
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
                    <div class="card-body">
                        <div class="form-group col-sm-2">
                            @if($data->accesses && in_array('create', $data->accesses))
                                <button type="button" class="btn btn-block btn-outline-primary btn-sm pull-right" onclick="tambahData()">
                                    Buat Baru
                                </button>
                            @endif
                        </div>
                        <hr>
                        <div id="treeGrid" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-info">
                        <h3 class="card-title">
                            <i class="fas fa-list-alt mr-2"></i>
                            Pointtype State
                        </h3>
                        <div class="card-tools">
                            @if($data->accesses && in_array('create', $data->accesses))
                                <button type="button" class="btn btn-xs btn-default" onclick="tambahDataDetail()">
                                    <i class="fas fa-plus"></i> Buat Baru
                                </button>
                                <button type="button" class="btn btn-xs btn-default ml-2" onclick="CopyPointState()">
                                    <i class="fas fa-copy"></i> Copy Point State
                                </button>
                            @endif

                            <button id="refreshButtonGrid1" class="btn btn-default btn-xs" title="Refresh">
                                <i class="fas fa-sync"></i>
                            </button>
                            <button id="listViewButtonGrid1" class="btn btn-default btn-xs" title="List View">
                                <i class="fas fa-list"></i>
                            </button>
                            <button id="downloadButtonGrid1" class="btn btn-default btn-xs" title="Download">
                                <i class="fas fa-download"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="treeGrid1"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-data" role="dialog" aria-labelledby="pointTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title" id="modal-data-title">Form Point Type</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form" id="form-data">
                <div class="modal-body">
                    <input type="hidden" class="form-control" name="id" id="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Nama Point</label>
                                <input type="text" class="form-control" name="name" id="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jenispoint">Jenis Point</label>
                                <select class="form-control select2" id="jenispoint" name="jenispoint" required>
                                    <option value="">Pilih Jenis Point</option>
                                    <option value="TELEMETERIGN">TELEMETERIGN</option>
                                    <option value="TELESIGNAL">TELESIGNAL</option>
                                    <option value="MASTER STATION">MASTER STATION</option>
                                    <option value="RTU">RTU</option>
                                    <!-- <option value="TELEKOMUNIKASI">TELEKOMUNIKASI</option> -->
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_induk_pointtype">Parent</label>
                                <select class="form-control select2" name="id_induk_pointtype" id="id_induk_pointtype">
                                    <option value="0">-- Pilih Parent --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <div class="d-flex align-items-center">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="status1" name="status" class="custom-control-input" value="1" checked>
                                        <label class="custom-control-label" for="status1">Aktif</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="status0" name="status" class="custom-control-input" value="0">
                                        <label class="custom-control-label" for="status0">Nonaktif</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_form_detail" role="dialog" aria-labelledby="pointTypeStateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title" id="title_detail">Form Pointtype State</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_detail">
                <div class="modal-body">
                    <input type="hidden" name="id_pointtype_state" id="id_pointtype_state">
                    <input type="hidden" name="id_pointtype" id="id_pointtype1">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="value">Value</label>
                                <input class="form-control" id="value" name="statekey" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quality_code">Quality Code</label>
                                <input name="quality_code" id="quality_code" type="text" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="state_label">State Label</label>
                                <input name="name" id="state_label" type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Normal/Up/Valid</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="validasi" name="valid" value="1">
                                    <label class="custom-control-label" for="validasi"></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status</label>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="status2" name="status" value="1" checked>
                                    <label class="custom-control-label" for="status2"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                    <button type="submit" class="btn btn-info"><i class="fas fa-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Copy Point State Modal -->
<div class="modal fade" id="modal_form_copy" role="dialog" aria-labelledby="copyPointStateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title" id="title_copy">Copy Point State</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="form_copy">
                <div class="modal-body">
                    <input type="hidden" name="id_pointtypetarget" id="id_pointtype2">
                    <div class="form-group">
                        <label for="pkey">Pointtype Source</label>
                        <select name="id_pointtypesource" id="pkey" class="form-control select2" required>
                            <option value="">Select Pointtype</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                    <button type="submit" class="btn btn-info"><i class="fas fa-copy"></i> Copy</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.content -->
@endsection

@push('scripts')
    <script>
        var userAccess = @json($data->accesses);
        var currentPath = window.location.pathname;
        var baseUrl = mainServerUrl + currentPath;
        var baseUrlDetail = mainServerUrl +  '/master-data/pointtypestate';
        var tableData         = [];
        var tableDataDetail       = [];
        var selectedId = '';

        function refresh(result) {
            alertSuccess(result.message);
            loadData();
            $('#modal-data').modal('hide');
        }

        function refresh1(result) {
            setgriddetail(selectedId);
            alertSuccess(result.message);
            $('#modal_form_detail').modal('hide');
        }

        function deleteDataTree(id) {
            Swal.fire({
                title: 'Warning!',
                text: 'Apakah Anda yakin ingin menghapus data ini?',
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
                            loadData(); // Reload menus after delete
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                        }
                    });
                }
            })
        }

        loadData = function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Create a data source
            var source = {
                datatype: "json",
                dataFields: [
                    { name: 'id', type: 'string' },
                    { name: 'name', type: 'string' },
                    { name: 'jenispoint', type: 'string' },
                    { name: 'no_urut', type: 'string' },
                    { name: 'status', type: 'string' },
                    { name: 'id_induk_pointtype', type: 'string' }
                ],
                hierarchy: {
                    keyDataField: { name: 'id' },
                    parentDataField: { name: 'id_induk_pointtype' }
                },
                id: 'id',
                url: baseUrl + '/read',
                method: 'POST',
                cache: false,
                root: 'Rows',
                beforeprocessing: function(data) {
                    tableData = data.data.Rows; // Store appSettings data
                    source.totalrecords = data.data.Rows.length; // Set the total records
                },
                sort: function() {
                    $("#treeGrid").jqxTreeGrid('updatebounddata', 'sort');
                },
                filter: function() {
                    $("#treeGrid").jqxTreeGrid('updatebounddata', 'filter');
                },
                deleterow: function (rowid, commit) {
                    var data = $("#treeGrid").jqxTreeGrid('getrowdata', rowid);
                    if (data && data.id) {
                        deleteData(data.id); // Use your existing deleteData function
                        commit(true); // You can skip this if you're handling refresh in your own flow
                    } else {
                        commit(false);
                    }
                },
                updaterow: function (rowid, newdata, commit) {
                    var data = $("#treeGrid").jqxTreeGrid('getrowdata', rowid);
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
            $("#treeGrid").jqxTreeGrid({
                width:  '100%',
                height: 500, // Set static height in pixels (adjust as needed)
                source: dataAdapter,
                sortable: true,
                ready: function () {
                    $("#treeGrid").jqxTreeGrid('expandRow', '2');
                },
                columns: [
                    { text: 'Nama', dataField: 'name', width: '30%'},
                    { text: 'Tipe Point', dataField: 'jenispoint'},
                    { text: 'No Urut', dataField: 'no_urut' },
                    { text: 'Status', dataField: 'status', width: '10%', cellsalign: 'center', columntype: 'template',
                        cellsrenderer: function(row, columnfield, value, rowData) {
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
                        text: 'Select',
                        datafield: 'id_induk_pointtype',
                        width: '8%',
                        align: 'center',
                        cellsalign: 'center',
                        columntype: 'template',
                        sortable: false,
                        filterable: false,
                        cellsrenderer: function (row, columnfield, value, rowData) {
                            return `<div style="text-align: center; padding-top: 5px; cursor: pointer;">
                                        <input type="radio" name="selectRadio" value="${rowData.id}" onclick="setgriddetail('${rowData.id}')">
                                    </div>`;
                        }
                    },
                    { 
                        text: 'Actions', 
                        datafield: 'id', 
                        width: '10%',
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
                            
                            // Add Delete button if user has delete access
                            if (userAccess && userAccess.includes('delete')) {
                                var deleteBtn = $(`<button class="btn btn-xs btn-danger" onclick="deleteDataTree('${value}')"><i class="fas fa-trash-alt"></i></button>`);
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
                sortable: true,
                filterable: true,
                theme: 'material'
            });

            $("#treeGrid").on("bindingcomplete", function () {
                console.log("Grid is ready!");

                $("#treeGrid").jqxTreeGrid('render');
            });
        };

        function detailData(id) {
            var pointType = tableData.find(function(item) {
                return item.id == id;
            });

            if (pointType) {
                urlAction = baseUrl + '/update';
                toggleForm('#form-data', true);
                resetForm('#form-data');
                
                renderIndukPointType('id_induk_pointtype');
                $('#id').val(pointType.id);
                $('#name').val(pointType.name);
                $('#jenispoint').val(pointType.jenispoint).trigger('change');
                $('#id_induk_pointtype').val(pointType.id_induk_pointtype).trigger('change');
                $('#status' + pointType.status).prop('checked', true);
                $('#modal-data-title').text('Edit Point Type');
                $('#modal-data').modal('show');
            } else {
                console.error('Point Type not found');
            }
        }

        function tambahData() {
            resetForm('#form-data');
            $('#form-data').validate().resetForm();
            urlAction = baseUrl + '/store';
            renderIndukPointType('id_induk_pointtype')

            toggleForm('#form-data', true);
            $('#modal-data').modal('show');
        }
        
        function renderIndukPointType(id) {
            var options = '<option value="0">-- Pilih Parent --</option>';
            $.each(tableData, function(index, item) {
                options += '<option value="' + item.id + '">' + item.name + '</option>';
            });
            $('#' + id).html(options);
         
        }

        function tambahDataDetail() {
            if(selectedId.length > 0){
                resetForm('#form_detail');
                $('#form_detail').validate().resetForm();
                $('#id_pointtype1').val(selectedId);
                urlAction = baseUrlDetail+'/store';
                $('#modal_form_detail').modal('show');
                $('#title_detail').text('Tambah Data State'); // Set Title to Bootstrap modal title
            }else{
                alert('Silahkan Pilih Pointtype Terlebih Dahulu');
            }
        }

        function CopyPointState() {
            if(selectedId.length > 0){
                resetForm('#form_copy');
                $('#form_copy').validate().resetForm();
                renderIndukPointType('pkey')
                $('#id_pointtype2').val(selectedId);
                urlAction = baseUrlDetail+'/copy';
                $('#modal_form_copy').modal('show'); // show bootstrap modal
                $('#title_copy').text('Pilih Pointtype yang akan di copy'); // Set Title to Bootstrap modal title
            }else{
                alert('Silahkan Pilih Pointtype Terlebih Dahulu');
            }
        }

        function detailDataDetail(id) {
            urlAction = baseUrlDetail+'/update';
            toggleForm('#form_detail', true);
            resetForm('#form_detail');
            var dataRecord = $("#treeGrid1").jqxGrid('getrowdata',id);
            $('#id_pointtype1').val(dataRecord.id_pointtype);
            $('#id_pointtype_state').val(dataRecord.id_pointtype_state);
            $('#value').val(dataRecord.statekey);
            $('#quality_code').val(dataRecord.quality_code);
            $('#state_label').val(dataRecord.name);
            if(dataRecord.valid = 0){
                $("#validasi").prop("checked", false);
            }else{
                $("#validasi").prop("checked", true);
            }
            if(dataRecord.status = 1){
                $("#status2").prop("checked", true);
            }else{
                $("#status2").prop("checked", false);
            }
            $('#modal_form_detail').modal('show');
        }

        function renderActionDetail(row, column, value, defaultHtml, columnSettings, rowData) {
            var button = '';
            if (userAccess && userAccess.includes("update")) {
                button = '<button type="button" class="btn btn-info btn-xs pull-right" onclick="detailDataDetail(\'' + row + '\')"><i class="fa fa-pen"></i> Edit</button>';
            }
            if (userAccess && userAccess.includes("delete")) {
                button += '<button type="button" class="btn btn-danger btn-xs pull-right" onclick="deleteData(\'' + rowData.id_pointtype_state + '\', \'' + baseUrlDetail + '/'+ rowData.id_pointtype_state +'\',refresh1)"><i class="fa fa-trash"></i> Hapus</button>';
            }
            return button;
        }

        function renderactive(data, type, row) {
            if (row == true || row == 1) {
                return '<small class="badge badge-success">Aktif</small>';
            }
            return '<small class="badge badge-danger">Nonaktif</small>';
        }

        var tableDataDetail = [];
        var dataAdapterDetail; // Make dataAdapter global to access it later
        function setgriddetail(id) {
            // Store the selected ID and check if it's valid
            selectedId = id;
            if (!selectedId) {
                console.error("No point type ID provided");
                return;
            }
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            // Create data source only if it doesn't exist yet
            if (!window.detailGridSource) {
                window.detailGridSource = {
                    datatype: "json",
                    datafields: [
                        { name: 'id_pointtype_state', type: 'string' },
                        { name: 'name', type: 'string' },
                        { name: 'quality_code', type: 'string' },
                        { name: 'statekey', type: 'string' },
                        { name: 'valid', type: 'bool' },
                        { name: 'status', type: 'bool' },
                        { name: 'id_pointtype', type: 'string' }
                    ],
                    id: 'id_pointtype_state',
                    url: baseUrlDetail + '/read',
                    root: 'data',
                    beforeprocessing: function(data) {
                        tableDataDetail = data.data;
                        window.detailGridSource.totalrecords = data.total;
                    }
                };
                
                // Create data adapter only once
                dataAdapterDetail = new $.jqx.dataAdapter(window.detailGridSource, {
                    formatData: function(data) {
                        // Always include the current selected ID with each request
                        return {
                            ...data,
                            id_pointtype: selectedId
                        };
                    }
                });
                
                // Initialize the grid only once
                $("#treeGrid1").jqxGrid({
                    width: '100%',
                    height: 500,
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
                        { text: 'Value', datafield: 'statekey', width: 250 },
                        { text: 'Quality Code', datafield: 'quality_code', width: 100 },
                        { text: 'State Label', datafield: 'name', width: 100 },
                        { text: 'Validasi', datafield: 'valid', width: 100, 
                        cellsrenderer: function(row, column, value) {
                            return value ? '<span class="badge badge-success">Yes</span>' : 
                                            '<span class="badge badge-secondary">No</span>';
                        }
                        },
                        { text: 'Status', datafield: 'status', width: 100, 
                        align: "center", cellsAlign: 'center', cellsRenderer: renderactive },
                        { text: 'Action', cellsAlign: 'center', align: "center", 
                        columnType: 'none', editable: false, sortable: false, 
                        dataField: null, width: 200, cellsRenderer: renderActionDetail }
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
            } else {
                // Just update the data and refresh if the grid is already initialized
                dataAdapterDetail.dataBind();
                $("#treeGrid1").jqxGrid('updatebounddata');
            }
        }

        function renderGridDetail(id) {
            // Use the passed id parameter or fallback to selectedId if not provided
            var pointTypeId = id || selectedId;
            
            if (!pointTypeId) {
                console.error("No point type selected");
                return;
            }
            
            // Update the dataAdapter with new parameters
            var filterParams = {
                id_pointtype: pointTypeId
            };
            
            // Set the data parameter on the source
            dataAdapterDetail._source.data = filterParams;
            
            // Clear previous data and refresh the grid
            dataAdapterDetail.dataBind();
            $("#treeGrid1").jqxGrid('updatebounddata');
        }

        $(document).ready(function () {
            loadData(); // Initial load of menus

            $('#refreshButton').on('click', function() {
                loadData();
            });

            $('#downloadButton').on('click', function() {
                // $("#treeGrid").jqxTreeGrid('exportdata', 'xlsx', 'TelemetryData');
            });

            $('#listViewButton').on('click', function() {
                console.log("List view toggle clicked");
            });

            $('#refreshButtonGrid1').on('click', function() {
                renderGridDetail(selectedId);
            });

            $('#listViewButtonGrid1').on('click', function() {
                console.log("List view toggle clicked");
            });
            
            $('#downloadButtonGrid1').on('click', function() {
                // $("#treeGrid1").jqxGrid('exportdata', 'xlsx', 'TelemetryData');
            });

            $('.select2').select2({
                theme: 'bootstrap4',
            });

            $('#form-data').validate({
                rules: {
                    jenispoint: {
                        required: true
                    },
                    name: {
                        required: true,
                        minlength: 3
                    }
                },
                messages: {
                    name: {
                        required: "Kolom Nama wajib diisi.",
                        minlength: "Nama harus terdiri dari minimal 3 karakter."
                    },
                    jenispoint: {
                        required: "Kolom Jenis Point wajib diisi."
                    }
                },
                errorElement: "div",
                errorClass: "text-danger",
                submitHandler:function (form) {
                    var reqData = new FormData(form);
                    ajaxData(urlAction, reqData, refresh, true);
                }
            });

            $('#form_detail').validate({
                rules: {
                    id_pointstate: {
                        required: true
                    },
                    name: {
                        required: true,
                        minlength: 3
                    },
                    quality_code: {
                        required: true
                    }
                },
                messages: {
                    id_pointstate: {
                        required: "Kolom ID Point Type wajib diisi."
                    },
                    name: {
                        required: "Kolom State Key wajib diisi.",
                        minlength: "Nama harus terdiri dari minimal 3 karakter."
                    },
                    quality_code: {
                        required: "Kolom Quality Code wajib diisi."
                    }
                },
                errorElement: "div",
                errorClass: "text-danger",
                submitHandler:function (form) {
                    var reqData = new FormData(form);
                    ajaxData(urlAction, reqData, refresh1, true);
                }
            });

            $('#form_copy').validate({
                rules: {
                    id_pointstatesource: {
                        required: true
                    },
                    id_pointstatetarget: {
                        required: true
                    }
                },
                messages: {
                    id_pointstatesource: {
                        required: "Kolom ID Point Type State Source wajib diisi."
                    },
                    id_pointstatetarget: {
                        required: "Kolom ID Point Type State Target wajib diisi."
                    }
                },
                errorElement: "div",
                errorClass: "text-danger",
                submitHandler:function (form) {
                    var reqData = new FormData(form);
                    ajaxData(urlAction, reqData, refresh1, true);
                }
            });
        });
    </script>
@endpush
