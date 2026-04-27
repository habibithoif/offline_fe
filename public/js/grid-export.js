// function exportGrid(gridId, type='xlsx') {

//     const rows = $(gridId).jqxGrid('getrows');

//     const ws = XLSX.utils.json_to_sheet(rows);
//     const wb = XLSX.utils.book_new();

//     XLSX.utils.book_append_sheet(wb, ws, "data");

//     XLSX.writeFile(wb, `export.${type}`);
// }

function exportGridAll(gridId, filename='tm', type='xlsx') {

    const grid = $(gridId);
    const source = grid.jqxGrid('source');

    let url = source._source.url;
    let params = source._source.data || {};

    params.export = 1;

    const query = new URLSearchParams(params).toString();

    fetch(`${url}?${query}`)
    .then(res=>res.json())
    .then(res=>{

        const rows = res.data.Rows;

        const ws = XLSX.utils.json_to_sheet(rows);
        const wb = XLSX.utils.book_new();

        XLSX.utils.book_append_sheet(wb, ws, "data");

        // penting: pakai extension
        XLSX.writeFile(wb, `${filename}.${type}`);
    });
}