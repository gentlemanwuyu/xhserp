@extends('layouts.default')
@section('content')
    <div class="erp-detail">
        <div class="erp-detail-title">
            <fieldset class="layui-elem-field layui-field-title">
                <legend>基本信息</legend>
            </fieldset>
        </div>
        <div class="erp-detail-content">
            <div class="layui-row layui-col-space30">
                <div class="layui-col-xs4">
                    <table class="layui-table erp-info-table">
                        <tr>
                            <td>名称</td>
                            <td>{{$customer->name or ''}}</td>
                        </tr>
                        <tr>
                            <td>编号</td>
                            <td>{{$customer->code or ''}}</td>
                        </tr>
                        <tr>
                            <td>公司</td>
                            <td>{{$customer->company or ''}}</td>
                        </tr>
                        <tr>
                            <td>电话</td>
                            <td>{{$customer->phone or ''}}</td>
                        </tr>
                        <tr>
                            <td>传真</td>
                            <td>{{$customer->fax or ''}}</td>
                        </tr>
                    </table>
                </div>
                <div class="layui-col-xs4">
                    <table class="layui-table erp-info-table">
                        <tr>
                            <td>税率</td>
                            <td>{{$customer->tax_name or ''}}</td>
                        </tr>
                        <tr>
                            <td>付款方式</td>
                            <td>{{$customer->payment_method_name or ''}}</td>
                        </tr>
                        @if(2 == $customer->payment_method)
                            <tr>
                                <td>额度</td>
                                <td>{{$customer->credit or ''}}</td>
                            </tr>
                        @elseif(3 == $customer->payment_method)
                            <tr>
                                <td>月结天数</td>
                                <td>{{$customer->monthly_day or ''}}</td>
                            </tr>
                        @endif
                        <tr>
                            <td>地址</td>
                            <td>{{$customer->full_address or ''}}</td>
                        </tr>
                        <tr>
                            <td>负责人</td>
                            <td>{{$customer->manager->name or ''}}</td>
                        </tr>
                        <tr>
                            <td>简介</td>
                            <td>{{$customer->desc or ''}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="erp-detail">
        <div class="erp-detail-title">
            <fieldset class="layui-elem-field layui-field-title">
                <legend>联系人</legend>
            </fieldset>
        </div>
        <div class="erp-detail-content">
            <div class="layui-row layui-col-space30">
                <div class="layui-col-xs4">
                    <table class="layui-table">
                        <thead>
                        <tr>
                            <th>序号</th>
                            <th>名称</th>
                            <th>职位</th>
                            <th>电话</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $index = 1; ?>
                        @foreach($customer->contacts as $contact)
                            <tr>
                                <td>{{$index++}}</td>
                                <td>{{$contact->name or ''}}</td>
                                <td>{{$contact->position or ''}}</td>
                                <td>{{$contact->phone or ''}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection