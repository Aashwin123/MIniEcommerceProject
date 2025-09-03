<?php

namespace App\Http\Controllers\API;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProductService;
use Exception;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        try {
            $products = $this->productService->listAll();
            return response()->json($products);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch products',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        

try {
    $data = $request->validate([
        'name'        => 'required|string|max:255',
        'description' => 'nullable|string',
        'price'       => 'required|numeric',
        'stock'       => 'required|integer',
    ]);

    $product = $this->productService->createProduct($data);

    return response()->json([
        'success' => true,
        'message' => 'Product created successfully',
        'data'    => $product
    ], 201);

} catch (ValidationException $ve) {
    return response()->json([
        'success' => false,
        'message' => 'Validation failed',
        'errors'  => $ve->errors()
    ], 422);
} catch (Exception $e) {
    return response()->json([
        'success' => false,
        'message' => 'Failed to create product',
        'error'   => $e->getMessage()
    ], 500);
}

    }

    public function show($id)
    {
        try {
            $product = $this->productService->getById($id);
            return response()->json($product);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch product',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'name'        => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'price'       => 'sometimes|required|numeric',
                'stock'       => 'sometimes|required|integer',
            ]);

            $product = $this->productService->updateProduct($id, $data);

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data'    => $product
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $deleted = $this->productService->deleteProduct($id);

            return response()->json([
                'success' => $deleted,
                'message' => $deleted ? 'Product deleted successfully' : 'Product not found'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
