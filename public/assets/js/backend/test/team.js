define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'test/team/index',
                    add_url: 'test/team/add',
                    edit_url: 'test/team/edit',
                    del_url: 'test/team/del',
                    multi_url: 'test/team/multi',
                    table: 'team',
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
                        {field: 'name', title: __('Name')},
                        {field: 'sex', title: __('Sex'), searchList: {"1":__('Sex 1')," 0":__('Sex  0')}, formatter: Table.api.formatter.normal},
                        {field: 'work_name', title: __('Work_name')},
                        {field: 'door_ids', title: __('Door_ids')},
                        {field: 'style_ids', title: __('Style_ids')},
                        {field: 'work_num', title: __('Work_num')},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'update_time', title: __('Update_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'door.id', title: __('Door.id')},
                        {field: 'door.name', title: __('Door.name')},
                        {field: 'door.create_time', title: __('Door.create_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'door.update_time', title: __('Door.update_time'), operate:'RANGE', addclass:'datetimerange'},
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