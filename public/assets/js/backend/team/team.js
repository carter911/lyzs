define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'team/team/index',
                    add_url: 'team/team/add',
                    edit_url: 'team/team/edit',
                    del_url: 'team/team/del',
                    multi_url: 'team/team/multi',
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
                        {field: 'image', title: __('Image'), formatter: Table.api.formatter.image},
                        {field: 'is_home', title: __('Is_home'), searchList: {"1":__('Is_home 1'),"2":__('Is_home 2')}, formatter: Table.api.formatter.normal},
                        {field: 'is_gxj', title: __('Is_gxj'), searchList: {"1":__('Is_gxj 1'),"2":__('Is_gxj 2')}, formatter: Table.api.formatter.normal},
                        {field: 'is_gddz', title: __('Is_gddz'), searchList: {"1":__('Is_gddz 1'),"2":__('Is_gddz 2')}, formatter: Table.api.formatter.normal},
                        {field: 'sex', title: __('Sex'), searchList: {"1":__('Sex 1'),"2":__('Sex 2')}, formatter: Table.api.formatter.normal},
                        {field: 'work_name', title: __('Work_name')},
                        {field: 'team_door_ids', title: __('Team_door_ids')},
                        {field: 'team_style_ids', title: __('Team_style_ids')},
                        {field: 'work_num', title: __('Work_num')},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'idea', title: __('Idea')},
                        {field: 'subscribe_num', title: __('Subscribe_num')},
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