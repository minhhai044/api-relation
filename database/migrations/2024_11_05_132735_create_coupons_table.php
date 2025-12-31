<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('coupon_code')->unique()->comment('mã giảm giá');
            $table->boolean('discount_type')->default(0)->comment('0:% , 1:cố định');
            $table->float('discount_value')->default(0)->comment('tỉ lệ % or mức giá');
            $table->timestamp('start_date')->comment('ngày bắt đầu');
            $table->timestamp('end_date')->comment('ngày kết thúc');
            $table->integer('coupon_limit')->default(0)->comment('giới hạn số lượng');
            $table->integer('coupon_used')->default(0)->comment('số lần đã sử dụng');
            $table->boolean('coupon_status')->default(1)->comment('trạng thái');
            $table->text('coupon_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
