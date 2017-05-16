<?php
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//    	Model::unguard();  
//    	// \App\CategoryItem::truncate();
//    	// \App\Category::truncate();
//        factory(App\Category::class, 10)->create();
        
        factory(App\Category::class, 10)->create();
    }
}
