define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'cases/cases/index',
                    add_url: 'cases/cases/add',
                    edit_url: 'cases/cases/edit',
                    del_url: 'cases/cases/del',
                    multi_url: 'cases/cases/multi',
                    table: 'cases',
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
                        {field: 'status', title: __('Status'), searchList: {"1":__('Status 1'),"2":__('Status 2')}, formatter: Table.api.formatter.status},
                        {field: 'xq_name', title: __('Xq_name')},
                        {field: 'name', title: __('Name')},
                        {field: 'region_id', title: __('Region_id')},
                        {field: 'address', title: __('Address')},
                        {field: 'area', title: __('Area')},
                        {field: 'type', title: __('Type'), searchList: {"1":__('Type 1'),"2":__('Type 2'),"3":__('Type 3')}, formatter: Table.api.formatter.normal},
                        {field: 'team_team_id', title: __('Team_team_id')},
                        {field: 'team_door_ids', title: __('Team_door_ids')},
                        {field: 'team_style_ids', title: __('Team_style_ids')},
                        {field: 'cases_area_id', title: __('Cases_area_id')},
                        {field: 'butler_id', title: __('Butler_id')},
                        {field: 'price', title: __('Price'), operate:'BETWEEN'},
                        {field: 'is_kjl', title: __('Is_kjl'), searchList: {"1":__('Is_kjl 1'),"2":__('Is_kjl 2')}, formatter: Table.api.formatter.normal},
                        {field: 'url', title: __('Url'), formatter: Table.api.formatter.url},
                        {field: 'image', title: __('Image'), formatter: Table.api.formatter.image},
                        {field: 'design_type', title: __('Design_type')},
                        {field: 'work_num', title: __('Work_num')},
                        {field: 'amount', title: __('Amount')},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'is_home', title: __('Is_home'), searchList: {"1":__('Is_home 1'),"2":__('Is_home 2')}, formatter: Table.api.formatter.normal},
                        {field: 'is_gxj', title: __('Is_gxj'), searchList: {"1":__('Is_gxj 1'),"2":__('Is_gxj 2')}, formatter: Table.api.formatter.normal},
                        {field: 'is_gddz', title: __('Is_gddz'), searchList: {"1":__('Is_gddz 1'),"2":__('Is_gddz 2')}, formatter: Table.api.formatter.normal},
                        {field: 'is_yj', title: __('Is_yj'), searchList: {"1":__('Is_yj 1'),"2":__('Is_yj 2')}, formatter: Table.api.formatter.normal},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            
            // 绑定TAB事件
            $('.panel-heading a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var field = $(this).closest("ul").data("field");
                var value = $(this).data("value");
                var options = table.bootstrapTable('getOptions');
                options.pageNumber = 1;
                options.queryParams = function (params) {
                    var filter = {};
                    if (value !== '') {
                        filter[field] = value;
                    }
                    params.filter = JSON.stringify(filter);
                    return params;
                };
                table.bootstrapTable('refresh', {});
                return false;
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