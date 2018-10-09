<?php

use App\Models\Lynx\LetterRequestType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateLetterRequestTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('letter_request_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        $this->seed();

        Permission::create(['name' => 'create letter-request-type', 'guard_name' => 'admin']);
        Permission::create(['name' => 'read letter-request-type', 'guard_name' => 'admin']);
        Permission::create(['name' => 'update letter-request-type', 'guard_name' => 'admin']);
        Permission::create(['name' => 'disable letter-request-type', 'guard_name' => 'admin']);

        $superadmin = Role::findByName('super-admin', 'admin');
        $superadmin->givePermissionTo([
            'create letter-request-type',
            'read letter-request-type',
            'update letter-request-type',
            'disable letter-request-type'
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('letter_request_types');
    }

    /**
     * Set initial values.
     */
    protected function seed()
    {
        $types = collect([
            'Settlement Agreement Letter',
            'Payment Receipt',
            'Payment Plan Letter',
            'Payment Reminder Letter',
            'Co-Borrower Release'
        ]);

        $types->map(function ($item) {
            factory(LetterRequestType::class)->create([
                'name' => $item,
                'description' => '',
            ]);
        });
    }
}
