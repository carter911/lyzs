define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {
    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: '',
                    add_url: '',
                    edit_url: '',
                    del_url: '',
                    multi_url: ""
                }
            });
            var table = $("#table");
            table.on('post-common-search.bs.table', function (event, table) {
            });

            //在表格内容渲染完成后回调的事件
            table.on('post-body.bs.table', function (e, json) {
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
        }
    };
    return Controller;
});