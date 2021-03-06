<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('password')->nullable();
            $table->string('subject')->nullable();
            $table->string('number')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('project_id')->nullable();

            //type: ['sale', 'purchase']
            $table->string('type');

            $table->string('currency_code')->default(config('platform.currency'))->nullable();
            $table->double('currency_rate', 15, 8)->nullable();


            $table->decimal('total', 15, 4);
            $table->decimal('tax', 15, 4)->nullable();
            $table->decimal('interest', 15, 4)->nullable();
            $table->decimal('discount', 15, 4)->nullable();
            $table->decimal('shipping', 15, 4)->nullable();
            $table->decimal('quantity', 15, 4)->nullable();


            //Status
            //draft: نیاز به ویرایش دارد و پیش نویس است
            //submitted: فاکتوری است که خودمان ثبت کردیم و نیاز است مراحل پرداخت آن را تعریف کنیم و پس از ثبت ایمیل برای کاربر ارسال می شود که در صورت نیاز آن را آنلاین پرداخت کند.
            //approved:پردازش تایید شده است یعنی می توانیم کالا ها را ارسال کنیم و تایید ارسال فاکتور برای کاربر اطلاع می دهیم
            //paid:فاکتوری است که توسط کاربر پرداخت می شود و نیاز به پردازش دارد تا به حالت approved برود
            //payment: فاکتوری است که در حالت پرداخت است اقساطی، اعتباری و....
            //post:فاکتوری است که به پست تحویل شده است
            //done: فاکتوری است که کالا به دست مشتری رسیده است و کلیه فرآیند های آن از ارسال تا پرداخت انجام شده است.
            //return: فاکتور مرجوعی
            //status: ['draft', 'sent', 'submitted', 'approved', 'paid', 'done', 'post', 'payment', 'return', canceled]
            $table->string('status')->default('submitted');
            //payment:['cash','credit','installment', 'post']
            $table->string('payment')->nullable();
            //post: ['none', 'sent', 'delivered', 'ready', 'process']
            $table->string('ship_status')->nullable();



            //Information
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('zip_code')->nullable();
            $table->text('address')->nullable();
            $table->text('location')->nullable();
            $table->bigInteger('province_id')->nullable();
            $table->bigInteger('city_id')->nullable();
            $table->string('economical_number')->nullable();

            //Options
            $table->string('attachment')->nullable();
            $table->text('note')->nullable();
            $table->longText('options')->nullable();

            //Times
            $table->timestamp('invoice_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('description')->nullable();
            $table->decimal('price', 15, 4);
            $table->decimal('tax', 15, 4)->nullable()->default(0);
            $table->decimal('shipping', 15, 4)->nullable()->default(0);
            $table->decimal('discount', 15, 4)->nullable()->default(0);
            $table->decimal('quantity', 15, 4)->default(1);
            $table->decimal('total', 15, 4)->nullable()->default(0);
            $table->bigInteger('invoice_id');
            $table->bigInteger('product_id')->nullable();
            $table->bigInteger('service_id')->nullable();
            $table->longText('options')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('records');
    }
}
