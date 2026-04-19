
$(document).ready(function() {
    $.each(setting_template, function(index, value) {
        
        if(document.getElementById(value.selector_name) != null){
            
            $(value.selector_name + ' option[class="'+value.class+'"]').prop('selected', true).trigger('change')

        } else {
            $(value.selector_name).addClass(value.class);
        
            $('#'+value.class).prop('checked', true);
        }
        
    })

    $('.setting').on('click', function() {
        var reqData = [];
        $('.setting').each(function() {
            if ($(this).is('select')) {
                var data = {
                    class : $(this).attr('class'),
                    selector_name : $(this).attr('id')
                }
            } else {
                if ($(this).is(':checked')) {
                    var data = {
                        class : $(this).attr('id'),
                        selector_name :  $(this).attr('selector_name'),
                    }
                }
            }
            if(data){
                reqData.push(data)
            }
        })

        $.ajax({
            url     : "/settings/setting-template/setTemplate",
            type    : "POST",
            headers : {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data    : {reqData:reqData},
            success:function(data){
                console.log(data);
            },
            error:function(error){
              alert(error);
            }
        });
        
    })

})