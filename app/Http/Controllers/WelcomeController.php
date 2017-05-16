<?php namespace App\Http\Controllers;

class WelcomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
//	public function __construct()
//	{
//		$this->middleware('guest');
//	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('shop.mimity.welcome_page')
                        ->withFeatured_item(\App\Item::active()->get()->random(1))
                        ->withSchema_id('start') // do podswietlenia opcji start w menu
                        ->withCategory_id(0);
	}
        









	// ========================================================================================================================= //



	private static $menu_data = array();
    private static $parent_ides = array();
    private static $l = 0;
    private static $categories = null;
    private static $max_poziom = 0;

	// Pobiera wszystkie kategorie jednym zapytaniem do kolekcji.
    private static function getCategories($schema_id){
        $sql = "SELECT c.name, c.id_upper, c.id FROM categories c ";
        self::$categories = \DB::select($sql);
    }

    // Generuje tablice z danymi potrzebnymi do wygenerowania menu.
    private static function gmenu($id_upper = null, $poziom = 0){
        $temp = array();
        foreach(self::$categories as $c1){//dd($c1);
            if($c1->id_upper == $id_upper)
                $temp[] = $c1;
        }

        foreach($temp as $c){
        	if(self::$max_poziom < $poziom){
        		self::$max_poziom = $poziom;
        	}

            self::$l++;
            self::$parent_ides[self::$l] = $c->id_upper;
            self::$menu_data[self::$l] = array('id'=>$c->id, 'poziom'=>$poziom, 'parent'=>$c->id_upper, 'name'=>$c->name, 'items'=>false);
            self::gmenu($c->id, $poziom+1);
        }
    }

    private function hasChildById($id){
    	$sql = "SELECT count(ci.item_id) FROM category_item ci 
    			LEFT JOIN items i ON i.id = ci.item_id 
    			WHERE ci.category_id = ".$id." AND i.active IS true";
    	return \DB::select($sql)[0]->count;
    }

	public function testx(){
		self::getCategories(1);
        self::gmenu();

		usort(self::$menu_data, function($a, $b){
			if($a['poziom'] == $b['poziom'])
				return 0;
			$a['poziom'] > $b['poziom'] ? $x = -1 : $x = 1;
			return $x;
		});
		$good_parents = [];
		$all_good = [];

        while(!empty(self::$menu_data)){
        	$tmp = array_pop(self::$menu_data);
        	// echo $tmp['name'] .' - '. $tmp['id'] .' -> '.$this->hasChildById($tmp['id']) . '</br>';
        	echo $tmp['id'];
        	if(in_array($tmp['id'], $good_parents) || $this->hasChildById($tmp['id']) > 0){
        		$all_good[] = $tmp['id'];
        		if($tmp['parent'])
        			$good_parents[] = $tmp['parent'];
        	}
        }
		dd(array_merge($all_good, array_unique($good_parents)));

		// $categories = \App\Category::where('schema_id', 1)->get()->all();
		// $cats = [];
		// foreach($categories as $c){
		// 	$cats[$c->id] = $c;
		// }

		// dd($cats);
	}
	// pokazuje w adminie plik w ktorym zapisuja sie ludzie i roboty wchodzace na strone
	public function userActivity(){
		$content = \File::get(storage_path('app/users_activity.txt'));
		echo '<div style="overflow-x:scroll;">';
		foreach(file(storage_path('app/users_activity.txt')) as $line) {
			echo '<p style="width: 5000px;">'.$line.'</p>';
		}
		echo '</div>';
		
	}


}
