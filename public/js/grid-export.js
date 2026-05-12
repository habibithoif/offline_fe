// function exportGrid(gridId, type='xlsx') {

//     const rows = $(gridId).jqxGrid('getrows');

//     const ws = XLSX.utils.json_to_sheet(rows);
//     const wb = XLSX.utils.book_new();

//     XLSX.utils.book_append_sheet(wb, ws, "data");

//     XLSX.writeFile(wb, `export.${type}`);
// }

// function exportGridAll(gridId, filename='tm', type='xlsx') {

//     const grid = $(gridId);
//     const source = grid.jqxGrid('source');

//     let url = source._source.url;
//     let params = source._source.data || {};

//     params.export = 1;

//     const query = new URLSearchParams(params).toString();

//     fetch(`${url}?${query}`)
//     .then(res=>res.json())
//     .then(res=>{

//         const rows = res.data.Rows;

//         const ws = XLSX.utils.json_to_sheet(rows);
//         const wb = XLSX.utils.book_new();

//         XLSX.utils.book_append_sheet(wb, ws, "data");

//         // penting: pakai extension
//         XLSX.writeFile(wb, `${filename}.${type}`);
//     });
// }

function exportGridAll(gridId, filename='tm', type='xlsx', headers=null, formData=null) {
    $('#modal-loading').fadeIn('fast');
    const grid = $(gridId);
    const source = grid.jqxGrid('source');

    let url = source._source.url;
    let params = source._source.data || {};

    params.export = 1;

    // merge param dari form
    if (formData) {
        formData.forEach((value, key) => {
            params[key] = value;
        });
    }

    const query = new URLSearchParams(params).toString();

    // console.time('fetch');

    fetch(`${url}?${query}`)
    .then(res => res.json())
    .then(res => {

        // console.timeEnd('fetch');

        const rows = res.data.Rows;

        if (!rows || rows.length === 0) {
            alert('Data kosong');
            return;
        }

        // 🔥 safety (hindari browser freeze)
        // if (rows.length > 10000) {
        //     alert('Data terlalu banyak, silakan filter lagi');
        //     return;
        // }

        console.log(headers);

        let ws;

        if (headers && headers.length > 0) {

            const fields = headers.map(h => h.field);
            const labels = headers.map(h => h.label);

            const data = rows.map(row => {
                let obj = {};
                fields.forEach((f, i) => {
                    let val = row[f];

                    // optional rounding di frontend
                    if (!isNaN(val)) {
                        val = Number(val).toFixed(2);
                    }

                    obj[labels[i]] = val;
                });
                return obj;
            });

            ws = XLSX.utils.json_to_sheet(data);

            ws['!cols'] = labels.map(l => ({ wch: l.length + 5 }));

        } else {
            ws = XLSX.utils.json_to_sheet(rows);
        }

        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "data");

        XLSX.writeFile(wb, `${filename}.${type}`);
        $('#modal-loading').fadeOut('fast');
        console.timeEnd('excel');

    }).catch(err => {
        console.error(err);
        alert('Gagal export');
    });
}