$(document).ready(function () {
    $(".bootstrap-tagsinput .tag").
    $(".bootstrap-tagsinput").keypress(function () {

        jQuery.ajax({
            type: "POST",
            url: '/parser/search-category',
            dataType: 'json',
            data: {},
            success: function () {
                $(".tag label label-info")
            }
        });



    })

})
