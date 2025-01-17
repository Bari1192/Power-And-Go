<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('sub_id');
            $table->foreign('sub_id')->references('id')->on('subscriptions')->onDelete('cascade');

            $table->unsignedBigInteger('category_class');
            $table->foreign('category_class')->references('id')->on('categories')->onDelete('cascade');

            $table->integer('rental_start');
            $table->integer('driving_minutes')->nullable();
            $table->integer('discounted_driving')->nullable()->default(0);      # Kedvezményes vezetés (percdíj, 6:00 - 9:00) - opcionális
            $table->integer('parking_minutes')->nullable();                     # Parkolás (percdíj)
            $table->integer('reserv_minutes')->nullable();                      # Foglalás (percdíj, 20 perc után)
            $table->integer('disc_parking_minutes')->nullable();                # Kedvezményes parkolás (percdíj)
            $table->integer('daily_fee');                                       # Napidíj
            $table->integer('daily_km_limit');                                  # A napidíjban foglalt megtehető INGYENES km-ek száma.
            $table->integer('km_fee');                                          # Ingyenesen (125) megtehető km-en felüli útdíj
            $table->integer('airport_out_fee')->nullable();                     # Reptéri felár transzferrel (reptérRE)
            $table->integer('airport_in_fee')->nullable();                      # Reptéri felár transzferrel (reptérRŐL)
            $table->integer('airport_out_terminal_fee')->nullable();            # Reptéri felár terminálnál (reptérRE)
            $table->integer('airport_in_terminal_fee')->nullable();             # Reptéri felár terminálnál (reptérRŐL)
            $table->integer('zone_opening_fee')->nullable();                    # Külső zónából való bérlés nyitási, indítási felára (nyitás)
            $table->integer('zone_closing_fee')->nullable();                    # Külső zónában való bérlés zárási felára (zárás)
            $table->integer('three_hour_fee')->nullable();                      # 3 órás bérlés esetén a bérlés összege [CSAK OPEL-VIVARO-nál]
            $table->integer('six_hour_fee')->nullable();                        # 6 órás bérlés esetén a bérlés összege [CSAK OPEL-VIVARO-nál]
            $table->integer('twelve_hour_fee')->nullable();                     # 12 órás bérlés esetén a bérlés összege
            $table->integer('weekend_daily_fee')->nullable();                   # 12 órás bérlés esetén a bérlés összege
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
