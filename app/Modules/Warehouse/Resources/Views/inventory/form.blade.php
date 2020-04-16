@extends('layouts.default')
@section('css')
    <style>
        .layui-table th, .layui-table tr{text-align: center;}
        .layui-table td{padding: 0;}
        .layui-table .layui-input{border: 0; text-align: center;}
        tbody tr{height: 40px!important;}
    </style>
@endsection
@section('content')
    <div class="layui-row">
        <div class="layui-col-xs6">
            <form class="layui-form" lay-filter="inventory">
                <table class="layui-table">
                    <thead>
                    <tr>
                        <th width="150">SKU编号</th>
                        <th class="required">库存数量</th>
                        <th>最高库存</th>
                        <th>最低库存</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($product->skus as $sku)
                        <tr>
                            <td>{{$sku->code}}</td>
                            <td><input type="text" name="inventory[{{$sku->id}}][stock]" class="layui-input" placeholder="库存数量(必填)" lay-verify="required" lay-reqText="请输入库存数量" value="{{$sku->inventory->stock or ''}}" oninput="value=value.replace(/[^\d]/g, '')"></td>
                            <td><input type="text" name="inventory[{{$sku->id}}][highest_stock]" class="layui-input" placeholder="最高库存" value="{{$sku->inventory->highest_stock or ''}}" oninput="value=value.replace(/[^\d]/g, '')"></td>
                            <td><input type="text" name="inventory[{{$sku->id}}][lowest_stock]" class="layui-input" placeholder="最低库存" value="{{$sku->inventory->lowest_stock or ''}}" oninput="value=value.replace(/[^\d]/g, '')"></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <button type="button" class="layui-btn" lay-submit lay-filter="inventory">提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        layui.use(['form'], function () {
            var form = layui.form;

            form.on('submit(inventory)', function (form_data) {
                var load_index = layer.load();
                $.ajax({
                    method: "post",
                    url: "{{route('warehouse::inventory.save')}}",
                    data: form_data.field,
                    success: function (data) {
                        layer.close(load_index);
                        if ('success' == data.status) {
                            layer.msg("库存设置成功", {icon: 1, time: 2000}, function () {
                                parent.layui.admin.closeThisTabs();
                            });
                        } else {
                            layer.msg("库存设置失败:"+data.msg, {icon: 2, time: 2000});
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