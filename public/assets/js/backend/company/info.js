define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'company/info/index',
                    add_url: 'company/info/add',
                    edit_url: 'company/info/edit',
                    del_url: 'company/info/del',
                    multi_url: 'company/info/multi',
                    table: 'company_info',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'department_phone', title: __('Department_phone')},
                        {field: 'service_phone', title: __('Service_phone')},
                        {field: 'hr_phone', title: __('Hr_phone')},
                        {field: 'join_phone', title: __('Join_phone')},
                        {field: 'address', title: __('Address')},
                        {field: 'email', title: __('Email')},
                        {field: 'top_state', title: __('Top_state'), searchList: {"1":__('Top_state 1'),"0":__('Top_state 0')}, formatter: Table.api.formatter.normal},
                        {field: 'department_name', title: __('Department_name')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});