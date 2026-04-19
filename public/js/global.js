//const { method } = require("lodash");

var action;

alert = function(str) {
    Swal.fire({
      title: '',
      text: str,
      icon: 'error',
      confirmButtonColor: "#254283"
    })
}

function alertSuccess(str, url = null) {
    Swal.fire({
      title: 'Success',
      text: str,
      icon: 'success',
      showCancelButton: false,
      showConfirmButton: true,
    }).then((result) => {
      if (url !== null) {
        location.href = url;
      }
    })
    
}

function renderNumRow(data, type, row, meta) {
    return meta.row + 1 + meta.settings._iDisplayStart;
}

function renderDate(data) {
  var d = new Date(data);
    var month_names =["Jan","Feb","Mar",
                    "Apr","May","Jun",
                    "Jul","Aug","Sep",
                    "Oct","Nov","Dec"];

    var day = d.getDate();
    var month_index = d.getMonth();
    var year = d.getFullYear();

    return "" + day + " " + month_names[month_index] + " " + year;
}

function setDataTable(divId, dataUrl, colDef = [], requestData = null, requestOrder = null, reqDom = null, reqFooterCallback = null, reqCreatedRow = false) {
    var  tableConf = {
        select: true,
        processing: true,
        serverSide: true,
        destroy: true,
        scrollX: true,
        //responsive:true,
        scrollCollapse: true,
        sScrollXInner: '100%', 
        fixedColumns:   {
            left: 3
        },
        lengthMenu:[[10,25,50],[10,25,50]],
        ajax: {
            url: dataUrl,
            type: 'POST',
            headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: function(d) {
                if (requestData !== null) {
                  $.each(requestData, function(key, value) {
                      d[key] = value;
                  });
                }
            },
            error : function(xhr, textStatus, errorThrown){
              alert(errorThrown)
            }
        },
        columns: colDef,
    };
    
    
    if (requestOrder !== null) {
        tableConf.order = requestOrder;
    }
    
    if (reqDom !== null) {
        tableConf.dom = reqDom;
    }

    if (reqFooterCallback != null) {
        tableConf.footerCallback = reqFooterCallback;
    }

    if (reqCreatedRow != false) {
        tableConf.createdRow = reqCreatedRow;
    }
    
    return $(divId).DataTable(tableConf);
}

function ajaxData(requestUrl, requestData, callback = false, multipart = false, showAlert = true, type = 'POST') {
    var ajaxSetting = {
      type  : type,
      url     : requestUrl,
      headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      data    : requestData,
      beforeSend: function(data) {
        $('#modal-loading').fadeIn('fast');
      },
      success: function(data) {
        try {
          var result = data;
  
          if (result.status === true) {
            if (callback !== false) callback(result);
          } else if (result.status === false) {
            if (showAlert === true) {
              if (typeof(result.message) == 'object') {
                message = '';
                $.each(result.message, function(idx, val) {
                  $.each(val, function(index, values) {
                    message += values + '\n';
                  });
                });

                alert(message)
              } else {
                alert(result.message);
              }
            }
          } else {
            callback(result);
          }
        } catch (e) {
          alert('System Error\n ' + e.message);
        }
      },
      error: function(data) {
        var result = JSON.parse(data.responseText);
        alert(data.status + '\n' + result.message);
      },
      complete: function(data) {
        $('#modal-loading').fadeOut('fast');
      }
    }
  
    if (multipart === true) {
      ajaxSetting.contentType = false;
      ajaxSetting.processData = false;
    }
  
    $.ajax(ajaxSetting);
}

function toggleForm(elem, role) {
  if (role === true) {
    $(elem + ' :input').not('[type="button"]').prop('disabled', false);
    $(elem + ' :input[type="submit"]').show();
  } else {
    $(elem + ' :input').not('[type="button"]').prop('disabled', true);
    $(elem + ' :input[type="submit"]').hide();
  }
}

