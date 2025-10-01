<?php

namespace App\Http\Controllers\Api\ElasticSearch;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use App\Helpers\ResponseHelper;
use App\Models\Category;
use App\Services\Search\ElasticSearchTypeService;
use App\Services\Search\ElasticSearchProductService;
use App\Services\MainService;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Category\CategoryResource;

class CategoryFilterController extends Controller
{
    protected $elasticSearchTypeService;
    protected $elasticSearchProductService;
    protected $mainService;

    public function __construct(
        ElasticSearchTypeService $elasticSearchTypeService,
        ElasticSearchProductService $elasticSearchProductService,
        MainService $mainService
    ) {
        $this->elasticSearchTypeService = $elasticSearchTypeService;
        $this->elasticSearchProductService = $elasticSearchProductService;
        $this->mainService = $mainService;
    }
    public function categoryFilter(Request $request, $category_slug)
    {
        $categories = $this->mainService->getCategory($category_slug);

        if (!$categories) {
            return ResponseHelper::notFound('Kategori bulunamadı.');
        }
        $request->merge([
            'category_ids' => $categories->pluck('id')->toArray(),
        ]);
        $filters = $this->elasticSearchTypeService->filterType($request);
        $filters['sorting'] = $request->input('sorting', '');
        $data = $this->elasticSearchProductService->filterProducts(
            $filters,
            $filters['sorting'],
            $request->input('page', 1),
            $request->input('size', 1000)
        );

        return response()->json([
            'products'   => $data['products'],
            'filters'    => $filters,
            'cat'   => $categories,
            'categories'   =>CategoryResource::collection($categories),
            'total' => $data['results']['total'],
            'pagination' => [
                'page' => $request->input('page', 1),
                'size' => $request->input('size', 1000)
            ]
        ]);
    }
}
