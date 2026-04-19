@extends('layouts.layouts_app')

@section('content')

<x-content-header 
    :title="$data->page['name']" 
    :breadcrumbs="[
        ['label' => 'Home', 'url' => '#'],
        ['label' => 'Setting', 'url' => '#'],
        ['label' => $data->page['name'], 'url' => '#']
    ]" 
/>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-key mr-2"></i>
                            Role Access Permissions
                        </h3>
                    </div>
                    <form class="form" id="form-data-access">
                        <div class="card-body">
                            <input type="hidden" name="role_id" id="role_id" value="{{ $data->role_id }}">
                            
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="checkbox_all">
                                    <label for="checkbox_all" class="custom-control-label font-weight-bold">
                                        Select All Permissions
                                    </label>
                                </div>
                            </div>
                            <hr>
                            
                            <div id="accessTreeGrid"></div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="button" class="btn btn-default mr-2" onclick="window.history.back();">
                                <i class="fas fa-arrow-left mr-1"></i> Back
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
    .jqx-tree-grid {
        border: none !important;
    }
    .jqx-grid-cell {
        padding: 8px 5px !important;
    }
    .permission-item {
        padding-left: 20px;
    }
    .menu-icon {
        margin-right: 8px;
        color: #6c757d;
    }
    .jqx-checkbox-check-indeterminate {
        opacity: 0.5;
    }
</style>
@endpush

