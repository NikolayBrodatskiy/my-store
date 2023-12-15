<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // $data = [];

        // $data['usersCount'] = User::all()->count();
        // $data['productsCount'] = Product::all()->count();
        // $data['categoriesCount'] = Category::all()->count();
        // $data['tagsCount'] = Tag::all()->count();

        return Inertia::render(
            'Admin/Dashboard/Index'
        );
    }
}
