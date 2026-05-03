@extends('layouts.layouts_app')

@section('content')

<x-content-header 
    :title="$data->page['name']" 
    :breadcrumbs="[
        ['label' => 'Home', 'url' => '#'],
        ['label' => 'FASOP', 'url' => '#'],
        ['label' => 'Histori', 'url' => '#'],
        ['label' => $data->page['name'], 'url' => '#']
    ]" 
/>
<section class="content">
    <div class="container-fluid">
        <!-- Main Grid Card -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm rounded">
                    <div class="card-header bg-info text-white">
                        <h3 class="card-title mb-0">HISTORI - DOWNLOAD FILE LST</h3>
                        <div class="card-tools">
                            <button id="refreshButton" class="btn btn-default btn-sm" title="Refresh">
                                <i class="fas fa-sync"></i>
                            </button>
                            <!-- <button id="listViewButton" class="btn btn-default btn-sm" title="List View">
                                <i class="fas fa-list"></i>
                            </button> -->
                            <button id="downloadButton" class="btn btn-default btn-sm" title="Download">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div id="treeGrid" style="width: 100%;"></div>
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

        function initializeGrid(filterParams = {}) {
            // CSRF Token setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var source = {
                datatype: "json",
                datafields: [
                    { name: 'id', type: 'number' },
                    { name: 'parent_id', type: 'number' },
                    { name: 'name', type: 'string' },
                    { name: 'type', type: 'string' },
                    { name: 'path', type: 'string' } // optional untuk download
                ],
                hierarchy: {
                    keyDataField: { name: 'id' },
                    parentDataField: { name: 'parent_id' }
                },
                id: 'id',
                url: '{{ route("fasop.histories.download-lst.read") }}',

                root: 'data'
            };

            var dataAdapter = new $.jqx.dataAdapter(source);

            var cellsRendererType = function (row, dataField, cellValue, rowData, cellText) {
                if (rowData.type === 'folder') {
                    return `<i class="fas fa-folder text-warning"></i> ${cellValue}`;
                } else {
                    return `<i class="fas fa-file text-success"></i> ${cellValue}`;
                }
            }

            var cellsRendererDownload = function (row, dataField, cellValue, rowData) {

                if (!rowData) return '';

                // buat object param
                var payload = {
                    type: rowData.type,  
                    path: rowData.path,    
                    name: rowData.name         
                };

                var url = baseUrl + "/download?path=" + rowData.path +"&name=" + rowData.name +"&type=" +rowData.type;

                return `
                    <i class="fas ${rowData.type === 'file' ? 'fa-download' : 'fa-file-archive'}"
                    style="cursor:pointer;color:${rowData.type === 'file' ? '#007bff' : '#28a745'}"
                    onclick="event.stopPropagation(); window.open('${url}', '_blank')">
                    </i>
                `;
            };

            $("#treeGrid").jqxTreeGrid({
                width: '100%',
                source: dataAdapter,

                columns: [
                    {
                        text: 'Name',
                        dataField: 'name',
                        cellsRenderer: cellsRendererType
                    },
                    {
                        text: 'Download',
                        width: '20%',
                        cellsRenderer: cellsRendererDownload
                    }
                ]
            });
            
        //     var source = {
        //         datatype: "json",
        //         datafields: [
        //             { name: 'no', type: 'string' },
        //             { name: 'file_name', type: 'string' },
        //             { name: 'size', type: 'string' },
        //             { name: 'modified', type: 'string' },
        //         ],
        //         url: '{{ route("fasop.histories.download-lst.read") }}',
        //         cache: false,
        //         data: filterParams,
        //         root: 'Rows',
        //         beforeprocessing: function(data) {
        //             if (data && data.data && data.data.Rows) {
        //                 tableData = data.data.Rows;
        //                 source.totalrecords = data.data.TotalRows;
        //             } else {
        //                 console.error('Invalid data structure:', data);
        //                 tableData = [];
        //                 source.totalrecords = 0;
        //             }
        //         }
        //     };

        //     dataAdapter = new $.jqx.dataAdapter(source);

        //     $("#jqxGrid").jqxGrid({
        //         width: '100%',
        //         source: dataAdapter,
        //         pageable: true,
        //         virtualmode: true,
        //         autorowheight: true,
        //         autoheight: false,
        //         showtoolbar: false,
        //         rendergridrows: function() {
        //             return dataAdapter.records;
        //         },
        //         columns: [
        //             { text: 'No', width: 50, cellsalign: 'center', align: 'center', datafield:'no'},
        //             { text: 'File Name', datafield: 'file_name' },
        //             { text: 'Size', datafield: 'size', width: 120 },
        //             { text: 'Modified', datafield: 'modified', width: 180 },
        //             {
        //                 text: 'Download',
        //                 datafield: 'download',
        //                 width: 120,
        //                 cellsrenderer: function (row) {

        //                     var filename = $('#jqxGrid').jqxGrid('getcellvalue', row, 'file_name');
        //                     var url = baseUrl + "/download/" + encodeURIComponent(filename);

        //                     return `
        //                         <div style="text-align:center;margin-top:6px">
        //                             <i class="fas fa-download"
        //                             style="cursor:pointer;color:#007bff"
        //                             onclick="event.stopPropagation(); window.open('${url}')">
        //                             </i>
        //                         </div>
        //                     `;
        //                 }
        //             }
        //         ],
        //         pagermode: 'default',
        //         pagesize: 20,
        //         pagesizeoptions: ['5', '10', '20', '50'],
        //         sortable: true,
        //         filterable: true,
        //         showfilterrow: true,
        //         filtermode: 'excel',
        //         theme: 'material'
        //     });
        }

        // function downloadFile(file) {
        //     window.open(baseUrl + "/download/" + file, "_blank");
        // }

        // function downloadFolder(folder) {
        //     window.open(baseUrl + "/downloadFolder/" + folder, "_blank");
        // }
        
        function refreshGrid(filterParams = {}) {
            // Update the dataAdapter with new parameters
            dataAdapter.data = filterParams;
            dataAdapter._source.data = filterParams;
            
            // Clear previous data
            dataAdapter.dataBind();
        }

        $(document).ready(function() {
            // Initialize grid first time
            initializeGrid();
        });
        
        // Refresh button functionality
        $('#refreshButton').on('click', function() {
            $("#treeGrid").jqxGrid('updatebounddata');
        });
        
        // Export to Excel
        $('#downloadButton').on('click', function() {
           exportGridAll('#jqxGrid','Daftar-file-lst','csv');
        });
        
        // List view button (toggle view or implement custom view)
        $('#listViewButton').on('click', function() {
            // Implement custom view toggle here
            console.log("List view toggle clicked");
        });
        
        function downloadFile(filename)
        {
            fetch(baseUrl + "/download/" + encodeURIComponent(filename), {
                method: 'GET',
                credentials: 'include', // <-- ini penting
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            .then(async response => {

                if (!response.ok) {
                    const text = await response.text();
                    console.log("ERROR:", text);
                    throw new Error("Download gagal");
                }

                const blob = await response.blob();

                console.log("Blob size:", blob.size);

                const url = window.URL.createObjectURL(blob);

                const a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                a.remove();

                window.URL.revokeObjectURL(url);
            })
            .catch(err => console.error(err));
        }

        onclick="event.stopPropagation(); window.location='${url}'";
    </script>
@endpush