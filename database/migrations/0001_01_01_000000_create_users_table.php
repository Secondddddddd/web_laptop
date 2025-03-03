<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('user_id')->autoIncrement(); // INT PRIMARY KEY
            $table->string('full_name', 100);
            $table->string('avatar', 100)->default('avatar_default.jpg');
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('phone', 15)->nullable();
            $table->text('address')->nullable();
            $table->enum('role', ['customer', 'admin', 'staff'])->default('customer');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });


        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->integer('user_id')->nullable()->index(); // Dùng INT thay vì unsignedBigInteger()
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
