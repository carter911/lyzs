//getYuyue
function checkDataEmpty(id) {
    var userName = $("#" + id).val();
    if (userName.length === 0) {
        $("#" + id).css("border", "1px solid #ee0000");
        return true;
    } else {
        $("#" + id).css("border", "1px solid #ddd");
        return false;
    }
}

function checkBaojiaData() {
    if (checkDataEmpty("baojia_user_name")) return;
    if (checkDataEmpty("baojia_user_phone")) return;
    if (checkDataEmpty("baojia_user_area")) return ;
    submitForm('#baojia');
}


function checkActivityData() {
    if (checkDataEmpty("activity_user_location")) return;
    if (checkDataEmpty("activity_user_name")) return;
    if (checkDataEmpty("activity_user_phone")) return;
    submitForm('#canjia_activity');
}

function checkService() {
    if (checkDataEmpty("service_user_location")) return;
    if (checkDataEmpty("service_user_name")) return;
    if (checkDataEmpty("service_user_location")) return;
    if (checkDataEmpty("service_user_phone")) return;
    submitForm('#dingzhi_service')

}

function checkGongdi() {
    if (checkDataEmpty("gongdi_user_location")) return;
    if (checkDataEmpty("gongdi_user_name")) return;
    if (checkDataEmpty("gongdi_user_phone")) return;
    submitForm('#gongdi')
}

function checkLiaojieMore() {
    if(checkDataEmpty("liaojie_user_location"))return;
    if(checkDataEmpty("liajie_user_name"))return;
    if(checkDataEmpty("liaojie_user_phone")) return;
    submitForm('#liaojie_more');
}

function app_user_phone() {
    if(checkDataEmpty("app_user_phone")) return;
    if(checkDataEmpty("app_user_name")) return;
    if(checkDataEmpty("app_user_location")) return;
    submitForm("#tiyan_app");
}

function checkDesigner() {
    if(checkDataEmpty("designer_user_location"))return;
    if(checkDataEmpty("designer_user_name"))return;
    if(checkDataEmpty("designer_user_phone")) return;
    submitForm('#yuyue_designer');
}

function checkManager() {
    if(checkDataEmpty("manager_user_location"))return;
    if(checkDataEmpty("manager_user_name"))return;
    if(checkDataEmpty("manager_user_phone")) return;
    submitForm("#yuyue_manager");
}

function checkYuyueTa() {
    if(checkDataEmpty("ta_user_location"))return;
    if(checkDataEmpty("ta_user_name"))return;
    if(checkDataEmpty("ta_user_phone")) return;
    submitForm("#yuyue_ta");
}


function showLyDialog(params) {
    console.log("-----showLyDialog----->>" + params);

    $.ajax({
        url: '/index/Form/showDialog',
        type: 'get',
        data: {
            type: params
        },

        success: function (data) {
            $("body").append(data);
        }
    });
}

function showLyDialogWithId(params, otherId) {
    console.log("-----showLyDialogWidthId----->>" + params);

    $.ajax({
        url: '/index/Form/showDialogWithId',
        type: 'get',
        data: {
            type: params,
            otherId: otherId
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
        url: '/index/Form/submitForm',
        type: 'post',
        data: $(formId).serialize(),
        success: function (data) {
            console.log("data:", data);

            if (null != data && data.code === 200) {
                $(".common-tender-wrapper").parent().remove();
                alert("预约成功");

            } else {
                //TODO
                alert("预约成功");
            }
        },

        error: function (error) {
            $(".common-tender-wrapper").parent().remove();
            alert("预约成功");
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
