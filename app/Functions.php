<?php


namespace App;

use Illuminate\Database\Eloquent\Model;


class Functions extends Model{
    public static $menu_data = array();
    private static $parent_ides = array();
    private static $l = 0;
    private static $categories = null;
    private static $max_poziom = 0;
    
   
    
    public static function escapeJsonString($value) {
    # list from www.json.org: (\b backspace, \f formfeed)    
    $escapers =     array('"', "\\",     "/",   "\"",  "\n",  "\r",  "\t", "\x08", "\x0c");
    $replacements = array('\"', "\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t",  "\\f",  "\\b");
    $result = str_replace('"', '\"', $value);
    return $result;
  }
    
  public static function utf8_bad_replace_static($str, $replace = '') {
            $UTF8_BAD = '([\x00-\x7F]' . # ASCII (including control chars)
                    '|[\xC2-\xDF][\x80-\xBF]' . # non-overlong 2-byte
                    '|\xE0[\xA0-\xBF][\x80-\xBF]' . # excluding overlongs
                    '|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}' . # straight 3-byte
                    '|\xED[\x80-\x9F][\x80-\xBF]' . # excluding surrogates
                    '|\xF0[\x90-\xBF][\x80-\xBF]{2}' . # planes 1-3
                    '|[\xF1-\xF3][\x80-\xBF]{3}' . # planes 4-15
                    '|\xF4[\x80-\x8F][\x80-\xBF]{2}' . # plane 16
                    '|(.{1}))';                              # invalid byte
            ob_start();
            while (preg_match('/' . $UTF8_BAD . '/S', $str, $matches)) {
              
                $str = substr($str, strlen($matches[0]));
            }
            $result = ob_get_contents();
            ob_end_clean();
            return $result;
        }

    public static function changeBadCharactersPl($str){
        $str = str_replace('Å', 'ł', $str);
        $str = str_replace('Å¼', 'ż', $str);
        $str = str_replace('Ä', 'ą', $str);
        $str = str_replace('Ăł', 'ó', $str);
        $str = str_replace('Ä\x99', 'ł', $str);
        $str = str_replace('Ä\x99', 'ę', $str);
        $str = str_replace('Å\x84', 'ń', $str);
        $str = str_replace('', '', $str);
        $str = str_replace('', '', $str);
        $str = str_replace('', '', $str);
        $str = str_replace('', '', $str);
        $str = str_replace('¼', '', $str);
        return $str;
    }
    public static function changeBadCharacters($str){
        $str = str_replace('ź', 'z', $str);
        $str = str_replace('ż', 'z', $str);
        $str = str_replace('ż', 'a', $str);
        $str = str_replace('ó', 'o', $str);
        $str = str_replace('ł', 'l', $str);
        $str = str_replace('ę', 'e', $str);
        $str = str_replace('ń', 'n', $str);
        $str = str_replace('ś', 's', $str);
        $str = str_replace('ć', 'c', $str);
        $str = str_replace('ą', 'a', $str);
        $str = str_replace('', '', $str);
        $str = str_replace('', '', $str);
        $str = str_replace('', '', $str);
        $str = str_replace('', '', $str);
        $str = str_replace('¼', '', $str);
        return $str;
    }
    public static function createUrlFromString($string, $length = 5){
        $string = str_replace(' ', '-', self::changeBadCharacters(mb_strtolower($string, 'UTF-8')));
       
       
       
        $string =  preg_replace('/[^a-z\-]/', '', $string);
        $string =  preg_replace('/-+/', '-', $string);
     // preg_match('/^([a-z].*\-)/', $string, $match);//dd($match);
        preg_match('/^([a-z].*\-*)/', $string, $match);//dd( $string, $match);
        $return_string = '';
        
        if(isset($match[0])){
            $exp = explode('-', $match[0]);
            $i=0;
            
            for($i=0 ; $i<count($exp) && $i<$length; $i++){
                $return_string .= $exp[$i] . '-';
            }
        } 
        return trim($return_string, '-');
    }
    public static function createDescriptionFromString($string){
        $exp = explode(' ', $string);
        $return_string = '';
        $i=0;
        for($i=0 ; $i<count($exp) && $i<5; $i++){
           $return_string .= $exp[$i] . ' ';
        }
        return $return_string;
    }

    public static function printr($txt){
        echo '<pre>'.print_r($txt, 1).'</pre>';
        exit;
    }


