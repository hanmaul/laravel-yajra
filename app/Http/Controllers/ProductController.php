<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        if ($request->ajax())
        {
            $product = Product::select('id','name','details')->get();
            return DataTables::of($product)
                    ->addIndexColumn()
                    ->addColumn('action', function($data){
                        $button = '<button type="button" name="edit" id="'.$data->id.'" class="edit btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i>Edit</button>';
                        $button .= '<button type="button" name="delete" id="'.$data->id.'" class="delete btn btn-danger btn-sm"><i class="bi bi-backspace-reverse-fill"></i>Delete</button>'; 
                        return $button;
                    })
                    ->make(true);
        }

        return view('product.index');
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
        //Validasi
        $rules = array(
            'name'  => 'required',
            'details' => 'required'
        );
        
        //Jika tidak sesuai validasi
        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        
        //Jika sesuai validasi  
        $form_data = array(
            'name'  => $request->name,
            'details' => $request->details
        );

        Product::create($form_data);

        return response()->json(['success' => 'Data Added Successfully!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(request()->ajax())
        {
            $product = Product::findOrFail($id);
            return response()->json(['result' => $product]);
        }
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
        //Validasi
        $rules = array(
            'name'  => 'required',
            'details' => 'required'
        );
        
        //Jika tidak sesuai validasi
        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }
        
        //Jika sesuai validasi  
        $form_data = array(
            'name'  => $request->name,
            'details' => $request->details
        );

        // $product = Product::whereId($id)->update($form_data);
        $product = Product::find($id)->update($form_data);

        return response()->json(['success' => 'Data was succcessfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
    }
}
