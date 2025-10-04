<?php

namespace App\Services\Search;

class ElasticSearchTypeService
{
    public function filterType($request)
    {
        $filters = [];
        if($request->filled('category_ids')){
            $filters['category_ids'] = $request->input('category_ids') ?? '';
        }
        if($request->filled('gender')){
            $filters['gender'] = $request->input('gender') ?? '';
        }
        if($request->filled('min_price')){
            $filters['min_price'] = $request->input('min_price') ?? '';
        }
        if($request->filled('max_price')){
            $filters['max_price'] = $request->input('max_price') ?? '';
        }
        if($request->filled('color')){
            $filters['color'] = $request->input('color') ?? '';
        }
        if($request->filled('sizes')){
            $filters['sizes'] = $request->input('sizes') ?? '';
        }
        return $filters;
        
    }

    public function sortingType($request)
    {
        $sorting = '';
        if($request->filled('sorting')){
            if($request->input('sorting') == 'stock_quantity_asc' || $request->input('sorting') == 'stock_quantity_desc' || $request->input('sorting') == 'price_asc' || $request->input('sorting') == 'price_desc'){
                $sorting = $request->input('sorting');
            }else{
                $sorting = 'price_asc';
            }
        }
    
        return $sorting;
    }

}