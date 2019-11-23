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
}
