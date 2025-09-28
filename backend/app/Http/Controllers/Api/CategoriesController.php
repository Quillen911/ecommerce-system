<?php

namespace App\Http\Controllers\Api;

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

class CategoriesController extends Controller
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
        $category = $this->mainService->getCategory($category_slug);

        if (!$category) {
            return ResponseHelper::notFound('Kategori bulunamadı.');
        }

        $request->merge([
            'category_title' => $category->category_title,
            'category_id' => $category->id
        ]);
        $filters = $this->elasticSearchTypeService->filterType($request);

        $data = $this->elasticSearchProductService->filterProducts(
            $filters,
            $request->input('page', 1),
            $request->input('size', 12)
        );

        return response()->json([
            'products'   => $data['products'],
            'filters'    => $filters,
            'category'   => new CategoryResource($category),
            'pagination' => [
                'page' => $request->input('page', 1),
                'size' => $request->input('size', 12)
            ]
        ]);
    }
}
