//getYuyue

function showLyDialog(params) {
    console.log("-----showLyDialog----->>" + params);

    $.ajax({
        url: '/index/FormIndex/showDialog',
        type: 'get',
        data: {
            type: params
        },

        success: function (data) {
            $("body").append(data);
        }
    });
}

function showLyDialogWithId(params,otherId) {
    console.log("-----showLyDialogWidthId----->>" + params);

    $.ajax({
        url: '/index/FormIndex/showDialogWithId',
        type: 'get',
        data: {
            type: params,
            otherId:otherId
        },

        success: function (data) {
            $("body").append(data);
        }
    });
}

/**
 * 提交表单
 * @param formId
 */
function submitForm(formId) {
    console.log("-----submitForm----->>" + formId);

    $.ajax({
        url: '/index/FormIndex/submitForm',
        type: 'post',
        data: $(formId).serialize(),
        success: function (data) {
            console.log("data:", data);

            if(null != data && data.code === 200) {
                $(".common-tender-wrapper").parent().remove();
                //TODO

            }else {
                //TODO

            }
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
