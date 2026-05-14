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

// function exportGridAll(gridId, filename='tm', type='xlsx', headers=null, formData=null) {
//     $('#modal-loading').fadeIn('fast');
//     const grid = $(gridId);
//     const source = grid.jqxGrid('source');

//     let url = source._source.url;
//     let params = source._source.data || {};
    
//     params.export = 1;

//     // merge param dari form
//     if (formData) {
//         formData.forEach((value, key) => {
//             params[key] = value;
//         });
//     }

//     const query = new URLSearchParams(params).toString();
    
//     // console.time('fetch');

//     fetch(`${url}?${query}`)
//     .then(res => res.json())
//     .then(res => {

//         console.log(res);

//         const rows = res.data.Rows;

//         if (!rows || rows.length === 0) {
//             alert('Data kosong');
//             return;
//         }

//         // 🔥 safety (hindari browser freeze)
//         // if (rows.length > 10000) {
//         //     alert('Data terlalu banyak, silakan filter lagi');
//         //     return;
//         // }

//         // console.log(headers);

//         let ws;

//         if (headers && headers.length > 0) {

//             const fields = headers.map(h => h.field);
//             const labels = headers.map(h => h.label);

//             const data = rows.map(row => {
//                 let obj = {};
//                 fields.forEach((f, i) => {
//                     let val = row[f];

//                     // optional rounding di frontend
//                     if (!isNaN(val)) {
//                         val = Number(val).toFixed(2);
//                     }

//                     obj[labels[i]] = val;
//                 });
//                 return obj;
//             });

//             ws = XLSX.utils.json_to_sheet(data);

//             ws['!cols'] = labels.map(l => ({ wch: l.length + 5 }));

//         } else {
//             ws = XLSX.utils.json_to_sheet(rows);
//         }

//         const wb = XLSX.utils.book_new();
//         XLSX.utils.book_append_sheet(wb, ws, "data");

//         XLSX.writeFile(wb, `${filename}.${type}`);
//         $('#modal-loading').fadeOut('fast');
//         console.timeEnd('excel');

//     }).catch(err => {
//         console.error(err);
//         alert('Gagal export');
//     });
// }

function exportGridAll(
    gridId,
    filename = 'tm',
    type = 'xlsx',
    headers = null
) {

    // support object parameter
    if (
        typeof gridId === 'object' &&
        gridId !== null
    ) {

        const obj = gridId;

        gridId = obj.gridId;
        filename = obj.filename || 'tm';
        type = obj.type || 'xlsx';
        headers = obj.headers || null;

    }

    if (!gridId) {
        alert('gridId wajib diisi');
        return;
    }

    if ($(gridId).length === 0) {
        alert(`Grid tidak ditemukan: ${gridId}`);
        return;
    }

    $('#modal-loading').fadeIn('fast');

    const grid = $(gridId);
    const source = grid.jqxGrid('source');

    let url = source._source.url;

    // ambil params bawaan grid
    let params = source._source.data || {};

    let queryParams = {};

    Object.keys(params).forEach(key => {

        queryParams[key] = params[key];

    });

    queryParams.export = 1;

    // build query
    const query = new URLSearchParams();

    Object.keys(queryParams).forEach(key => {

        const value = queryParams[key];

        // array
        if (Array.isArray(value)) {

            value.forEach(v => {
                query.append(`${key}[]`, v);
            });

        }
        // object
        else if (
            value !== null &&
            typeof value === 'object'
        ) {

            Object.keys(value).forEach(k => {

                query.append(
                    `${key}[${k}]`,
                    value[k]
                );

            });

        }
        // normal
        else if (
            value !== null &&
            value !== undefined
        ) {

            query.append(key, value);

        }

    });

    fetch(`${url}?${query.toString()}`)
        .then(res => res.json())
        .then(res => {

            let rows = null;

            // default jqxGrid
            if (res?.data?.Rows) {

                rows = res.data.Rows;

            } else {

                // cari array pertama otomatis
                const findArray = (obj) => {

                    if (Array.isArray(obj)) {
                        return obj;
                    }

                    if (
                        obj &&
                        typeof obj === 'object'
                    ) {

                        for (const key in obj) {

                            const result = findArray(obj[key]);

                            if (result) {
                                return result;
                            }

                        }

                    }

                    return null;

                };

                rows = findArray(res);

            }

            if (!rows || rows.length === 0) {

                $('#modal-loading').fadeOut('fast');

                alert('Data kosong');

                return;

            }

            let ws;

            // custom header
            if (headers && headers.length > 0) {

                const fields = headers.map(h => h.field);
                const labels = headers.map(h => h.label);

                const data = rows.map(row => {

                    let obj = {};

                    fields.forEach((f, i) => {

                        let val = row[f];

                        if (
                            val !== null &&
                            val !== '' &&
                            !isNaN(val)
                        ) {

                            val = Number(val).toFixed(2);

                        }

                        obj[labels[i]] = val;

                    });

                    return obj;

                });

                ws = XLSX.utils.json_to_sheet(data);

                ws['!cols'] = labels.map(l => ({
                    wch: l.length + 5
                }));

            } else {

                ws = XLSX.utils.json_to_sheet(rows);

            }

            const wb = XLSX.utils.book_new();

            XLSX.utils.book_append_sheet(
                wb,
                ws,
                "data"
            );

            XLSX.writeFile(
                wb,
                `${filename}.${type}`
            );

            $('#modal-loading').fadeOut('fast');

        })
        .catch(err => {

            console.error(err);

            $('#modal-loading').fadeOut('fast');

            alert('Gagal export');

        });

}

function exportGridLocal(
    gridId,
    filename = 'export',
    type = 'xlsx',
    headers = null
) {

    const rows = $(gridId).jqxGrid('getrows');

    if (!rows || rows.length === 0) {
        alert('Data kosong');
        return;
    }

    let data = rows;

    if (headers && headers.length > 0) {

        data = rows.map(row => {

            let obj = {};

            headers.forEach(h => {

                obj[h.label] = row[h.field];

            });

            return obj;

        });

    }

    const ws = XLSX.utils.json_to_sheet(data);

    const wb = XLSX.utils.book_new();

    XLSX.utils.book_append_sheet(
        wb,
        ws,
        'data'
    );

    XLSX.writeFile(
        wb,
        `${filename}.${type}`
    );

}