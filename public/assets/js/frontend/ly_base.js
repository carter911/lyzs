//getYuyue

function gotoYuyue() {
    console.log("-------------->>>");

    $.ajax({
        url: '/index/index/showDialog',
        type: 'get',
        data: {
            type: 'yuyue_manager'
        },
        success: function (data) {
            $("body").append(data);
        }
    })
}

function showLyDialog(params) {
    console.log("-----showLyDialog----->>" + params);

    $.ajax({
        url: '/index/index/showDialog',
        type: 'get',
        data: {
            type: params
        },

        success: function (data) {
            $("body").append(data);
        }
    });

}


function init() {
    $("body").on("click", ".popup-close", function () {
        $(".common-tender-wrapper").parent().remove();
    });

}


$(document).ready(function () {
    init()
});
