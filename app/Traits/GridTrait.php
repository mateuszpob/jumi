<?php namespace App\Traits;

/**
 * Description of Grid
 *
 * @author Marcin
 */
trait GridTrait {
    
    public function scopeSearchGrid($query,$text){
        $columns = $this->searchable;
        return $query->where(function($query) use ($text,$columns){
            foreach($columns as $c => $type){
                switch ($type){
                    case 'string':
                        $query->orWhere($c,'ILIKE','%'.$text.'%');
                        break;
                    case 'text':
                        $query->orWhere($c,'ILIKE','%'.$text.'%');
                        break;
                    case 'int':
                        $text = (int) $text;
                        break;
                    case 'float':
                        $text = (float) $text;
                        break;
                    default :
                        $text = null;
                        break;
                        
                }
                $query->orWhere($c,'=',$text);

                
            }
        });
    }
    
    protected function gridAction($model){
            $table_data = \Request::all();
            
            $count = $model::query()->count();            
            $search = $model::query();
            if(!empty($table_data['search']['value']))
                $search->searchGrid($table_data['search']['value']);
            
            $data = array(
                "draw"            => $table_data['draw'],
                "recordsTotal"    => $count,
                "recordsFiltered" => $search->count(),
                "data"            => null
            );
            //order after getting count
            foreach($table_data['order'] as $order){
                $search->orderBy($table_data['columns'][$order['column']]['data'], $order['dir']);
            }
            $data['data']=$search->take($table_data['length'])->offset($table_data['start'])->get();
            
            return $data;
    }
}