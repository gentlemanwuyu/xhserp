var $ = layui.$
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
};
// 绑定打开新tab事件
$('a[lay-href]').on('click', function () {
    var othis = $(this)
        ,href = othis.attr('lay-href')
        ,text = othis.attr('lay-text');

    //执行跳转
    var topLayui = parent === self ? layui : top.layui;
    topLayui.index.openTabsPage(href, text || othis.text());
});
