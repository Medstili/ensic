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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('client_full_name');
            $table->string('client_tel');
            $table->string('choosen_speciality'); 
            $table->json('appointment_planning');
            $table->string('status')->default('pending');
            $table->foreignId('user_id')->constrained();
            $table->string('canceler')->nullable(true);
            $table->text('reason')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
