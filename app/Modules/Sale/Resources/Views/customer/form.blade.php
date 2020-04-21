@extends('layouts.default')
@section('css')
    <style>
        .layui-table th, .layui-table tr{text-align: center;}
        #contactTable tbody td{padding: 0;}
        #contactTable tbody tr{height: 40px;}
        #contactTable .layui-input{border: 0;}
        .layui-input-block>.layui-col-xs4:not(:first-child)>.layui-form-select .layui-input{border-left: 0;}
        .layui-input-block .layui-inline{margin-right: 0;}
        .layui-card-header a{font-size: 12px;margin-left: 5px;}
    </style>
@endsection
@section('content')
    <form class="layui-form layui-form-pane" lay-filter="customer">
        @if(isset($customer_id))
            <input type="hidden" name="customer_id" value="{{$customer_id}}">
        @endif
        <div class="layui-card">
            <div class="layui-card-header">
                <h3>基本信息</h3>
            </div>
            <div class="layui-card-body">
                <div class="layui-row layui-col-space30">
                    <div class="layui-col-xs4">
                        <div class="layui-form-item">
                            <label class="layui-form-label required">名称</label>
                            <div class="layui-input-block">
                                <input type="text" name="name" lay-verify="required" lay-reqText="请输入名称" class="layui-input" value="{{$customer->name or ''}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">编号</label>
                            <div class="layui-input-block">
                                <input type="text" name="code" lay-verify="required" lay-reqText="请输入编号" class="layui-input" value="{{$customer->code or ''}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">公司</label>
                            <div class="layui-input-block">
                                <input type="text" name="company" class="layui-input" value="{{$customer->company or ''}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">电话</label>
                            <div class="layui-input-block">
                                <input type="text" name="phone" class="layui-input" value="{{$customer->phone or ''}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">传真</label>
                            <div class="layui-input-block">
                                <input type="text" name="fax" class="layui-input" value="{{$customer->fax or ''}}">
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-xs4">
                        <div class="layui-form-item">
                            <label class="layui-form-label required">税率</label>
                            <div class="layui-input-block">
                                <select name="tax" lay-verify="required" lay-reqText="请选择税率">
                                    <option value="">请选择税率</option>
                                    @foreach(\App\Modules\Sale\Models\Customer::$taxes as $tax_id => $val)
                                        <option value="{{$tax_id}}" @if(isset($customer) && $tax_id == $customer->tax) selected @endif>{{$val['display']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">币种</label>
                            <div class="layui-input-block">
                                <select name="currency_code" lay-search="" lay-verify="required" lay-reqText="请选择币种">
                                    <option value="">请选择币种</option>
                                    @foreach($currencies as $currency)
                                        <option value="{{$currency['code']}}" @if(isset($customer) && $currency['code'] == $customer->currency_code) selected @endif>{{$currency['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">地址</label>
                            <div class="layui-input-block">
                                <div class="layui-col-xs4">
                                    <select name="state_id" lay-search="" lay-filter="state">
                                        <option value="">请选择省/洲</option>
                                        @foreach($chinese_regions as $region)
                                            <option value="{{$region['id']}}" @if(!empty($customer->state_id) && $region['id'] == $customer->state_id) selected @endif>{{$region['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="layui-col-xs4 @if(empty($customer) || empty($chinese_regions[$customer->state_id]['children'])) layui-hide @endif">
                                    <select name="city_id" lay-search="" lay-filter="city">
                                        @if(!empty($customer) && !empty($chinese_regions[$customer->state_id]['children']))
                                            <option value="">请选择市</option>
                                            @foreach($chinese_regions[$customer->state_id]['children'] as $city)
                                                <option value="{{$city['id']}}" @if($customer->city_id == $city['id']) selected @endif>{{$city['name']}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="layui-col-xs4 @if(empty($customer) || empty($chinese_regions[$customer->state_id]['children'][$customer->city_id]['children'])) layui-hide @endif">
                                    <select name="county_id" lay-search="" lay-filter="county">
                                        @if(!empty($customer) && !empty($chinese_regions[$customer->state_id]['children'][$customer->city_id]['children']))
                                            <option value="">请选择县/区</option>
                                            @foreach($chinese_regions[$customer->state_id]['children'][$customer->city_id]['children'] as $county)
                                                <option value="{{$county['id']}}" @if($customer->county_id == $county['id']) selected @endif>{{$county['name']}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <?php
                                    $flag = true;
                                    if (empty($customer->state_id)) {
                                        $flag = false; // 如果没有选择省份，街道地址不显示
                                    }else {
                                        if (empty($customer->city_id)) {
                                            // 如果城市为空，判断省份下是否有城市列表
                                            if (!empty($chinese_regions[$customer->state_id]['children'])) {
                                                $flag = false;
                                            }
                                        }else {
                                            if (empty($customer->county_id)) {
                                                // 如果县区为空，判断城市下是否有县区列表
                                                if (!empty($chinese_regions[$customer->state_id]['children'][$customer->city_id]['children'])) {
                                                    $flag = false;
                                                }
                                            }
                                        }
                                    }
                                ?>
                                <div class="layui-col-xs12 @if(!$flag) layui-hide @endif" style="margin-top: 15px;">
                                    <input type="text" name="street_address" class="layui-input" placeholder="街道地址" value="{{$customer->street_address or ''}}">
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item" pane="">
                            <label class="layui-form-label">放入客户池</label>
                            <div class="layui-input-block">
                                <input type="checkbox" name="in_pool" value="1" lay-skin="switch" lay-text="是|否" lay-filter="in_pool" @if(!empty($customer) && 0 == $customer->manager_id) checked @endif>
                            </div>
                        </div>
                    </div>
                    <div class="layui-col-xs4">
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">简介</label>
                            <div class="layui-input-block">
                                <textarea name="intro" class="layui-textarea">{{$customer->intro or ''}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-card">
            <div class="layui-card-header">
                <h3 style="display: inline;">付款方式</h3>
                @if(isset($customer) && !$customer->pendingPaymentMethodApplication)
                    <a href="javascript:;" erp-event="edit_payment_method">[更改]</a>
                @endif
            </div>
            <div class="layui-card-body">
                <div class="layui-row layui-col-space30">
                    @if(isset($customer))
                        <div class="layui-col-xs4">
                            <div class="layui-form-item">
                                <label class="layui-form-label">付款方式</label>
                                <div class="layui-input-block">
                                    <span class="erp-form-span">{{$customer->payment_method_name}}</span>
                                </div>
                            </div>
                            @if(2 == $customer->payment_method)
                                <div class="layui-form-item">
                                    <label class="layui-form-label">额度</label>
                                    <div class="layui-input-block">
                                        <span class="erp-form-span">{{$customer->credit}}</span>
                                    </div>
                                </div>
                            @elseif(3 == $customer->payment_method)
                                <div class="layui-form-item">
                                    <label class="layui-form-label">月结天数</label>
                                    <div class="layui-input-block">
                                        <span class="erp-form-span">{{$customer->monthly_day}}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="layui-col-xs4">
                            <div class="layui-form-item" pane="">
                                <label class="layui-form-label required">付款方式</label>
                                <div class="layui-input-block">
                                    @foreach(\App\Modules\Sale\Models\Customer::$payment_methods as $method_id => $method)
                                        <input type="radio" name="payment_method" value="{{$method_id}}" title="{{$method}}" lay-verify="checkReq" lay-reqText="请选择付款方式" lay-filter="payment_method" @if(isset($customer->payment_method) && $customer->payment_method == $method_id) checked @endif>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="layui-card">
            <div class="layui-card-header">
                <h3>联系人</h3>
            </div>
            <div class="layui-card-body">
                <table class="layui-table" id="contactTable">
                    <thead>
                    <tr>
                        <th class="required">名称</th>
                        <th>职位</th>
                        <th>电话</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($customer->contacts))
                        @foreach($customer->contacts as $contact)
                            <tr>
                                <td><input type="text" name="contacts[{{$contact->id}}][name]" placeholder="名称" lay-verify="required" lay-reqText="请输入名称" class="layui-input" value="{{$contact->name or ''}}"></td>
                                <td><input type="text" name="contacts[{{$contact->id}}][position]" placeholder="职位" class="layui-input" value="{{$contact->position or ''}}"></td>
                                <td><input type="text" name="contacts[{{$contact->id}}][phone]" placeholder="电话" class="layui-input" value="{{$contact->phone or ''}}"></td>
                                <td><button type="button" class="layui-btn layui-btn-sm layui-btn-danger" onclick="deleteRow(this);">删除</button></td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
            <div class="layui-card-body">
                <button type="button" class="layui-btn layui-btn-normal" lay-event="addContact">添加联系人</button>
            </div>
        </div>
        <button type="button" class="layui-btn" lay-submit lay-filter="customer">提交</button>
        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </form>
@endsection
@section('scripts')
    <script>
        var chinese_regions = <?= json_encode($chinese_regions); ?>
                ,payment_methods = <?= json_encode(\App\Modules\Sale\Models\Customer::$payment_methods); ?>;
        @if(isset($customer))
            var customer = <?= json_encode($customer); ?>;
        @else
            var customer = undefined;
        @endif
        layui.use(['form'], function () {
            var form = layui.form
                    // 更改付款方式监听
                    ,listenEditPaymentMethod = function () {
                $('*[erp-event=edit_payment_method]').on('click', function () {
                    var $this = $(this)
                            ,$cardBody = $this.parents('.layui-card').find('.layui-card-body');

                    $this.attr('erp-event', 'reset_payment_method').html('[撤销]');

                    var html = '';
                    html += '<div class="layui-row layui-col-space30">';
                    html += '<div class="layui-col-xs4">';
                    html += '<div class="layui-form-item" pane="">';
                    html += '<label class="layui-form-label required">付款方式</label>';
                    html += '<div class="layui-input-block">';
                    $.each(payment_methods, function (method_id, method_name) {
                        html += '<input type="radio" name="payment_method" value="' + method_id + '" title="' + method_name + '" lay-verify="checkReq" lay-reqText="请选择付款方式" lay-filter="payment_method">';
                    });
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';

                    $cardBody.html(html);
                    form.render('radio', 'customer');
                    listenPaymentMethodRadio();
                    listenResetPaymentMethod();
                });
            }
                    ,listenResetPaymentMethod = function () {
                $('*[erp-event=reset_payment_method]').on('click', function () {
                    var $this = $(this)
                            ,$cardBody = $this.parents('.layui-card').find('.layui-card-body');

                    $this.attr('erp-event', 'edit_payment_method').html('[更改]');

                    var html = '';
                    if (customer) {
                        html += '<div class="layui-row layui-col-space30">';
                        html += '<div class="layui-col-xs4">';
                        html += '<div class="layui-form-item">';
                        html += '<label class="layui-form-label">付款方式</label>';
                        html += '<div class="layui-input-block">';
                        html += '<span class="erp-form-span">' + customer.payment_method_name + '</span>';
                        html += '</div>';
                        html += '</div>';
                        if (2 == customer.payment_method) {
                            html += '<div class="layui-form-item">';
                            html += '<label class="layui-form-label">额度</label>';
                            html += '<div class="layui-input-block">';
                            html += '<span class="erp-form-span">' + customer.credit + '</span>';
                            html += '</div>';
                            html += '</div>';
                        }else if (3 == customer.payment_method) {
                            html += '<div class="layui-form-item">';
                            html += '<label class="layui-form-label">月结天数</label>';
                            html += '<div class="layui-input-block">';
                            html += '<span class="erp-form-span">' + customer.monthly_day + '</span>';
                            html += '</div>';
                            html += '</div>';
                        }
                        html += '</div>';
                        html += '</div>';
                    }

                    $cardBody.html(html);
                    listenEditPaymentMethod();
                });
            }
                    // 付款方式单选监听
                    ,listenPaymentMethodRadio = function () {
                form.on('radio(payment_method)', function(data){
                    var $paymentMethodItem = $(data.elem).parents('.layui-form-item');
                    $('input[name=credit]').parents('.layui-form-item').remove();
                    $('input[name=monthly_day]').parents('.layui-form-item').remove();
                    $('textarea[name=reason]').parents('.layui-form-item').remove();

                    if (2 == data.value) {
                        var html = '';
                        html += '<div class="layui-form-item">';
                        html += '<label class="layui-form-label required">额度</label>';
                        html += '<div class="layui-input-block">';
                        html += '<input type="text" name="credit" class="layui-input" placeholder="额度(元)" lay-verify="required" lay-reqText="请输入额度">';
                        html += '</div>';
                        html += '</div>';
                        html += '<div class="layui-form-item layui-form-text">';
                        html += '<label class="layui-form-label required">申请原因</label>';
                        html += '<div class="layui-input-block">';
                        html += '<textarea name="reason" class="layui-textarea" placeholder="申请原因" lay-verify="required" lay-reqText="请输入申请原因"></textarea>';
                        html += '</div>';
                        html += '</div>';

                        $paymentMethodItem.after(html);
                    }else if (3 == data.value) {
                        var html = '';
                        html += '<div class="layui-form-item">';
                        html += '<label class="layui-form-label required">月结天数</label>';
                        html += '<div class="layui-input-block">';
                        html += '<input type="text" name="monthly_day" class="layui-input" placeholder="月结天数" lay-verify="required" lay-reqText="请输入月结天数">';
                        html += '</div>';
                        html += '</div>';
                        html += '<div class="layui-form-item layui-form-text">';
                        html += '<label class="layui-form-label required">申请原因</label>';
                        html += '<div class="layui-input-block">';
                        html += '<textarea name="reason" class="layui-textarea" placeholder="申请原因" lay-verify="required" lay-reqText="请输入申请原因"></textarea>';
                        html += '</div>';
                        html += '</div>';

                        $paymentMethodItem.after(html);
                    }
                });
            };

            listenPaymentMethodRadio();
            listenEditPaymentMethod();

            form.on('switch(in_pool)', function(data){
                var checked = data.elem.checked
                        ,$baseInfoCard = $(data.elem).parents('.layui-card')
                        ,$paymentMethodCard = $baseInfoCard.next('.layui-card');

                if (customer) {
                    var html = '';
                    html += '<div class="layui-row layui-col-space30">';
                    html += '<div class="layui-col-xs4">';
                    html += '<div class="layui-form-item">';
                    html += '<label class="layui-form-label">付款方式</label>';
                    html += '<div class="layui-input-block">';
                    html += '<span class="erp-form-span">' + customer.payment_method_name + '</span>';
                    html += '</div>';
                    html += '</div>';
                    if (2 == customer.payment_method) {
                        html += '<div class="layui-form-item">';
                        html += '<label class="layui-form-label">额度</label>';
                        html += '<div class="layui-input-block">';
                        html += '<span class="erp-form-span">' + customer.credit + '</span>';
                        html += '</div>';
                        html += '</div>';
                    }else if (3 == customer.payment_method) {
                        html += '<div class="layui-form-item">';
                        html += '<label class="layui-form-label">月结天数</label>';
                        html += '<div class="layui-input-block">';
                        html += '<span class="erp-form-span">' + customer.monthly_day + '</span>';
                        html += '</div>';
                        html += '</div>';
                    }
                    html += '</div>';
                    html += '</div>';
                    $paymentMethodCard.find('.layui-card-body').html(html);
                    $paymentMethodCard.find('.layui-card-header a').remove();

                    if (!checked && !customer.pending_payment_method_application) {
                        $paymentMethodCard.find('.layui-card-header').append('<a href="javascript:;" erp-event="edit_payment_method">[更改]</a>');
                        listenEditPaymentMethod();
                    }
                }else {
                    if (checked) {
                        $paymentMethodCard.remove();
                    }else {
                        var html = '';
                        html += '<div class="layui-card">';
                        html += '<div class="layui-card-header">';
                        html += '<h3>付款方式</h3>';
                        html += '</div>';
                        html += '<div class="layui-card-body">';
                        html += '<div class="layui-row layui-col-space30">';
                        html += '<div class="layui-col-xs4">';
                        html += '<div class="layui-form-item" pane="">';
                        html += '<label class="layui-form-label required">付款方式</label>';
                        html += '<div class="layui-input-block">';
                        $.each(payment_methods, function (method_id, method_name) {
                            html += '<input type="radio" name="payment_method" value="' + method_id + '" title="' + method_name + '" lay-verify="checkReq" lay-reqText="请选择付款方式" lay-filter="payment_method">';
                        });
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        $baseInfoCard.after(html);
                        form.render('radio', 'customer');
                        listenPaymentMethodRadio();
                    }
                }
            });

            $('button[lay-event=addContact]').on('click', function () {
                var $body = $('#contactTable').find('tbody')
                        ,html = ''
                        ,flag = Date.now();
                html += '<tr>';
                html += '<td>';
                html += '<input type="text" name="contacts[' + flag + '][name]" placeholder="名称" lay-verify="required" lay-reqText="请输入名称" class="layui-input">';
                html += '</td>';
                html += '<td>';
                html += '<input type="text" name="contacts[' + flag + '][position]" placeholder="职位" class="layui-input">';
                html += '</td>';
                html += '<td>';
                html += '<input type="text" name="contacts[' + flag + '][phone]" placeholder="电话" class="layui-input">';
                html += '</td>';
                html += '<td>';
                html += '<button type="button" class="layui-btn layui-btn-sm layui-btn-danger" onclick="deleteRow(this);">删除</button>';
                html += '</td>';
                html += '</tr>';

                $body.append(html);
                form.render();
            });

            // 地址三级联动
            form.on('select(state)', function (data) {
                var value = data.value
                        ,$citySelect = $('select[name=city_id]')
                        ,$countySelect = $('select[name=county_id]')
                        ,$streetInput = $('input[name=street_address]');
                if (value) {
                    var cities = chinese_regions[value]['children'];
                    if (cities) {
                        var optsHtml = '';
                        optsHtml += '<option value="">请选择市</option>';
                        $.each(cities, function (_, city) {
                            optsHtml += '<option value="' + city.id + '">' + city.name + '</option>';
                        });
                        $citySelect.html(optsHtml);
                        $citySelect.parent().removeClass('layui-hide');
                        $countySelect.html('');
                        $countySelect.parent().addClass('layui-hide');
                        $streetInput.val('');
                        $streetInput.parent().addClass('layui-hide');
                    }else {
                        $citySelect.html('');
                        $citySelect.parent().addClass('layui-hide');
                        $countySelect.html('');
                        $countySelect.parent().addClass('layui-hide');
                        $streetInput.val('');
                        $streetInput.parent().removeClass('layui-hide');
                    }
                }else {
                    $citySelect.html('');
                    $citySelect.parent().addClass('layui-hide');
                    $countySelect.html('');
                    $countySelect.parent().addClass('layui-hide');
                    $streetInput.val('');
                    $streetInput.parent().addClass('layui-hide');
                }

                form.render('select', 'customer');
            });

            form.on('select(city)', function (data) {
                var value = data.value
                        ,$stateSelect = $('select[name=state_id]')
                        ,state_id = $stateSelect.val()
                        ,$countySelect = $('select[name=county_id]')
                        ,$streetInput = $('input[name=street_address]');

                if (value) {
                    var counties = chinese_regions[state_id]['children'][value]['children'];
                    if (counties) {
                        var optsHtml = '';
                        optsHtml += '<option value="">请选择县/区</option>';
                        $.each(counties, function (_, county) {
                            optsHtml += '<option value="' + county.id + '">' + county.name + '</option>';
                        });
                        $countySelect.html(optsHtml);
                        $countySelect.parent().removeClass('layui-hide');
                        $streetInput.val('');
                        $streetInput.parent().addClass('layui-hide');
                    }else {
                        $countySelect.html('');
                        $countySelect.parent().addClass('layui-hide');
                        $streetInput.val('');
                        $streetInput.parent().removeClass('layui-hide');
                    }
                }else {
                    $countySelect.html('');
                    $countySelect.parent().addClass('layui-hide');
                    $streetInput.val('');
                    $streetInput.parent().addClass('layui-hide');
                }

                form.render('select', 'customer');
            });

            form.on('select(county)', function(data){
                var $streetInput = $('input[name=street_address]');
                $streetInput.val('');
                if (data.value) {
                    $streetInput.parent().removeClass('layui-hide');
                }else {
                    $streetInput.parent().addClass('layui-hide');
                }

                form.render('select', 'customer');
            });

            form.on('submit(customer)', function (form_data) {
                var contact_exists = false;
                $.each(form_data.field, function (key, val) {
                    if (new RegExp(/^contacts\[[\d]+\]\[[\d\D]+\]$/).test(key)) {
                        contact_exists = true;
                        return false; // 跳出循环
                    }
                });

                if (!contact_exists) {
                    layer.msg("请至少添加一个联系人", {icon:2});
                    return false;
                }

                var load_index = layer.load();
                $.ajax({
                    method: "post",
                    url: "{{route('sale::customer.save')}}",
                    data: form_data.field,
                    success: function (data) {
                        layer.close(load_index);
                        if ('success' == data.status) {
                            layer.msg("客户保存成功", {icon: 1, time: 2000}, function () {
                                location.reload();
                            });
                        } else {
                            layer.msg("客户保存失败:"+data.msg, {icon: 2, time: 2000});
                            return false;
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        layer.close(load_index);
                        layer.msg(packageValidatorResponseText(XMLHttpRequest.responseText), {icon: 2, time: 2000});
                        return false;
                    }
                });
            })
        });
    </script>
@endsection