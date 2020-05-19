var $ = layui.$
    // 柱状图默认配置
    ,barDefaultOpts = {
    color: [
        '#009688','#1E9FFF','#c05050','#FFB980','#5FB878',
        '#D87A80','#8d98b3','#e5cf0d','#97b552','#95706d',
        '#dc69aa','#07a2a4','#9a7fd1','#588dd5','#f5994e',
        '#59678c','#c9ab00','#7eb00a','#6f5553','#c14089'
    ],

    textStyle: {
        fontFamily: '微软雅黑, Arial, Verdana, sans-serif'
    },
    xAxis: {
        axisLine: {
            lineStyle: {
                color: '#009688',
                width: 1.5
            }
        },
        axisTick: {
            show: false
        },
        splitLine: {
            show: true,
            lineStyle: {
                color: '#f2f2f2'
            }
        }
    },
    yAxis: {
        axisLine: {
            lineStyle: {
                color: '#009688',
                width: 1.5
            }
        },
        axisTick: {
            show: false
        },
        splitLine: {
            show: true,
            lineStyle: {
                color: '#f2f2f2'
            }
        }
    },
    tooltip: {
        backgroundColor: 'rgba(50,50,50,0.5)',   // 提示背景颜色，默认为透明度为0.7的黑色
        axisPointer : {      // 坐标轴指示器，坐标轴触发有效
            type : 'line',     // 默认为直线，可选为：'line' | 'shadow'
            lineStyle : {      // 直线指示器样式设置
                color: '#009688'
            },
            crossStyle: {
                color: '#008acd'
            },
            shadowStyle : {           // 阴影指示器样式设置
                color: 'rgba(200,200,200,0.2)'
            }
        }
    }
}
/**
 * 组装响应内容
 *
 * @param text
 * @returns {string}
 */
    ,packageValidatorResponseText = function (text) {
    text = JSON.parse(text);
    var message = [];
    $.each(text, function (key, val) {
        $.each(val, function (vk, vv) {
            message.push(vv);
        });
    });
    return message.join('<br>');
}
    // 删除表格中的一行
    ,deleteRow = function (obj) {
    var $tbody = $(obj).parents('tbody'), index = 1;
    $(obj).parents('tr').remove();
    $tbody.find('tr td[erp-col=index]').each(function () {
        $(this).html(index);
        index++;
    });
}
    ,array_column = function (arr, field) {
    var collection = [];
    arr.forEach(function (item) {
        collection.push(item[field]);
    });

    return collection;
}
    // 去除数组中的空值
    ,array_filter = function (arr) {
    return $.grep(arr, function (item) {
        return $.trim(item).length > 0;
    });
}

// 给所有的ajax请求加上csrf_token
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// 绑定打开新tab事件
$('a[lay-href]').on('click', function () {
    var othis = $(this)
        ,href = othis.attr('lay-href')
        ,text = othis.attr('lay-text');

    //执行跳转
    var topLayui = parent === self ? layui : top.layui;
    topLayui.index.openTabsPage(href, text || othis.text());
});

// 自定义表单验证规则
layui.form.config.verify = {
    required: function (value, item) {
        if(!new RegExp(/[\S]+/).test(value)){
            var label_text = $(item).parents('div.layui-form-item').find('label.layui-form-label').text();
            return label_text + ':不能为空';
        }
    }
    ,phone: function (value, item) {
        if(value && !new RegExp(/^1\d{10}$/).test(value)){
            var label_text = $(item).parents('div.layui-form-item').find('label.layui-form-label').text();
            return label_text + ':请输入正确的手机号';
        }
    }
    ,email: function (value, item) {
        if(value && !new RegExp(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/).test(value)){
            var label_text = $(item).parents('div.layui-form-item').find('label.layui-form-label').text();
            return label_text + ':邮箱格式不正确';
        }
    }
    ,url: function (value, item) {
        if(value && !new RegExp(/(^#)|(^http(s*):\/\/[^\s]+\.[^\s]+)/).test(value)){
            var label_text = $(item).parents('div.layui-form-item').find('label.layui-form-label').text();
            return label_text + ':链接格式不正确';
        }
    }
    ,number: function (value, item) {
        if(value && isNaN(value)){
            var label_text = $(item).parents('div.layui-form-item').find('label.layui-form-label').text();
            return label_text + ':只能填写数字';
        }
    }
    ,date: function (value, item) {
        if(value && !new RegExp(/^(\d{4})[-\/](\d{1}|0\d{1}|1[0-2])([-\/](\d{1}|0\d{1}|[1-2][0-9]|3[0-1]))*$/).test(value)){
            var label_text = $(item).parents('div.layui-form-item').find('label.layui-form-label').text();
            return label_text + ':日期格式不正确';
        }
    }
    ,identity: function (value, item) {
        if(value && !new RegExp(/(^\d{15}$)|(^\d{17}(x|X|\d)$)/).test(value)){
            var label_text = $(item).parents('div.layui-form-item').find('label.layui-form-label').text();
            return label_text + ':请输入正确的身份证号';
        }
    }
    ,alpha_dash: function (value, item) {
        if(value && !new RegExp("^[a-zA-Z0-9_\-]+$").test(value)){
            var label_text = $(item).parents('div.layui-form-item').find('label.layui-form-label').text();
            return label_text + '只能包含字母/数字/破折号-以及下划线_';
        }
    }
    ,checkReq: function (value, item) {
        var name = $(item).attr('name')
            ,$inputs = $('input[name=' + name + ']')
            ,checked = false
            ,label_text = $(item).parents('div.layui-form-item').find('label.layui-form-label').text();
        $inputs.each(function (_, input) {
            if ($(input).prop('checked')) {
                checked = true;
            }
        });

        if (!checked) {
            return '请选择' + label_text;
        }
    }
};
