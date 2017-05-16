<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        unlink('/home/error/kod/hiveware/storage/shop/items/');
//        system('rm -rf /home/error/kod/hiveware/storage/shop/items');
//        system('mkdir /home/error/kod/hiveware/storage/shop/items');
        Model::unguard();

//        $this->call('CategorySeeder');
//        $this->call('ItemSeeder');
//        
//        foreach(\App\Item::all() as $item){
//            $c_id = rand(1,10);
//            if(\App\Category::find($c_id))
//                    $item->categories()->attach($c_id);
//        }

        Model::reguard();
        
        $s = new \App\Shipment();
        $s->name = 'Przesyłka kurierska';
        $s->description = 'Przesyłka kurierska';
        $s->price = 24;
        $s->save();

        $s = new \App\SchemaCategories();
        $s->owner_id = 1;
        $s->name = 'Łazienki';
        $s->description = 'Łazienki';
        $s->url = 'lazienki';
        $s->save();

        
        $m = new \App\Mail();
        $m->name = 'confirm_order';
        $m->content = 'name: {{$name}}, link: {{$confirm_url}}, order_id: {{$order_id}}';
        $m->save();
        
        $m = new \App\Mail();
        $m->name = 'order_confirmed';
        $m->content = 'Imię: {{$first_name}}, Nazwisko {{$last_name}}, Adres: {{$address}}, Miasto: {{$city}}, Kod pocztowy: {{$postcode}}, Telefon: {{$telephone}}, Komentarz {{$comment}}, <a href="{{$sent_url}}">Oznacz jako wysłane</a>';
        $m->save();
    }
}
