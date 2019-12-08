@extends('layouts.default')
@section('css')
    <style>
        .layui-table th, .layui-table tr{text-align: center;}
        #contactTable tbody td{padding: 0;}
        #contactTable tbody tr{height: 40px;}
        #contactTable .layui-input{border: 0;}
        .layui-input-block>.layui-col-xs4:not(:first-child)>.layui-form-select .layui-input{border-left: 0;}
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
                                <input type="text" name="name" lay-verify="required" lay-reqText="请输入名称" class="layui-input" value="{{$customer->code or ''}}">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">编号</label>
                            <div class="layui-input-block">
                                <input type="text" name="code" lay-verify="required" lay-reqText="请输入编号" class="layui-input" value="{{$customer->name or ''}}">
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
                                <div class="layui-col-xs4 @if(empty($customer->city_id)) layui-hide @endif">
                                    <select name="city_id" lay-search="" lay-filter="city">
                                        @if(!empty($customer->city_id))
                                            <option value="">请选择市</option>
                                            @foreach($chinese_regions[$customer->state_id]['children'] as $city)
                                                <option value="{{$city['id']}}" @if($customer->city_id == $city['id']) selected @endif>{{$city['name']}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="layui-col-xs4 @if(empty($customer->county_id)) layui-hide @endif">
                                    <select name="county_id" lay-search="" lay-filter="county">
                                        @if(!empty($customer->county_id))
                                            <option value="">请选择县/区</option>
                                            @foreach($chinese_regions[$customer->state_id]['children'][$customer->city_id]['children'] as $county)
                                                <option value="{{$county['id']}}" @if($customer->county_id == $county['id']) selected @endif>{{$county['name']}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="layui-col-xs12 @if(empty($customer->county_id)) layui-hide @endif" style="margin-top: 15px;">
                                    <input type="text" name="street_address" class="layui-input" placeholder="街道地址" value="{{$customer->street_address or ''}}">
                                </div>
                            </div>
                        </div>
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
        var chinese_regions = <?= json_encode($chinese_regions); ?>;
        layui.use(['form'], function () {
            var form = layui.form;
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
                            layer.msg("客户添加成功", {icon:1});
                            location.reload();
                        } else {
                            layer.msg("客户添加失败:"+data.msg, {icon:2});
                            return false;
                        }
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        layer.close(load_index);
                        layer.msg(packageValidatorResponseText(XMLHttpRequest.responseText), {icon:2});
                        return false;
                    }
                });
            })
        });
    </script>
@endsection