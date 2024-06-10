<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');
        $categories = Category::where('title', 'like', "%$query%")
            ->orWhere('slug', 'like', "%$query%")
            ->orderBy('id', 'DESC')
            ->paginate(10);

        $categories_parent = $this->parentShow();
        $paginationData    = [
            'total'          => $categories->total(),
            'current_page'   => $categories->currentPage(),
            'last_page'      => $categories->lastPage(),
            'has_more_pages' => $categories->hasMorePages(),
            'per_page'       => $categories->perPage(),
        ];

        return response()->json([
            'categories'        => CategoryResource::collection($categories),
            'pagination'        => $paginationData,
            'categories_parent' => $categories_parent
        ]);
    }
    
    public function parentShow()
    {
        $categories = Category::whereNull('parent_id')->get();

        return CategoryResource::collection($categories);
    }

    public function store(CategoryRequest $request)
    {
        $data     = $request->validated();
        if($request->parent_id == ''){
            $data['parent_id'] = null;
        }
        $category = Category::create($data);

        return response(new CategoryResource($category) , 201);
    }

    public function show($id)
    {
        $category = Category::find($id);
        if ($category) {
            $categories_parent = $this->parentShow();
            return response()->json([
                'category'          => new CategoryResource($category),
                'categories_parent' => $categories_parent
            ]);
        } else {
            return response()->json(['error' => 'Category not found'], 404);
        }
    }

    public function update(CategoryRequest $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $data = $request->all();

        $existingTitle = Category::where('title', $data['title'])->where('id', '!=', $id)->exists();
        $existingSlug  = Category::where('slug', $data['slug'])->where('id', '!=', $id)->exists();
        if ($existingTitle || $existingSlug) {
            return response()->json(['message' => 'Title or Slug already exists in another category'], 400);
        }

        if($request->parent_id == ''){
            $data['parent_id'] = null;
        }

        $category->update($data);

        return new CategoryResource($category);
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $childCategories = Category::where('parent_id', $id)->exists();

        if ($childCategories) {
            return response()->json(['message' => 'Cannot delete a category with child categories'], 422);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
