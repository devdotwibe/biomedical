<?php

namespace App\Imports;

use App\Product;
use App\Brand;
use App\Category;
use App\Company;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\DB;
  

class UsersImport implements ToModel

{

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function model(array $row)
        {
        $i=0;
           
        if($row[4]!='Manufacture' && $row[5]!='Product Category')
        {

        


           $brand_name=$row[5];
       // print_r($row);exit;
        $brand_exits = Brand::where('name',trim($row[4]))->get();
        if(count($brand_exits)>0)
        {
            $brand_id=$brand_exits[0]->id;
        }
        else{
        
            $slug = str_slug(trim($row[4]));
            $slug_e = Brand::where('slug', $slug)->count();
            $slug_e1 = Category::where([['slug', $slug]])->count();
            if($slug_e >  0 || $slug_e1 > 0) {
                $slug = $slug.time();
            }
           // echo $row[5];
            $brands = new Brand;
            $brands->name = trim($row[4]);
            $brands->slug = $slug;
            $brands->status= "Y";
           // print_r($brand);
           //exit;
            $brands->save();   
            $brand_id=$brands->id;
        }

        $category_exits = DB::select('select * from categories where `name`="'.trim($row[5]).'"');


        if(count($category_exits)>0)
        {
            $category_id=$category_exits[0]->id;
        }
        else{
            $slug = str_slug($row[5]);
            $slug_e = Category::where('slug', $slug)->count();
            $slug_e1 = Brand::where([['slug', $slug]])->count();
            if($slug_e >  0 || $slug_e1 > 0) {
                $slug = $slug.time();
            }
            $category = new Category;
            $category->name = trim($row[5]);
            $category->slug = $slug;
            $category->status= "Y";
            $category->save();    
            $category_id=$category->id;
        }

        
        $company_exits = DB::select('select * from company where `name`="'.trim($row[15]).'"');


        if(count($company_exits)>0)
        {
            $company_id=$company_exits[0]->id;
        }
        else{
            $slug = str_slug($row[15]);
            $slug_e = Company::where('slug', $slug)->count();
           
            if($slug_e >  0) {
                $slug = $slug.time();
            }
            $company = new Company;
            $company->name = trim($row[15]);
            $company->slug = $slug;
            $company->status= "Y";
            $company->save();    
            $company_id=$company->id;
        }


        $product_exits = DB::select('select * from products where `name`="'.nl2br(stripslashes(htmlentities(trim($row[0])))).'" AND `brand_id`="'.trim($brand_id).'" AND `item_code`="'.trim($row[6]).'"');   

    
        if(count($product_exits)==0)
        {
        $product = new Product;

        $slug = str_slug($row[0]);
        $slug_e = Product::where([['slug', $slug]])->count();
        $slug_e1 = Category::where([['slug', $slug]])->count();
        if($slug_e > 0 || $slug_e1 > 0) {
            $slug = $slug.time();
        }
        
        $product->slug = $slug;
        $product->name = nl2br(stripslashes(htmlentities(trim($row[0]))));
        $product->short_title = trim($row[1]);
        $product->description = trim($row[2]);
        $product->short_description = trim($row[3]);
        $product->brand_name =trim($row[4]);
        $product->category_name =trim($row[5]);
        $product->brand_id =$brand_id;
        $product->category_id =$category_id;
        $product->item_code =trim($row[6]);
        $product->unit =trim($row[7]);
        $product->quantity =trim($row[8]);

        $product->unit_price =trim($row[9]);
        $product->tax_percentage =trim($row[10]);
        $product->hsn_code =trim($row[11]);

        $product->warrenty =trim($row[12]);
        $product->payment =trim($row[13]);
        $product->validity =trim($row[14]);
        if(trim($row[15])!='')
        {
        $product->company_id =$company_id;
        $product->company_name =trim($row[15]);
        }
        
        $product->save();
        }


        }

       /* return new Product([

            'name'     => $row[0],

            'title'    => $row[1], 

            'description' => $row[1],
            'category_id'=> 8,
            'slug'=> 'test',

        ]);*/

    }

}
