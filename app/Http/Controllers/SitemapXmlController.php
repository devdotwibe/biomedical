<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Brand;

class SitemapXmlController extends Controller
{
    public function index() {
        
        $posts = Product::all();
        $brand = Brand::all();
        return response()->view('xml', [
            'posts' => $posts,'brand' => $brand
        ])->header('Content-Type', 'text/xml');
      }

}