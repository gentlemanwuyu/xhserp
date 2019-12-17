/**
 * Created by websir on 2018/1/24.
 */
layui.define(function (exports) {

    //加载css
    var getPath = function (){
        var jsPath = document.currentScript ? document.currentScript.src : function(){
            var js = document.scripts
                ,last = js.length - 1
                ,src;
            for(var i = last; i > 0; i--){
                if(js[i].readyState === 'interactive'){
                    src = js[i].src;
                    break;
                }
            }
            return src || js[last].src;
        }();
        return jsPath.substring(0, jsPath.lastIndexOf('/') + 1);
    }();
    $("<link>").attr({
        rel: "stylesheet",
        type: "text/css",
        href: getPath + "/autocomplete.css"
    }).appendTo("head");


    var autocomplete = {
        v: "1.0.0",
        init: function (config) {
            var AT = new Class(config);
            AT.init();
            return AT;
        }
    };

    var Class = function (config) {
        this.$ul = $('<ul class="autocomplete-list"></ul>');
        this.config = $.extend({
            textLength: 3,//触发长度
            delay: 300//触发延迟
        }, config);
    };

    //定位
    Class.prototype.offset = function () {
        var that = this;
        var offset = that.$elem.offset();
        that.$ul.css({
            position: "absolute",
            left: offset.left,
            top: offset.top + that.$elem.height(),
            width: that.$elem.outerWidth()
        });
    };

    //初始化
    Class.prototype.init = function () {
        var that = this;
        that.$elem = $(that.config.elem);
        var $elem = that.$elem;
        var $ul = that.$ul;
        var longKeyDownTimer = null;//定时器
        var longKey = false;
        $elem.on("keyup", function (e) {
            longKey = false;
            clearInterval(longKeyDownTimer);
            if (e.keyCode == 38 || e.keyCode == 40) {
                e.preventDefault();
            }
        });

        //向上选择
        function up() {
            if (!$ul.find(".active").length || $ul.find(".active").prev().length == 0) {
                $ul.find("li:last").addClass("active").siblings().removeClass("active");
                return false;
            }
            $ul.find(".active").prev().addClass("active").siblings().removeClass("active");
        }

        //向下选择
        function down() {
            if (!$ul.find(".active").length || $ul.find(".active").next().length == 0) {
                $ul.find("li:first").addClass("active").siblings().removeClass("active");
                return false;
            }
            $ul.find(".active").next().addClass("active").siblings().removeClass("active");
        }

        function listRender(input) {
            clearTimeout(that.delayTimer);
            that.delayTimer = setTimeout(function () {
                that.config.callback.data(input, function (data) {
                    that.data = data;
                    var tpl = "";
                    data.map(function (item, index) {
                        if (!input) {
                            tpl += '<li data-id="' + index + '" data-value="' + item.value + '">' + item.title + '</li>';
                        }else if (-1 < item.title.indexOf(input)) {
                            tpl += '<li data-id="' + index + '" data-value="' + item.value + '">' + item.title + '</li>';
                        }
                    });
                    $ul.html(tpl);
                    that.offset();
                    $ul.remove();
                    $ul.appendTo($("body")).show();
                    that.bindDom();
                });
            }, that.config.delay);
        }

        //用户输入/键盘选择
        $elem.on("keyup", function (e) {
            clearInterval(longKeyDownTimer);
            if (e.keyCode == 38) {//向上
                up();
                longKeyDownTimer = setInterval(up, 800);
                return false;
            }
            if (e.keyCode == 40) {//向下
                down();
                longKeyDownTimer = setInterval(down, 800);
                return false;
            }
            if (e.keyCode == 13) {//回车
                $ul.find(".active").trigger("click");
                e.preventDefault();
                return false;
            }

            listRender($(this).val());
        });

        $elem.on('focus', function () {
            listRender($(this).val());
        });

        $elem.on("blur", function () {
            setTimeout(function () {
                $ul.remove();
            }, 200)
        })
    };

    //绑定事件
    Class.prototype.bindDom = function () {
        var that = this;
        var $ul = that.$ul;
        $ul.on("mouseenter", "li", liHover);
        $ul.on("click", "li", liClick);

        function liClick(e) {
            var callbackData = that.data[$(this).data("id")];
            if (that.config.callback.selected) {
                that.config.callback.selected(callbackData);
            }
            that.$elem.val(callbackData.value);
            that.$ul.remove();
            e.stopPropagation();
        }

        function liHover() {
            $(this).addClass("active").siblings().removeClass("active");
        }

    };

    exports('autocomplete', autocomplete);
});