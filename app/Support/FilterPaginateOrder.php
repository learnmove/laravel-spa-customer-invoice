<?php
namespace App\Support;
use Validator;
trait FilterPaginateOrder{
    protected $operators=[
        'equal_to'=>'=',
        'not_equal'=>'<>',
        'less_than'=>'<',
        'greater_than'=>'>',
        'less_than_or_equal_to'=>'<=',
        'greater_than_or_equal_to'=>'<=',
        'in'=>'IN',
        'like'=>'LIKE',
        'not_in'=>'NOT_IN',
        'between'=>'BETWEEN'
    ];
    public function scopeFilterPaginateOrder($q){
        $rq=request();
        $v=Validator::make($rq->all(),[
            'column'=>'required|in:'.implode(',',$this->filter),
            'direction'=>'required|in:asc,desc',
            'per_page'=>'required|integer|min:1',
            'search_operator'=>'required|in:'.implode(',',array_keys($this->operators)),
            'search_column'=>'required|in:'.implode(',',$this->filter),
            'search_query_1'=>'max:255',
            'search_query_2'=>'max:255',
            
        ]);
        if($v->fails()){
            dd($v->messages());

        }
        return $q->orderBy($rq->column,$rq->direction)
        ->where(function($q) use ($rq){
            if($rq->has('search_query_1')){
                if($this->isRelateColumn($rq)){
                    list($relation,$relatedColumn)=explode('.',$rq->search_column);
                    return  $q->whereHas($relation,function($q)use ($relatedColumn,$rq){
                        return $this->buildQuery(
                      $relatedColumn,
                        $rq->search_operator,
                        $rq,
                        $q
                        );
                    });
                }else{
                    return $this->buildQuery(
                        $rq->search_column,
                        $rq->search_operator,
                        $rq,
                        $q
                    );
                }
            }
        })
        ->paginate($rq->per_page);

    }
    protected function isRelateColumn($rq){
        return strpos($rq->search_column,'.')!==false;
    }
    protected function buildQuery($column,$operator,$rq,$q){
        switch ($operator){
            case 'equal_to':
            case 'not_equal_to':
            case 'less_than':
            case 'greater_than':
            case 'less_than_or_equal_to':
            case 'greater_than_or_equal_to':  //why
                $q->where($column,$this->operators[$operator],$rq->search_query_1);
            break;
            case 'in':
                $q->whereIn($column,explode(',',$rq->search_query_1));
                break;
            case 'not_in':
                $q->whereNotIn($column,explode(',',$rq->search_query_1));
                
            case 'like':
                $q->where($column,'like','%'.$rq->search_query_1.'%');
                break;
            case 'between':

                $q->whereBetween($column,[$rq->search_query_1,$rq->search_query_2]);
            break;
            default:
            throw   new Exception('Invalid',1);

            break;
        }
        return $q;

    }
}