    // ==================== MENU ========================= //
     // Zwraca wygenerowane menu kategorii.
    public static function getCategoryMenu($schema_url){

        self::loadMenuData($schema_url);

        $menu = '<div id="main_menu">
                    <ul class="moczy-menu">';
        if(empty(self::$menu_data))
            return null;
        // dd(self::$menu_data);
        $poz = self::$menu_data[0]['poziom'];
        
        foreach(self::$menu_data as $t){
            $arrow = '';
            if(in_array($t['id'], self::$parent_ides))
                $arrow = '<i class="fa fa-caret-left pull-right"></i>';
            
            $link = '';
            //if(!in_array($t['id'], self::$parent_ides))
                $link = 'href="'. url('kategorie/'.$schema_url . '/' . $t['url_name']) .'"';
            
            if($t['poziom'] > $poz){
                $menu .= '<ul class="moczy-menu-part" data-parent="'.$t['parent'].'">';
                $menu .= '<li class="el-moczy-menu" data-parent="'.$t['parent'].'" data-id="'.$t['id'].'" data-poziom="'.$t['poziom'].'"><a '.$link.'>' . $t['name'] . $arrow . '</a></li>';
            }elseif($t['poziom'] == $poz){
                $menu .= '<li class="el-moczy-menu" data-parent="'.$t['parent'].'" data-id="'.$t['id'].'" data-poziom="'.$t['poziom'].'"><a '.$link.'>' . $t['name'] . $arrow . '</a></li>';
            }elseif($t['poziom'] < $poz){
                $menu .= '</ul>';
                $menu .= '<li class="el-moczy-menu" data-parent="'.$t['parent'].'" data-id="'.$t['id'].'" data-poziom="'.$t['poziom'].'"><a '.$link.'>' . $t['name'] . $arrow . '</a></li>';
            }
            
            $poz = $t['poziom'];
        }

        $menu .= '</ul></div>';

        return $menu;
    }
    // Pobiera wszystkie kategorie jednym zapytaniem do kolekcji.
    private static function getCategories($schema_url){
        $sql = "SELECT c.url_name, c.name, c.id_upper, c.id FROM categories c LEFT JOIN schema_categories sc ON sc.id = c.schema_id WHERE sc.url = ?";
        self::$categories = \DB::select($sql, [$schema_url]);
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
            self::$menu_data[self::$l] = array('id'=>$c->id, 'poziom'=>$poziom, 'parent'=>$c->id_upper, 'name'=>$c->name, 'items'=>false, 'url_name'=>$c->url_name);
            self::gmenu($c->id, $poziom+1);
        }
    }

    private static function hasChildById($id){
        $sql = "SELECT count(ci.item_id) FROM category_item ci 
                LEFT JOIN items i ON i.id = ci.item_id 
                WHERE ci.category_id = ".$id." AND i.active IS true";
        return \DB::select($sql)[0]->count;
    }

    public static function loadMenuData($schema_url){
        self::getCategories($schema_url);
        self::gmenu();
        $all = self::$menu_data;

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
            // echo $tmp['id'];
            if(in_array($tmp['id'], $good_parents) || self::hasChildById($tmp['id']) > 0){
                $all_good[] = $tmp['id'];
                if($tmp['parent'])
                    $good_parents[] = $tmp['parent'];
            }
        }
        $good = array_merge($all_good, array_unique($good_parents));
        
        self::$menu_data = [];
        foreach($all as $c){
            if(in_array($c['id'], $good))
                self::$menu_data[] = $c;
        }

    }

    public static function getSimplePaginationHtml($url, $page, $page_count, $take=10){//dd($url, $page, $page_count, $take);
        $present_prev = 4;
        $present_next = 4;
        $page_count = (int)$page_count;
        $page = (int)$page;
        $from_zero_to_page = range(0, $page);
        if($page >= $page_count)
            $from_page_to_max = [];
        else
            $from_page_to_max = range($page, $page_count);
        $lp_offset = ($page-$present_prev)>=0 ? $page-$present_prev : 0;
        $lp = array_slice($from_zero_to_page, $lp_offset);
        $rp = array_slice($from_page_to_max, 0, $present_next);
//        dd($page_count, $from_zero_to_page,$lp, $rp, $page, $page_count, $present_prev);
        $html = '';
        for($i=0 ; $i<$present_prev && isset($lp[$i]) ; $i++){
            if($lp[$i] != $page)
                $html .= '<a href="'.$url. '/' . $lp[$i] . '/' . $take.'">'.($lp[$i]+1).'</a> ';
        }
        $html .= '<a href="#" class="current-page"><b>'.($page+1).'</b></a>';
        
        for($i=0 ; $i<$present_next && isset($rp[$i]) ; $i++){
            if($rp[$i] != $page && $rp[$i]+1 <= $page_count)
                $html .= ' <a href="'.$url. '/' . $rp[$i] . '/' . $take.'">'.($rp[$i]+1).'</a>';
        }

        return $html;
    }
}