function resetForm(elem, select2id = null) {
  $(elem)[0].reset();
  $(elem + ' span.error').remove();
  $(elem + ' :input').removeClass('error');
  $(elem + ' textarea').html('');
  $(elem + ' input[type="file"]').each(function(index, el) {
    var target = $(el).data('target');

    $(target).prop('src', $(el).data('default'));
  });

  if (select2id === null) {
    $(elem + ' select').trigger('change');
  } else {
    $(elem + ' select').not(select2id.join(', ')).trigger('change');
  }
}

function deleteData(id, actionUrl, callback = refresh, message = "Anda yakin akan menghapus data tersebut?") {
  Swal.fire({
    title: 'Warning!',
    text: message,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    cancelButtonText: "No",
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      ajaxData(actionUrl, {id:id}, callback, false, true, 'delete');
    }
  })
}

function activationData(id, actionUrl, callback = refresh, message = "Anda yakin akan nonaktifkan data tersebut?") {
  Swal.fire({
    title: 'Warning!',
    text: message,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    cancelButtonText: "No",
    confirmButtonText: 'Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      ajaxData(actionUrl, {id:id}, callback, false, true, 'get');
    }
  })
}

function formatRupiah(bilangan, prefix) {
    var number_string = bilangan.replace(/[^,\d]/g, '').toString(),
    split   = number_string.split(','),
    sisa    = split[0].length % 3,
    rupiah  = split[0].substr(0, sisa),
    ribuan  = split[0].substr(sisa).match(/\d{1,3}/gi);
    
    if (ribuan) {
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }
    
    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}

var numberField = document.getElementsByClassName('separator');
if(numberField){
    $.each(numberField, function(key, value){
        $(this).on('keyup', function(e)
        {
            $(this).val(formatRupiah($(this).val() ) );
        });
    });
}

function formatCurrency(data, digits = 0) {
  var formatter = new Intl.NumberFormat('id-ID', {
      style: 'decimal',
      minimumFractionDigits: 2,
  });
  
  return formatter.format(data);
}

function inArray(needle, haystack) {
  var length = haystack.length;
  for(var i = 0; i < length; i++) {
      if(haystack[i] == needle) return true;
  }
  return false;
}

function renderDateIfNull(data, type, row) {
    if (data == null) return ''

    return renderDate(data);
}

$(document).ready(function() {
  $('input').prop('autocomplete', 'off');
  //$(".select2").select2({width:"100%"});
  // $('.datatable').DataTable();

  // $(".datepicker").datetimepicker({
  //     format: "DD-MM-YYYY",
  //     useCurrent: false  
  // });

  // $(".datepickermonth").datetimepicker( {
  //     format: "MM-YYYY",
  // });
  
  $("body").on({'change keyup': function(e){
      formatValue($(this));
  },'focus': function() {
      $(this).select();
  }},".input-currency, .input-number, input[data-type='currency']");

  $('.input').prop('autocomplete', false);

  $('input[type="file"]').change(function(e) {
    var maxsize = $(this).data('maxsize');
    var uploadedFile = this.files[0];

    if (typeof(maxsize) == 'undefined') {
      maxsize = 5000 * 1000;
    } else {
      maxsize = parseInt(maxsize) * 1000;
    }
    
    if (uploadedFile.size > maxsize) {
      alert('Ukuran file melebihi batas. Silahkan ulangi. \n Maksimum file upload : '+(maxsize/1000/1000)+' MB');
      this.value = "";
    }

  });

  $('input[type="file"]').change(function(e) {
    if ($(this).data('target') !== undefined) {
      var imageType = ['png','jpg','jpeg'];
      var target = $(this).data('target');
      var reader = new FileReader();
      var uploadedFile = this.files[0];
      var fileType = uploadedFile.name.split('.');
      fileType = fileType[fileType.length-1];

      if ($.inArray(fileType, imageType) != -1) {
        reader.onload = function(event) {
          $(target).html('<img src="' + event.target.result + '" style="width:100%; height:100%;">');
        }

        reader.readAsDataURL(uploadedFile);
      } else {
        alert('Tipe file harus sesuai');
      }
    }
  });
});