@push('scripts')
<script>
    var role_id = {{ $data->role_id }};
    var accessTreeGrid;

    // Recursive function to build the tree data
    function buildTreeData(menus) {
        var data = [];
        
        menus.forEach(menu => {
            // Add menu item
            data.push({
                id: 'menu_' + menu.id,
                parentid: menu.parent_id ? 'menu_' + menu.parent_id : null,
                name: `<i class="${menu.icon || 'fas fa-folder'} menu-icon"></i>${menu.display}`,
                value: menu.id,
                level: 0,
                checked: false,
                hasChildren: (menu.children && menu.children.length > 0) || (menu.akses && JSON.parse(menu.akses).length > 0)
            });

            // Add access permissions as children
            if (menu.akses) {
                JSON.parse(menu.akses).forEach(akses => {
                    data.push({
                        id: 'akses_' + menu.id + '_' + akses,
                        parentid: 'menu_' + menu.id,
                        name: `<span class="permission-item"><i class="fas fa-lock menu-icon"></i>${akses}</span>`,
                        value: `${menu.id}|${akses}`,
                        level: 1,
                        checked: false,
                        hasChildren: false
                    });
                });
            }

            // Add children menus recursively
            if (menu.children && menu.children.length > 0) {
                data = data.concat(buildTreeData(menu.children));
            }
        });
        
        return data;
    }

    // Load selected permissions based on role
    function loadSelectedPermissions() {
        $.ajax({
            url: '/setting/roles/read_access/' + role_id,
            type: "GET",
            success: function (response) {
                var checkedAccesses = [];

                response.data.forEach(item => {
                    item.akses?.forEach(akses => {
                        checkedAccesses.push('akses_' + item.menu_id + '_' + akses);
                    });
                });

                function checkMatchingRows(rows) {
                    rows.forEach(row => {
                        if (checkedAccesses.includes(row.id)) {
                            $('#accessTreeGrid').jqxTreeGrid('checkRow', row.uid);
                        }

                        if (row.records && row.records.length > 0) {
                            checkMatchingRows(row.records);
                        }
                    });
                }

                const rows = $('#accessTreeGrid').jqxTreeGrid('getRows');
                checkMatchingRows(rows);
            }
        });
    }

    // Update parent checkbox state based on child checkboxes
    function updateParentCheckboxes() {
        var rows = $('#accessTreeGrid').jqxTreeGrid('getRows');
        
        // Reset all parent states
        rows.forEach(row => {
            if (row.hasChildren) {
                var childRows = getChildRows(row.id);
                var allChecked = true;
                var anyChecked = false;
                
                childRows.forEach(child => {
                    var childRow = $('#accessTreeGrid').jqxTreeGrid('getRow', child.uid);
                    if (childRow.checked === true) {
                        anyChecked = true;
                    } else if (childRow.checked === null) {
                        anyChecked = true;
                        allChecked = false;
                    } else {
                        allChecked = false;
                    }
                });
                
                if (allChecked && childRows.length > 0) {
                    $('#accessTreeGrid').jqxTreeGrid('checkRow', row.uid);
                } else if (anyChecked) {
                    $('#accessTreeGrid').jqxTreeGrid('indeterminateRow', row.uid);
                } else {
                    $('#accessTreeGrid').jqxTreeGrid('uncheckRow', row.uid);
                }
            }
        });
    }

    // Get child rows based on parent ID
    function getChildRows(parentId) {
        return $('#accessTreeGrid').jqxTreeGrid('getRows').filter(row => row.parentid === parentId);
    }

    function prepareFormData() {
        const formData = new FormData();
        formData.append('role_id', role_id);

        const rows = $('#accessTreeGrid').jqxTreeGrid('getRows');
        const menu = [];
        const akses = {};
        const availablePermissions = {};

        // Cache row data lookup to reduce DOM calls
        const rowMap = new Map();

        // Flatten all rows (recursive)
        function flattenRows(rows) {
            let flat = [];
            for (const row of rows) {
                flat.push(row);
                if (row.records && row.records.length > 0) {
                    flat = flat.concat(flattenRows(row.records));
                }
            }
            return flat;
        }

        const flatRows = flattenRows(rows);

        // First pass: cache rowData, gather availablePermissions
        flatRows.forEach(row => {
            const rowData = $('#accessTreeGrid').jqxTreeGrid('getRow', row.uid);
            rowMap.set(row.uid, rowData);

            if (row.id?.startsWith('akses_')) {
                const [menuIdStr, aksesValue] = row.value.split('|');
                const menuId = parseInt(menuIdStr);

                if (!availablePermissions[menuId]) {
                    availablePermissions[menuId] = [];
                }
                availablePermissions[menuId].push(aksesValue);
            }
        });

        // Second pass: process menus that are checked or indeterminate
        flatRows.forEach(row => {
            const rowData = rowMap.get(row.uid);
            const isChecked = rowData.checked === true || rowData.checked === null;

            if (isChecked && row.id?.startsWith('menu_')) {
                const menuId = parseInt(row.value);
                if (!menu.includes(menuId)) {
                    menu.push(menuId);
                }
                if (!akses[menuId]) {
                    akses[menuId] = [...(availablePermissions[menuId] || [])];
                }
            }
        });

        // Third pass: refine individual permissions
        flatRows.forEach(row => {
            if (row.id?.startsWith('akses_')) {
                const rowData = rowMap.get(row.uid);
                const [menuIdStr, aksesValue] = row.value.split('|');
                const menuId = parseInt(menuIdStr);

                if (rowData.checked === false && menu.includes(menuId)) {
                    akses[menuId] = akses[menuId]?.filter(perm => perm !== aksesValue);
                }

                if (rowData.checked === true) {
                    if (!menu.includes(menuId)) {
                        menu.push(menuId);
                    }
                    if (!akses[menuId]) {
                        akses[menuId] = [];
                    }
                    if (!akses[menuId].includes(aksesValue)) {
                        akses[menuId].push(aksesValue);
                    }
                }
            }
        });

        formData.append('menu', JSON.stringify(menu));
        formData.append('akses', JSON.stringify(akses));

        const payload = {
            role_id: role_id,
            menu: menu,
            akses: akses
        }

        return payload;
    }

    $(document).ready(function() {
        // Initialize TreeGrid with the data
        var treeData = buildTreeData(@json($data->menu_akses ?? []));
        
        var source = {
            datatype: "json",
            datafields: [
                { name: 'id', type: 'string' },
                { name: 'parentid', type: 'string' },
                { name: 'name', type: 'string' },
                { name: 'value', type: 'string' },
                { name: 'checked', type: 'bool' },
                { name: 'hasChildren', type: 'bool' },
                { name: 'level', type: 'number' }
            ],
            hierarchy: {
                keyDataField: { name: 'id' },
                parentDataField: { name: 'parentid' }
            },
            id: 'id',
            localdata: treeData
        };
        
        var dataAdapter = new $.jqx.dataAdapter(source);
        
        accessTreeGrid = $('#accessTreeGrid').jqxTreeGrid({
            width: '100%',
            height: '500px',
            source: dataAdapter,
            checkboxes: true,
            hierarchicalCheckboxes: true,
            ready: function() {
                // Expand all rows by default
                $('#accessTreeGrid').jqxTreeGrid('expandAll');
                
                // Load selected permissions
                loadSelectedPermissions();
            },
            columns: [
                { text: 'Permission', datafield: 'name', width: '100%' }
            ]
        });

        $('#accessTreeGrid').on('bindingComplete', function () {
            loadSelectedPermissions();
        });

        // Handle "Select All" checkbox
        $('#checkbox_all').on('change', function() {
            var isChecked = $(this).prop('checked');
            var rows = $('#accessTreeGrid').jqxTreeGrid('getRows');
            
            rows.forEach(row => {
                if (isChecked) {
                    $('#accessTreeGrid').jqxTreeGrid('checkRow', row.uid);
                } else {
                    $('#accessTreeGrid').jqxTreeGrid('uncheckRow', row.uid);
                }
            });
        });

        // Update "Select All" checkbox state based on all rows
        $('#accessTreeGrid').on('checkChange', function(event) {
            setTimeout(function() {
                var rows = $('#accessTreeGrid').jqxTreeGrid('getRows');
                var allChecked = true;
                var anyChecked = false;
                
                rows.forEach(row => {
                    var rowData = $('#accessTreeGrid').jqxTreeGrid('getRow', row.uid);
                    if (rowData.checked === true) {
                        anyChecked = true;
                    } else if (rowData.checked === null) {
                        anyChecked = true;
                        allChecked = false;
                    } else {
                        allChecked = false;
                    }
                });
                
                $('#checkbox_all').prop('checked', allChecked);
                $('#checkbox_all').prop('indeterminate', !allChecked && anyChecked);
            }, 100);
        });

        // Form submission
        $('#form-data-access').on('submit', function(e) {
            e.preventDefault();
            const formData = prepareFormData();

            $.ajax({
                url: '/setting/roles/update_access',
                type: 'POST',
                dataType: 'json',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    alertSuccess(response.message || 'Permissions saved successfully');
                },
                error: function(xhr) {
                    const errorMsg = xhr.responseJSON?.message || 'An error occurred while saving permissions';
                    alert(errorMsg);
                }
            });
        });
    });
</script>
@endpush