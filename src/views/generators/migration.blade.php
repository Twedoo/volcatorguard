<?php echo '<?php' ?>

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class StoneGuardSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::beginTransaction();

        if (!Schema::hasTable('{{ $usersTable }}')) {
            Schema::create('{{ $usersTable }}', function (Blueprint $table) {
                $table->increments('id');
                $table->string('code');
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->string('genre')->nullable();
                $table->string('date')->nullable();
                $table->string('avatar')->nullable();
                $table->string('status')->nullable();
                $table->string('type')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // Create table for storing roles
        if (!Schema::hasTable('{{ $rolesTable }}')) {
            Schema::create('{{ $rolesTable }}', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->unique();
                $table->string('display_name')->nullable();
                $table->string('description')->nullable();
                $table->string('id_creator')->nullable();
                $table->timestamps();
            });
        }

        // Create table for associating roles to users (Many-to-Many)
        if (!Schema::hasTable('{{ $roleUserTable }}')) {
            Schema::create('{{ $roleUserTable }}', function (Blueprint $table) {
                $table->integer('user_id')->unsigned();
                $table->integer('role_id')->unsigned();

                $table->foreign('user_id')->references('{{ $userKeyName }}')->on('{{ $usersTable }}')
                ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('role_id')->references('id')->on('{{ $rolesTable }}')
                ->onUpdate('cascade')->onDelete('cascade');

                $table->primary(['user_id', 'role_id']);
            });
        }

        // Create table for storing permissions
        if (!Schema::hasTable('{{ $permissionsTable }}')) {
            Schema::create('{{ $permissionsTable }}', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->unique();
                $table->string('id_stone')->nullable();
                $table->string('display_name')->nullable();
                $table->string('description')->nullable();
                $table->timestamps();
            });
        }

        // Create table for associating permissions to roles (Many-to-Many)
        if (!Schema::hasTable('{{ $permissionRoleTable }}')) {
            Schema::create('{{ $permissionRoleTable }}', function (Blueprint $table) {
                $table->integer('permission_id')->unsigned();
                $table->integer('role_id')->unsigned();

                $table->foreign('permission_id')->references('id')->on('{{ $permissionsTable }}')
                ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('role_id')->references('id')->on('{{ $rolesTable }}')
                ->onUpdate('cascade')->onDelete('cascade');

                $table->primary(['permission_id', 'role_id']);
            });
        }

        DB::commit();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('{{ $permissionRoleTable }}')) {
            Schema::drop('{{ $permissionRoleTable }}');
        }
        if (Schema::hasTable('{{ $permissionsTable }}')) {
            Schema::drop('{{ $permissionsTable }}');
        }
        if (Schema::hasTable('{{ $roleUserTable }}')) {
            Schema::drop('{{ $roleUserTable }}');
        }
        if (Schema::hasTable('{{ $rolesTable }}')) {
            Schema::drop('{{ $rolesTable }}');
        }
        if (Schema::hasTable('{{ $usersTable }}')) {
            Schema::drop('{{ $usersTable }}');
        }
    }
}
