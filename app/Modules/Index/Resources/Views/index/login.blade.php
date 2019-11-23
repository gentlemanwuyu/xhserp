<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>登录 | 汉生集团管理系统</title>
    <link rel="stylesheet" href="{{asset('/assets/layui-src/dist/css/layui.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('/assets/layuiadmin/style/login.css')}}" media="all">
    <style>
        .layadmin-user-login-header h2 {
            margin-top: 15px;
            color: rgba(0, 0, 0, .7);
        }
        .layadmin-user-login-footer a {
            color: #1E9FFF;
        }
        .layadmin-user-login-footer a:hover {
            color: #FF5722;
        }
    </style>
</head>
<body class="layui-layout-body">
<div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login">
    <div class="layadmin-user-login-main">
        <div class="layadmin-user-login-box layadmin-user-login-header">
            <img src="{{asset('/assets/images/xhs-logo.png')}}" alt="logo">
            <h2>汉生集团管理系统</h2>
        </div>
        <form action="" method="post">
            <input name="_token" type="hidden" value="{!! csrf_token() !!}" />
            <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-username" for="LAY-user-login-username"></label>
                    <input type="text" name="email" placeholder="邮箱" class="layui-input" required oninvalid="setCustomValidity('请输入用户名')" oninput="setCustomValidity('');">
                </div>
                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-password" for="LAY-user-login-password"></label>
                    <input type="password" name="password" placeholder="密码" class="layui-input" required oninvalid="setCustomValidity('请输入密码')" oninput="setCustomValidity('');">
                </div>
                <div class="layui-form-item">
                    <div class="layui-row">
                        <div class="layui-col-xs7">
                            <label class="layadmin-user-login-icon layui-icon layui-icon-vercode" for="LAY-user-login-vercode"></label>
                            <input type="text" name="captcha" placeholder="验证码" class="layui-input" required oninvalid="setCustomValidity('请输入验证码')" oninput="setCustomValidity('');">
                        </div>
                        <div class="layui-col-xs5">
                            <div style="margin-left: 10px;">
                                <img src="{{\Mews\Captcha\Facades\Captcha::src('woozee')}}" class="layadmin-user-login-codeimg" alt="captcha" onclick="this.src='{{\Mews\Captcha\Facades\Captcha::src('woozee')}}?'+Math.random()">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <button type="submit" class="layui-btn layui-btn-fluid">登 入</button>
                </div>
            </div>
        </form>
    </div>

    <div class="layui-trans layadmin-user-login-footer">
        <p>© 2019 Powered by <a href="https://www.woozee.com.cn/" target="_blank">吴宇</a></p>
    </div>
</div>
<script src="{{asset('/assets/layui-src/dist/layui.js')}}"></script>
<script>
    layui.use(['form', 'layer'], function(){
        @if (isset($errors) && count($errors) > 0)
            layui.layer.msg("{{implode('<br>', $errors->all())}}", {icon:2});
        @endif
    });
</script>
</body>
</html>