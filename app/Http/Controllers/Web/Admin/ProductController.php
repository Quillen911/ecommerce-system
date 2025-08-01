<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\Admin\Product\ProductStoreRequest;
use App\Http\Requests\Admin\Product\ProductUpdateRequest;
use App\Services\Campaigns\Admin\ProductService;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function product()
    {
        $products = $this->productService->indexProduct();
        return view('Admin.Product.product', compact('products'));
    }

    public function storeProduct()
    {
        return view('Admin.Product.storeProduct'); 
    }

    public function createProduct(ProductStoreRequest $request) 
    {
        $products = $this->productService->createProduct($request);
        return redirect()->route('admin.product')->with('success', 'Ürün başarıyla eklendi');
    }

    public function editProduct($id)
    {
        $products = Product::findOrFail($id);
        return view('Admin.Product.editProduct', compact('products'));
    }

    public function updateProduct(ProductUpdateRequest $request, $id)
    {
        $products = $this->productService->updateProduct($request, $id);
        return redirect()->route('admin.product')->with('success', 'Ürün başarıyla güncellendi');
    }

    public function deleteProduct($id)
    {
        $products = $this->productService->deleteProduct($id);
        return redirect()->route('admin.product')->with('success', 'Ürün başarıyla silindi');
    }
}