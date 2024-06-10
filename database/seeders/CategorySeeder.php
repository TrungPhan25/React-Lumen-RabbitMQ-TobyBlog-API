<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $topLevelCategories = [
            'Shopping',
            'Entertainment',
            'Technology',
            'Food & Drinks',
            'Travel',
            'Health & Wellness',
            'Home & Garden',
            'Fashion & Beauty',
            'Education',
            'Business & Finance',
        ];

        foreach ($topLevelCategories as $category) {
            $categoryModel = \App\Models\Category::create([
                'title' => $category,
                'slug' => Str::slug($category),
            ]);

            $this->createSubCategories($categoryModel);
        }
    }

    private function createSubCategories(\App\Models\Category $parentCategory)
    {
        $subCategoryCount = rand(2, 5); // Generate 2-5 subcategories per parent

        for ($i = 0; $i < $subCategoryCount; $i++) {
            $subCategoryTitle = $parentCategory->title . ' - Subcategory ' . ($i + 1);
            \App\Models\Category::create([
                'title' => $subCategoryTitle,
                'slug' => Str::slug($subCategoryTitle),
                'parent_id' => $parentCategory->id,
            ]);
        }
    }
}
