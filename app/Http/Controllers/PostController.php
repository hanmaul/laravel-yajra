<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if ($request->ajax()) {
        //     $post = Post::select('id', 'pict', 'title', 'content')->get();
        //     return DataTables::of($post)
        //         ->addIndexColumn()
        //         ->addColumn('action', function ($data) {
        //             $button = '<button type="button" name="edit" id="' . $data->id . '" class="edit btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i>Edit</button>';
        //             $button .= '<button type="button" name="delete" id="' . $data->id . '" class="delete btn btn-danger btn-sm"><i class="bi bi-backspace-reverse-fill"></i>Delete</button>';
        //             return $button;
        //         })
        //         ->make(true);
        // }

        return view('post.index');
    }

    public function fetchAll()
    {
        $post = Post::all();
        $output = '';
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
            'title'  => 'required',
            'content' => 'required',
            'pict'  => 'required|image|mimes:jpeg,jpg,png|max:2048'
        );

        //Jika tidak sesuai validasi
        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        //Upload pict
        $file = $request->file('pict');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/images', $fileName);

        //Jika sesuai validasi  
        $form_data = [
            'title' => $request->title,
            'content' => $request->content,
            'pict' => $fileName
        ];

        Post::create($form_data);

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
}
