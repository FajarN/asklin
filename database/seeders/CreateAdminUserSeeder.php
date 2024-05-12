<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
  
class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Superadmin', 
            'email' => 'superadmin@asklin.org',
            'password' => bcrypt('password')
        ]);
      
        $role = Role::create(['name' => 'Superadmin']);
        $role = Role::create(['name' => 'Admin Pusat']);
        $role = Role::create(['name' => 'Admin Cabang']);
        $role = Role::create(['name' => 'Admin Daerah']);
        $role = Role::create(['name' => 'Klinik']);
        $role = Role::create(['name' => 'Sekjen']);
        $role = Role::create(['name' => 'Ketua Umum']);
        $role = Role::create(['name' => 'Bendahara']);
       
        $permissions = Permission::pluck('id','id')->all();
     
        $role->syncPermissions($permissions);
       
        $user->assignRole(['Superadmin']);
    }
}