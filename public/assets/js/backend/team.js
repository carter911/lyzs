define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'team/index',
                    add_url: 'team/add',
                    edit_url: 'team/edit',
                    del_url: 'team/del',
                    multi_url: 'team/multi',
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
                        {field: 'team_door_ids', title: __('Team_door_ids')},
                        {field: 'team_style_ids', title: __('Team_style_ids')},
                        {field: 'work_num', title: __('Work_num')},
                        {field: 'create_time', title: __('Create_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'update_time', title: __('Update_time'), operate:'RANGE', addclass:'datetimerange'},
                        {field: 'is_home', title: __('Is_home'), searchList: {"1":__('Is_home 1')," 0":__('Is_home  0')}, formatter: Table.api.formatter.normal},
                        {field: 'avatar', title: __('Avatar')},
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