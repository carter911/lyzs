define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'onlineservice/history/index',
                    add_url: '',
                    edit_url: '',
                    del_url: 'onlineservice/history/del',
                    multi_url: ""
                }
            });

            var table = $("#table");
            table.on('post-common-search.bs.table', function (event, table) {
                //var form = $("form", table.$commonsearch);
                /* $("input[name='user_id']").addClass("selectpage").data("source", "auth/admin/index").data("primaryKey", "id").data("field", "nickname").data("orderBy", "id desc");
                 Form.events.selectpage($("form", table.$commonsearch));*/
            });

            //在表格内容渲染完成后回调的事件
            table.on('post-body.bs.table', function (e, json) {

            });

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                sortName: 'id',
                columns: [
                    [
                        {field: 'state', checkbox: true},
                        {field: 'id', title: 'ID', operate: false},
                        {field: 'admin_name', title: __('管理员ID'), operate: false},
                        {field: 'visitor.nickname', title: __('访客昵称')},
                        {field: 'user_name', title: __('会员昵称'), operate: false},
                        {
                            field: 'content->text',
                            title: __('聊天内容'),
                            align: 'left',
                            operate: false,
                            width: '350px',
                            formatter: Controller.api.formatter.content
                        },
                        {
                            field: 'source_status',
                            title: __("消息来源"),
                            searchList: {"1": __('访客'), "2": __('管理员')},
                            formatter: Controller.api.formatter.source_status
                        },
                        {
                            field: 'msg_status',
                            title: __("消息状态"),
                            searchList: {"0": __('未读'), "0": __('已读')}, formatter: Controller.api.formatter.msg_status
                        },
                        {field: 'sendtime', title: __('发送时间'), operate: 'RANGE', addclass: 'datetimerange'},
                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            buttons: [{
                                name: 'machine',
                                text: __('访客详情'),
                                /*icon: 'fa fa-list',*/
                                classname: 'btn btn-info btn-xs btn-detail btn-dialog',
                                url: 'onlineservice/history/detail'
                            }],
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ],
                clickToSelect: false,
                showToggle: false,
                commonSearch: true, //搜索框
                showExport: false //导出按钮
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Form.api.bindevent($("form[role=form]"));
        },
        edit: function () {
            Form.api.bindevent($("form[role=form]"));
        },
        detail: function () {


        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            formatter: {
                content: function (value, row, index) {
                    if (row.type == 0) {
                        return row.content.text;
                    } else if (row.type == 1) {
                        return '<a href="' + row.content.val + '" target="_blank" title="点击查看"><i class="fa fa-image" style="font-size: 16px; color: #6c7e8a;"></i> 这是一张图片</a>';
                    } else {
                        return '<a href="' + row.content.val + '" target="_blank" title="点击下载"><i class="fa fa-file-excel-o" style="font-size: 16px; color: #6c7e8a;"></i> 这是一个附件</a>';
                    }
                },
                source_status: function (value, row, index) {
                    return value == 1 ? "访客" : "管理员";
                }, msg_status: function (value, row, index) {
                    return value == 0 ? "未读" : "已读";
                },
                browser: function (value, row, index) {
                    return '<a class="btn btn-xs btn-browser">' + row.useragent.split(" ")[0] + '</a>';
                },
            }
        }
    };

    return Controller;
});