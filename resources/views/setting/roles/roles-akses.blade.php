@extends('layouts.layouts_app')

@section('content')

@php
    $currentPath = request()->path();
    $userAccessRole = null;

    foreach ($menu['data'] as $item) {
        if (isset($item['children']) && is_array($item['children'])) {
            foreach ($item['children'] as $child) {
                if ($child['path'] === $currentPath) {
                    $userAccessRole = json_decode($child['akses_role'], true);
                    break 2; // Hentikan loop jika ketemu
                }
            }
        }
    }
@endphp

<style>
    .loading {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }
    .indent-1 { padding-left: 20px !important; }
    .table > tbody > tr > td {
        vertical-align: middle;
    }

    ul.menu-list {
        list-style-type: none;
        padding-left: 0;
    }

    ul.menu-list li {
        margin-left: 25px; /* Adjust spacing */
    }

    ul.akses-list {
        list-style-type: none;
        padding-left: 0;
    }

    ul.akses-list li {
        margin-left: 25px; /* Adjust spacing */
    }
    ul.akses-list {
        list-style-type: none;
        padding-left: 10px; /* Adjust or set to 0 if necessary */
        margin-left: 0; /* Ensures it's aligned properly */
    }
    ul.akses-list li {
        display: inline-block;
        margin-right: 10px; /* Space between items */
    }
    .akses-list label {
        opacity: 0.8; /* 70% transparan */
        transition: opacity 0.3s ease-in-out;
    }

    .akses-list label:hover {
        opacity: 1; /* Kembali normal saat hover */
    }

</style>

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1>{{ $data->page->title }}</h1>
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">{{ $data->page->title }}</li>
        </ol>
        </div>
    </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form class="form" id="form-data-access">
                    <div class="card">
                        <div class="card-body">
                            <input type="hidden" class="form-control form-control-sm text-sm" name="role_id" id="role_id">
                            <div class="col-sm-6">
                                <div class="form-group clearfix">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="checkbox_all">
                                        <label for="checkbox_all">
                                        Semua
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            @include('setting.roles.roles-akses-partials', ['menus' => $data->menu_akses ?? [], 'level' => 0])
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                            <button type="button" class="btn btn-sm btn-default" onclick="window.history.back();">Kembali</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div> 
</section>
<!-- /.content -->


@endsection


@push('scripts')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('template_app') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('template_app') }}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('template_app') }}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('template_app') }}/plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.min.css">

    <link rel="stylesheet" href="{{ asset('template_app') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('template_app') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <script src="{{ asset('template_app') }}/plugins/select2/js/select2.full.min.js"></script>
    <script src="{{ asset('template_app') }}/plugins/jquery-validation/jquery.validate.min.js"></script>
    
    <script>
        var userAccessRole  = @json($userAccessRole);
        let currentPats     = window.location.pathname;
        var baseUrl         = mainServerUrl + currentPats;
        var role_id         = {{ $data->role_id }};
        var tableData;

        function refresh(result) {
            alertSuccess(result.message);
        }

        function checkSelectedMenus(role_id) {
            $.ajax({
                url: mainServerUrl +  '/setting/roles/read_access/'+role_id,
                type: "GET",
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    $.each(response.data, function(index, item) {
                        let menuCheckbox = document.getElementById(`menu${item.menu_id}`);
                        if (menuCheckbox) {
                            menuCheckbox.checked = true;
                        }

                        item.akses?.forEach(akses => {
                            let aksesCheckbox = document.getElementById(`akses${item.menu_id}_${akses}`);
                            if (aksesCheckbox) {
                                aksesCheckbox.checked = true;
                            }
                        });

                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }


        $(document).ready(function() {
            $('#role_id').val(role_id)

            checkSelectedMenus(role_id);

            $('#checkbox_all').on('click', function() {
                if ($(this).prop('checked')) {
                    $('input[type=checkbox][name*=menu]').prop('checked', true);
                    $('input[type=checkbox][name*=akses]').prop('checked', true);
                } else {
                    $('input[type=checkbox][name*=menu]').prop('checked', false);
                    $('input[type=checkbox][name*=akses]').prop('checked', false);
                }
            })

            // When a menu checkbox is changed
            $('input[name="menu[]"]').on('change', function () {
                let menuId = $(this).val();
                let isChecked = $(this).is(':checked');

                // Select/deselect all access checkboxes related to this menu
                $(`input[name="akses[${menuId}][]"]`).prop('checked', isChecked);

                // Select/deselect all submenu checkboxes recursively
                toggleChildren(menuId, isChecked);

                // Ensure parent menus are correctly updated
                toggleParents($(this).data('parent'));
            });

            // When an access checkbox is changed
            $('input[name^="akses["]').on('change', function () {
                let menuId = $(this).attr('name').match(/\[([0-9]+)\]/)[1];

                // If any access is checked, the parent menu should be checked
                let anyChecked = $(`input[name="akses[${menuId}][]"]:checked`).length > 0;
                $(`input#menu${menuId}`).prop('checked', anyChecked);

                // Update parent menu accordingly
                toggleParents($(`input#menu${menuId}`).data('parent'));
            });

            // Function to toggle all child checkboxes
            function toggleChildren(parentId, isChecked) {
                let children = $(`input[name="menu[]"][data-parent="${parentId}"]`);
                children.prop('checked', isChecked);

                // Also toggle their respective access checkboxes
                children.each(function () {
                    let childMenuId = $(this).val();
                    $(`input[name="akses[${childMenuId}][]"]`).prop('checked', isChecked);

                    // Recursively apply to deeper levels
                    toggleChildren(childMenuId, isChecked);
                });
            }

            // Function to update parent checkboxes based on child state
            function toggleParents(childId) {
                if (!childId) return; // Stop if no parent

                let parentCheckbox = $(`input#menu${childId}`);
                let allSiblingsChecked = $(`input[name="menu[]"][data-parent="${childId}"]:checked`).length > 0;

                parentCheckbox.prop('checked', allSiblingsChecked);

                // Recursively check/update grandparent menus
                toggleParents(parentCheckbox.data('parent'));
            }

            $('#form-data-access').validate({
                rules : {
                    
                },
                submitHandler:function (form, event) {
                    event.preventDefault();
                    var reqData = new FormData(form);

                    ajaxData('/setting/roles/update_access', reqData, refresh, true);
                }
            });
        });
    </script>
@endpush