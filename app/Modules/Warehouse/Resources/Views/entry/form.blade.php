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
    <form class="layui-form layui-form-pane" lay-filter="entry">
        <div class="layui-row">
            <div class="layui-col-xs4">
                <div class="layui-form-item">
                    <label class="layui-form-label required">订单</label>
                    <div class="layui-input-block">
                        <select name="order_item_id" lay-verify="required" lay-reqText="请选择订单">
                            <option value="">请选择订单</option>
                            @foreach($sku->purchase_order_items as $order_item)
                                <?php $order = $order_item->order; ?>
                                <option value="{{$order_item->id}}">{{$order->code}} (供应商: {{$order->supplier->name or ''}}, 待入库数量: {{$order_item->quantity - $order_item->entried_quantity}})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label required">入库数量</label>
                    <div class="layui-input-block">
                        <input type="text" name="quantity" lay-verify="required|number" lay-reqText="请输入入库数量" class="layui-input">
                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="layui-btn" lay-submit lay-filter="entry">提交</button>
        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </form>
@endsection
@section('scripts')
    <script>
        var order_items = <?= json_encode(array_column($sku->purchase_order_items->toArray(), null, 'id')) ?>;
        layui.use(['form'], function () {
            var form = layui.form;

            form.on('submit(entry)', function (form_data) {
                var quantity = parseInt(form_data.field.quantity);
                var pending_quantity = parseInt(order_items[form_data.field.order_item_id]['quantity']) - parseInt(order_items[form_data.field.order_item_id]['entried_quantity']);

                if (quantity > pending_quantity) {
                    layer.msg("入库数量不能大于待入库数量", {icon: 5, shift: 6});
                    return false;
                }
                var load_index = layer.load();
                $.ajax({
                    method: "post",
                    url: "{{route('warehouse::entry.save')}}",
                    data: form_data.field,
                    success: function (data) {
                        layer.close(load_index);
                        if ('success' == data.status) {
                            layer.msg('入库成功', {icon: 1, time: 2000}, function(){
                                parent.layui.admin.closeThisTabs();
                            });
                        } else {
                            layer.msg("入库失败:" + data.msg, {icon: 2});
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