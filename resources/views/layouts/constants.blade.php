<script>
    var YES = parseInt("{{YES}}")
            ,NO = parseInt("{{NO}}")
            ,Payment_CASH = parseInt("{{\Payment::CASH}}")
            ,Payment_REMITTANCE = parseInt("{{\Payment::REMITTANCE}}")
            ,Payment_CHECK = parseInt("{{\Payment::CHECK}}")
            ,PaymentMethod_CASH = parseInt("{{\PaymentMethod::CASH}}")
            ,PaymentMethod_CREDIT = parseInt("{{\PaymentMethod::CREDIT}}")
            ,PaymentMethod_MONTHLY = parseInt("{{\PaymentMethod::MONTHLY}}")
            ,Tax_NONE = parseInt("{{\Tax::NONE}}")
            ,Tax_THREE = parseInt("{{\Tax::THREE}}")
            ,Tax_SEVENTEEN = parseInt("{{\Tax::SEVENTEEN}}")
            ,Category_PRODUCT = parseInt("{{\App\Modules\Category\Models\Category::PRODUCT}}")
            ,Category_GOODS = parseInt("{{\App\Modules\Category\Models\Category::GOODS}}")
            ,Goods_SINGLE = parseInt("{{\App\Modules\Goods\Models\Goods::SINGLE}}")
            ,Goods_COMBO = parseInt("{{\App\Modules\Goods\Models\Goods::COMBO}}")
            ,User_MALE = parseInt("{{\App\Modules\Index\Models\User::MALE}}")
            ,User_FEMALE = parseInt("{{\App\Modules\Index\Models\User::FEMALE}}")
            ,PurchaseOrder_PENDING_REVIEW = parseInt("{{\App\Modules\Purchase\Models\PurchaseOrder::PENDING_REVIEW}}")
            ,PurchaseOrder_REJECTED = parseInt("{{\App\Modules\Purchase\Models\PurchaseOrder::REJECTED}}")
            ,PurchaseOrder_AGREED = parseInt("{{\App\Modules\Purchase\Models\PurchaseOrder::AGREED}}")
            ,PurchaseOrder_FINISHED = parseInt("{{\App\Modules\Purchase\Models\PurchaseOrder::FINISHED}}")
            ,PurchaseOrder_CANCELED = parseInt("{{\App\Modules\Purchase\Models\PurchaseOrder::CANCELED}}")
            ,PurchaseReturnOrder_AGREED = parseInt("{{\App\Modules\Purchase\Models\PurchaseReturnOrder::AGREED}}")
            ,PurchaseReturnOrder_EGRESSED = parseInt("{{\App\Modules\Purchase\Models\PurchaseReturnOrder::EGRESSED}}")
            ,PurchaseReturnOrder_FINISHED = parseInt("{{\App\Modules\Purchase\Models\PurchaseReturnOrder::FINISHED}}")
            ,PurchaseReturnOrder_CANCELED = parseInt("{{\App\Modules\Purchase\Models\PurchaseReturnOrder::CANCELED}}")
            ,DeliveryOrder_BY_SELF = parseInt("{{\App\Modules\Sale\Models\DeliveryOrder::BY_SELF}}")
            ,DeliveryOrder_SEND = parseInt("{{\App\Modules\Sale\Models\DeliveryOrder::SEND}}")
            ,DeliveryOrder_EXPRESS = parseInt("{{\App\Modules\Sale\Models\DeliveryOrder::EXPRESS}}")
            ,DeliveryOrder_PENDING_REVIEW = parseInt("{{\App\Modules\Sale\Models\DeliveryOrder::PENDING_REVIEW}}")
            ,DeliveryOrder_PENDING_DELIVERY = parseInt("{{\App\Modules\Sale\Models\DeliveryOrder::PENDING_DELIVERY}}")
            ,DeliveryOrder_FINISHED = parseInt("{{\App\Modules\Sale\Models\DeliveryOrder::FINISHED}}")
            ,Order_PENDING_REVIEW = parseInt("{{\App\Modules\Sale\Models\Order::PENDING_REVIEW}}")
            ,Order_REJECTED = parseInt("{{\App\Modules\Sale\Models\Order::REJECTED}}")
            ,Order_AGREED = parseInt("{{\App\Modules\Sale\Models\Order::AGREED}}")
            ,Order_FINISHED = parseInt("{{\App\Modules\Sale\Models\Order::FINISHED}}")
            ,Order_CANCELED = parseInt("{{\App\Modules\Sale\Models\Order::CANCELED}}")
            ,Order_PENDING_PAYMENT = parseInt("{{\App\Modules\Sale\Models\Order::PENDING_PAYMENT}}")
            ,Order_FINISHED_PAYMENT = parseInt("{{\App\Modules\Sale\Models\Order::FINISHED_PAYMENT}}")
            ,PaymentMethodApplication_PENDING_REVIEW = parseInt("{{\App\Modules\Sale\Models\PaymentMethodApplication::PENDING_REVIEW}}")
            ,PaymentMethodApplication_REJECTED = parseInt("{{\App\Modules\Sale\Models\PaymentMethodApplication::REJECTED}}")
            ,PaymentMethodApplication_AGREED = parseInt("{{\App\Modules\Sale\Models\PaymentMethodApplication::AGREED}}")
            ,ReturnOrder_PENDING_REVIEW = parseInt("{{\App\Modules\Sale\Models\ReturnOrder::PENDING_REVIEW}}")
            ,ReturnOrder_REJECTED = parseInt("{{\App\Modules\Sale\Models\ReturnOrder::REJECTED}}")
            ,ReturnOrder_AGREED = parseInt("{{\App\Modules\Sale\Models\ReturnOrder::AGREED}}")
            ,ReturnOrder_ENTRIED = parseInt("{{\App\Modules\Sale\Models\ReturnOrder::ENTRIED}}")
            ,ReturnOrder_FINISHED = parseInt("{{\App\Modules\Sale\Models\ReturnOrder::FINISHED}}")
            ,ReturnOrder_CANCELED = parseInt("{{\App\Modules\Sale\Models\ReturnOrder::CANCELED}}")
            ,ReturnOrder_EXCHANGE = parseInt("{{\App\Modules\Sale\Models\ReturnOrder::EXCHANGE}}")
            ,ReturnOrder_BACK = parseInt("{{\App\Modules\Sale\Models\ReturnOrder::BACK}}")
</script>