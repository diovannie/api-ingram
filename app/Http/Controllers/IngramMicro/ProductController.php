<?php

namespace ClevAppBcRestApi\Http\Controllers\IngramMicro;

use Illuminate\Http\Request;

use ClevAppBcRestApi\Http\Requests;
use ClevAppBcRestApi\Http\Controllers\Controller;

use ClevAppBcRestApi\IngramProduct;
use ClevAppBcRestApi\IngramCategory;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return IngramProduct::orderBy('vendor_name')->take(5)->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return IngramProduct::where('part_number', '=', $id)->take(10)->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Ingram Resources Migration
     * @param boolean $isProduct
     * @return \Illuminate\Http\Response
     * TODO: Transfer this a service or migration class
     */
    public function migration($isProduct = false)
    {
        ini_set('max_execution_time', 120);
       
        //Categories Migration
        if (($handle = fopen(config('app.category_csv_path'), "r")) !== FALSE) {
            
            $row = 1;

            while (($data = fgetcsv($handle, 0, "\t")) !== FALSE) {
                if($row == 1) { $row++; continue; }
                
                $row++;
              
                $category = new IngramCategory;

                $category->category_id = $data[0];
                $category->description = $data[1];
                $category->level = $data[2];
               
                
                $category->save();
            }

            fclose($handle);
        }

        if (!$isProduct) return 'Migration categories completed.';

        //Products Migration
        if (($handle = fopen(config('app.product_csv_path'), "r")) !== FALSE) {
            
            $row = 1;

            while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
                if($row == 1) { $row++; continue; }
                
                $row++;
          
                $prod = new IngramProduct;

                $prod->part_number = $data[0];
                $prod->description = $data[1];
                $prod->vendor_name = $data[7];
                $prod->weight = $data[9];
                $prod->volume = $data[10];
                $prod->unit = $data[11];
                $prod->category_id = $data[12];
                $prod->customer_price = $data[13];
                $prod->retail_price = $data[14];
                $prod->available_qty = $data[16];
                $prod->material_long_description = $data[23];
                $prod->length = $data[24];
                $prod->width = $data[25];
                $prod->height = $data[26];
                $prod->dimension_unit = $data[27];
                $prod->weight_unit = $data[28];
                $prod->volume_unit = $data[29];
                $prod->manufacturer_vendor_number = $data[36];
                $prod->sub_category = $data[37];
                $prod->product_family = $data[38];
                $prod->purchasing_vendor = $data[39];
                $prod->material_change_code = $data[40];
                $prod->action_code = $data[41];
                $prod->customer_price_with_tax = $data[56];
                $prod->retail_price_with_tax = $data[57];
                $prod->tax_percent = $data[58];
                $prod->company_code = $data[66];
                $prod->company_code_currency = $data[67];
                $prod->creation_reason_type = $data[71];
                $prod->plan_avaiblale_qty = $data[73];
                $prod->deleted_at = '';
                
                $prod->save();
            }

            fclose($handle);
        }

        return 'Migration completed.';
    }

    public function vendor($name)
    {
        return IngramProduct::where('vendor_name', 'regex', new \MongoRegex("/^$name$/i"))->get();
    }
}
