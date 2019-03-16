define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            $("#set-info").on('click', function () {
                var url = $(this).attr('data-url');
                var msg = $(this).attr('data-msg');
                var optons = {
                    shadeClose: false,
                    shade: [0, 3, '#393d49'],
                    area: ['70%', '70%'],
                    callback: function (value) {
                        console.log(value);
                    }
                };
                Fast.api.open(url, msg, optons);
            });
            $("#set-greetings").on('click', function () {
                var url = $(this).attr('data-url');
                var msg = $(this).attr('data-msg');
                var optons = {
                    shadeClose: false,
                    shade: [0, 3, '#393d49'],
                    area: ['70%', '70%'],
                    callback: function (value) {
                        console.log(value);
                    }
                };
                Fast.api.open(url, msg, optons);
            });
        },
        info: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'onlineservice/set/info',
                    add_url: 'onlineservice/set/info_add',
                    edit_url: 'onlineservice/set/info_edit',
                    del_url: 'onlineservice/set/info_del'
                }
            });

            var table = $("#table");
            table.on('post-common-search.bs.table', function (event, table) {
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
                        {checkbox: true},
                        {field: 'id', title: __('Id'), operate: false},
                        {field: 'username', title: __('账号')},
                        {field: 'nickname', title: __('昵称')},
                        {field: 'head_img', title: __('头像'), formatter: Table.api.formatter.image, operate: false},
                        {field: 'group_id', title: '分组ID', operate: false},
                        {field: 'createtime', title: __('时间')},
                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ],
                clickToSelect: false,
                search: false,
                showColumns: false,
                showToggle: false,
                showExport: false,
                showSearch: false,
                commonSearch: false
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        info_add: function () {
            Controller.api.bindevent();
        },
        info_edit: function () {
            Controller.api.bindevent();
        },
        greeting: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'onlineservice/set/greeting',
                    add_url: 'onlineservice/set/greeting_add',
                    edit_url: 'onlineservice/set/greeting_edit',
                    del_url: 'onlineservice/set/greeting_del'
                }
            });

            var table = $("#table");
            table.on('post-common-search.bs.table', function (event, table) {
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
                        {checkbox: true},
                        {field: 'id', title: __('Id'), operate: false},
                        {field: 'content', title: __('问候语')},
                        {
                            field: 'is_def',
                            title: __('是否默认'),
                            formatter: Controller.api.formatter.is_def,
                            operate: false
                        },
                        {field: 'type', title: __('分类'), formatter: Controller.api.formatter.type, operate: false},
                        {field: 'status', title: '状态', formatter: Table.api.formatter.status, operate: false,},
                        {field: 'createtime', title: __('时间')},
                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'resetDef',
                                    title: __('设置默认'),
                                    classname: 'btn btn-xs btn-info btn-magic btn-ajax btn-examine',
                                    icon: 'fa fa-check',
                                    url: function (row) {
                                        return 'onlineservice/set/setDef?type=' + row.type + '&ids=' + row.id;
                                    },
                                    hidden: function (row) {
                                        if (row.is_def == 1) {
                                            return true;
                                        }
                                    },
                                    extend: 'data-toggle="设置默认"',
                                    success: function (data, ret) {
                                        Layer.alert(ret.msg, {
                                            btn: [__('OK')],
                                            icon: 1
                                        });
                                        $('.btn-refresh').trigger('click');
                                        table.bootstrapTable('refresh');
                                        /*Layer.alert(ret.msg,function () {
                                            Layer.close();
                                            //table.bootstrapTable('refresh');
                                            window.location.reload();
                                        });*/
                                    },
                                    error: function (data, ret) {
                                        Layer.alert(ret.msg);
                                        return false;
                                    }
                                },
                                {
                                    name: 'unDef',
                                    title: __('撤销默认'),
                                    classname: 'btn btn-xs btn-primary btn-magic btn-ajax btn-examine',
                                    icon: 'fa fa-undo',
                                    url: 'onlineservice/set/unDef',
                                    hidden: function (row) {
                                        if (row.is_def == 0) {
                                            return true;
                                        }
                                    },
                                    extend: 'data-toggle="撤销默认"',
                                    success: function (data, ret) {
                                        Layer.alert(ret.msg, {
                                            btn: [__('OK')],
                                            icon: 1
                                        });
                                        $('.btn-refresh').trigger('click');
                                        table.bootstrapTable('refresh');
                                    },
                                    error: function (data, ret) {
                                        Layer.alert(ret.msg);
                                        return false;
                                    }
                                }
                            ],
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ],
                clickToSelect: false,
                search: false,
                showColumns: false,
                showToggle: false,
                showExport: false,
                showSearch: false,
                commonSearch: false
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        greeting_add: function () {
            Controller.api.bindevent();
        },
        greeting_edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            formatter: {
                is_def: function (value, row, index) {
                    return value == 0 ? '否' : '是';
                },
                type: function (value, row, index) {
                    return value == 'off' ? '离线专用' : '在线专用';
                }
            }
        }
    };
    return Controller;
});