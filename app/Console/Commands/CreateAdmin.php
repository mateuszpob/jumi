<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->createAdmin();
    }
    
     /*
     * Create role admin, role definition '*', and super user
     */
    private function createAdmin(){
        // ADMIN
        $role = new \App\Role();
        $role->name = 'admin';
        $role->save();
        $role_id = $role->getAttribute('id');
        
        $role_def = new \App\RoleDefinition();
        $role_def->action = '*';
        $role_def->role_id = $role_id;
        $role_def->save();
        
        // USER DEFAULT
        $role = new \App\Role();
        $role->name = 'user';
        $role->save();
       
        $user = \App\User::create([
            'nick' => 'admin',
            'email' => 'mateuszpob@gmail.com',
            'password' => bcrypt('123123'),
            'first_name' => 'Mateusz',
            'last_name' => 'Poboży',
            'address' => 'Mroczna',
            'postcode' => '00-000',
            'city' => 'Elomordo',
            'telephone' => '123123',
        ]);
        $user->roles()->attach($role_id);
        $user->save();
    }
}
