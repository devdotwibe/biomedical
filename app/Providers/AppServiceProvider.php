<?php

namespace App\Providers;

use App\Models\Cms;
use App\Models\Product;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */

     public function boot()
     {
 
         Config::set('app.debug', true);
         //View::share('my', '1');
 
 
         //$brand    = Brand::where('status','Y')->limit(10)->get();
         $product = Product::where('status', 'Y')->limit(10)->get();
         $aboutus = Cms::find(11); //Cms::where(['id'=>11, 'parent_id'=>11])->get();
         $about_pages = Cms::where(['parent_id' => 11, 'status' => 'Y'])->get();
 
 
         $downloads = Cms::find(7); //Cms::where(['id'=>11, 'parent_id'=>11])->get();
         $download_pages = Cms::where(['parent_id' => 7, 'status' => 'Y'])->get();
 
         //$categories     = Category::where(['status' => 'Y'])->orderBy('id','desc')->limit(10)->get();
 
         $categories = DB::select(query:"select cat.id as catid, MAX(cat.slug) as catslug, MAX(cat.name) as catname from categories as cat inner join products as products ON cat.id = products.category_id where products.status = 'Y' AND products.verified = 'Y' AND products.show_inPage = 'Y' AND cat.status = 'Y' group by cat.id order by cat.id desc limit 10");

 
         $brand = DB::select(query:"select brand.id as brandid, MAX(brand.slug) as brandslug, MAX(brand.name) as brandname from brand as brand inner join products as products ON brand.id = products.brand_id where products.status='Y' AND products.verified='Y' AND products.show_inPage='Y' AND brand.status='Y' group by brand.id order by brand.id desc limit 10");
  
 
         $siteData = array(
             'brand' => $brand,
             'product' => $product,
             'aboutus' => $aboutus,
             'about_pages' => $about_pages,
             'downloads' => $downloads,
             'download_pages' => $download_pages,
             'prod_category' => $categories,
 
         );
 
         View::share('siteData', $siteData);
     }
 
}
