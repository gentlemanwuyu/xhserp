@extends('layouts.default')
@section('content')
    <form class="layui-form layui-form-pane" lay-filter="entry">
        <div class="layui-card">
            <div class="layui-card-header">
                <h3>SKU入库</h3>
            </div>
            <div class="layui-card-body">
                <div class="layui-row layui-col-space30">
                    <div class="layui-col-xs4">
                        <div class="layui-form-item">
                            <label class="layui-form-label required">订单</label>
                            <div class="layui-input-block">
                                <select name="order_item_id" lay-verify="required" lay-reqText="请选择订单" lay-filter="orderItem">
                                    <option value="">请选择订单</option>
                                    @foreach($sku->pois as $order_item)
                                        <?php $order = $order_item->purchaseOrder; ?>
                                        <option value="{{$order_item->id}}">{{$order->code}} (供应商: {{$order->supplier->name or ''}}, 待入库数量: {{$order_item->pending_entry_quantity}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label required">入库数量</label>
                            <div class="layui-input-block">
                                <input type="text" name="quantity" placeholder="入库数量" lay-verify="required|number" lay-reqText="请输入入库数量" class="layui-input">
                            </div>
                        </div>
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
        var order_items = <?= json_encode($sku->pois) ?>;
        layui.use(['form'], function () {
            var form = layui.form;

            form.on('select(orderItem)', function (data) {
                var $formItem = $(data.elem).parents('.layui-form-item');
                if (data.value) {
                    var orderItem = order_items[data.value], html = '';
                    html += '<div class="layui-form-item" erp-form-item="supplier">';
                    html += '<label class="layui-form-label">供应商</label>';
                    html += '<div class="layui-input-block">';
                    html += '<span class="erp-form-span">' + orderItem.purchase_order.supplier.name + '</span>';
                    html += '</div>';
                    html += '</div>';
                    html += '<div class="layui-form-item" erp-form-item="pendingQuantity">';
                    html += '<label class="layui-form-label">待入库</label>';
                    html += '<div class="layui-input-block">';
                    html += '<span class="erp-form-span">' + orderItem.pending_entry_quantity + '</span>';
                    html += '</div>';
                    html += '</div>';
                    $formItem.after(html);
                }else {
                    $('.layui-form-item[erp-form-item=supplier]').remove();
                    $('.layui-form-item[erp-form-item=pendingQuantity]').remove();
                }
            });

            form.on('submit(entry)', function (form_data) {
                var quantity = parseInt(form_data.field.quantity)
                        ,pending_quantity = order_items[form_data.field.order_item_id]['pending_entry_quantity'];

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