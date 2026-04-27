@extends('layouts.layouts_app')

@section('content')

<x-content-header 
    :title="$data->page['name']" 
    :breadcrumbs="[
        ['label' => 'Home', 'url' => '#'],
        ['label' => 'Settings', 'url' => '#'],
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
<!-- /.content -->

<div class="modal fade" id="modal-data" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="form" id="form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="modal-data-title">Form Setting Menu</h3>
                </div>
                <div class="modal-body">
                    <input type="hidden" class="form-control form-control-sm text-sm" name="id" id="id">
                    <div class="row">
                        <div class="form-group col-md-12 form-hide">
                            <label for="display">Display</label>
                            <input type="text" class="form-control form-control-sm text-sm" name="display" id="display" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12 form-hide">
                            <label for="name">Nama Menu</label>
                            <input type="text" class="form-control form-control-sm text-sm" name="name" id="name" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12 form-hide">
                            <label for="path">Path</label>
                            <input type="text" class="form-control form-control-sm text-sm" name="path" id="path" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12 form-hide">
                            <label for="icon">Icon</label>
                            <select class="form-control form-control-sm text-sm select2-icon" name="icon" id="icon">
                                <option value="">-- Pilih Icon --</option>
                                @foreach($data->icons as $icon)
                                    <option value="{{ $icon['label'] }}" data-icon="{{ $icon['label'] }}">{{ $icon['label'] }}</option>
                                @endforeach
                            </select>                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12 form-hide">
                            <label for="no">Urutan</label>
                            <input type="number" class="form-control form-control-sm text-sm" name="no" id="no" min="0">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12 form-hide">
                            <label for="parent_id">Parent</label>
                            <select class="form-control text-sm select2" name="parent_id" id="parent_id">
                                <option value="0">-- Pilih Parent --</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12 form-hide">
                            <label for="akses">Akses</label>
                            <select class="form-control text-sm select2access" multiple="multiple" name="akses[]" id="akses" data-placeholder="Select Akses" style="width: 100%;">
                                <option value="view">view</option>
                                <option value="create">create</option>
                                <option value="update">update</option>
                                <option value="delete">delete</option>
                                <option value="akses_menu">akses menu</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Keluar</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
    <script>
        var userAccess = @json($data->accesses);
        var baseUrl       = mainServerUrl +  '/setting/menu';
        var tableData         = [];

        function refresh(result) {
            alertSuccess(result.message);
            loadData();
            $('#modal-data').modal('hide');
        }

        function buildTree(data, parentId = 0) {
            let tree = [];

            data.forEach(item => {
                if (item.parent_id === parentId) {
                    let children = buildTree(data, item.id);
                    if (children.length) {
                        item.children = children;
                    }
                    tree.push(item);
                }
            });

            return tree;
        }

        function renderOptions(select, items, selectedId = null, level = 0) {
            let counter = 1;
            items.forEach(item => {
                let numberPrefix = `${item.no}. `;
                let indent = '&nbsp;'.repeat(level * 8); // each level gets more indent
                let selected = item.id === selectedId ? 'selected' : '';
                select.append(`<option value="${item.id}" ${selected}>${indent}${numberPrefix}${item.display}</option>`);

                if (item.children) {
                    renderOptions(select, item.children, selectedId, level + 1);
                }
                counter++;
            });
        }

        function get_parent(selectedId = null) {
            $.ajax({
                url: baseUrl + '/read_all',
                type: "GET",
                contentType: "application/json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    let data = response.data;

                    // Convert flat data to tree
                    const tree = buildTree(data);

                    // Render tree as options
                    let select = $('#parent_id');
                    select.html('<option value="0">-- Pilih Parent --</option>');
                    renderOptions(select, tree, selectedId);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }

        function deleteData(id) {
            if (confirm('Are you sure you want to delete this menu?')) {
                $.ajax({
                    url: baseUrl + '/delete/' + id,
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
                dataFields: [
                    { name: 'id', type: 'number' },
                    { name: 'parent_id', type: 'number' },
                    { name: 'display', type: 'string' },
                    { name: 'name', type: 'string' },
                    { name: 'path', type: 'string' },
                    { name: 'icon', type: 'string' }
                ],
                hierarchy: {
                    keyDataField: { name: 'id' },
                    parentDataField: { name: 'parent_id' }
                },
                id: 'id',
                url: baseUrl + '/read_all',
                method: 'POST',
                cache: false,
                root: 'data',
                beforeprocessing: function(data) {
                    tableData = data.data; // Store appSettings data
                    source.totalrecords = data.data.length; // Set the total records
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
                source: dataAdapter,
                sortable: true,
                ready: function () {
                    $("#treeGrid").jqxTreeGrid('expandRow', '2');
                },
                columns: [
                    { text: 'Nama Menu', dataField: 'display', width: '30%',
                        cellsrenderer: function (row, columnfield, value, rowData) {
                            return `&nbsp;<i class="${rowData.icon}"></i> &nbsp; ${value}`;
                        }
                    },
                    { text: 'Path', dataField: 'path', width: '30%' },
                    { text: 'Icon', dataField: 'icon', width: '30%', 
                        cellsrenderer: function (row, columnfield, value, rowData) {
                            return `<i class="${value}">${value}</i>`;
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

                            if (userAccess && userAccess.includes("akses_menu")) {
                                var accessButton = $(`<button class="btn btn-xs btn-warning" onclick="AccessMenu('${value}')"><i class="fas fa-key"></i></button>`);
                                container.append(accessButton);
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
            var menuItem = tableData.find(function(item) {
                return item.id == id;
            });

            if (menuItem) {
                urlAction = baseUrl + '/update';

                toggleForm('#form-data', true);
                resetForm('#form-data');

                $('#id').val(menuItem.id);
                $('#name').val(menuItem.name);
                $('#display').val(menuItem.display);
                $('#path').val(menuItem.path);
                $('#icon').val(menuItem.icon);
                $('#no').val(menuItem.no);
                get_parent(menuItem.parent_id);
                $('#akses').val(JSON.parse(menuItem.akses)).trigger('change');
                $('#icon').val(menuItem.icon).trigger('change');

                $('#modal-data').modal('show');
            } else {
                console.error('Menu item not found');
            }
        }

        function tambahData() {
            resetForm('#form-data');
            $('#form-data').validate().resetForm();

            get_parent();

            urlAction = baseUrl + '/store';
            toggleForm('#form-data', true);
            $('#modal-data').modal('show');
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

            $('.select2').select2({
                theme: 'bootstrap4',
                tags: true
            });

            $('.select2access').select2({
                theme: 'bootstrap4',
                tags: true,
                placeholder: "Select Akses",
                allowClear: true
            });

            $('#akses').on('change', function() {
                let selectedValues = $(this).val();

                if (selectedValues.includes("0")) {
                    selectedValues = selectedValues.filter(value => value !== "0");
                    $(this).val(selectedValues).trigger('change');
                }
            });

            $('#form-data').validate({
                rules: {
                    display: { required: true },
                    name: { required: true, minlength: 3 },
                    path: { required: true },
                    icon: { required: true },
                    no: { required: true }
                },
                messages: {
                    name: { required: "Kolom Nama wajib diisi.", minlength: "Nama harus terdiri dari minimal 3 karakter." },
                    display: { required: "Kolom Tampilan wajib diisi." },
                    path: { required: "Kolom Path wajib diisi." },
                    icon: { required: "Kolom Icon wajib diisi." },
                    no: { required: "Kolom Urutan wajib diisi." }
                },
                errorElement: "div",
                errorClass: "text-danger",
                submitHandler: function (form) {
                    var reqData = new FormData(form);
                    ajaxData(urlAction, reqData, refresh, true);
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.select2-icon').select2({
                templateResult: formatIcon,
                templateSelection: formatIcon,
                escapeMarkup: function(markup) { return markup; }
            });
    
            function formatIcon (icon) {
                if (!icon.id) {
                    return icon.text;
                }
                var $icon = $(
                    '<span><i class="' + $(icon.element).data('icon') + '"></i> ' + icon.text + '</span>'
                );
                return $icon;
            }
        });
    </script>
    
@endpush
