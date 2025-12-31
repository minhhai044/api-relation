<?php

if (! function_exists('statusOrders')) {
    function statusOrders($value)
    {
        switch ($value) {
            case STATUS_ORDER_PENDING:
                return "Chờ xác nhận";
            case STATUS_ORDER_CONFIRMED:
                return "Đã xác nhận";
            case STATUS_ORDER_PREPARING_GOODS:
                return "Đang chuẩn bị hàng";
            case STATUS_ORDER_SHIPPING:
                return "Đang vận chuyển";
            case STATUS_ORDER_DELIVERED:
                return "Đã giao hàng";
            case STATUS_ORDER_CANCELED:
                return "Đơn hàng đã bị hủy";
        }
    }
}
if (! function_exists('methodPayment')) {

    function methodPayment($value)
    {
        switch ($value) {
            case METHOD_PAYMENT_VNPAY:
                return "Thanh toán VNPay";
            case METHOD_PAYMENT_DELIVERY:
                return "Thanh toán khi nhận hàng";
        }
    }
}


if (! function_exists('statusPayment')) {

    function statusPayment($value)
    {
        switch ($value) {
            case STATUS_PAYMENT_PAID:
                return "Đã thanh toán";
                
            case STATUS_PAYMENT_UNPAID:
                return "Chưa thanh toán";
        }
    }
}
define('STATUS_ORDER_PENDING', 'pending');
define('STATUS_ORDER_CONFIRMED', 'confirmed');
define('STATUS_ORDER_PREPARING_GOODS', 'preparing_goods');
define('STATUS_ORDER_SHIPPING', 'shipping');
define('STATUS_ORDER_DELIVERED', 'delivered');
define('STATUS_ORDER_CANCELED', 'canceled');

define('METHOD_PAYMENT_DELIVERY', 'cash_delivery');
define('METHOD_PAYMENT_VNPAY', 'vnpay_payment');

define('STATUS_PAYMENT_UNPAID', 'unpaid');
define('STATUS_PAYMENT_PAID', 'paid');
