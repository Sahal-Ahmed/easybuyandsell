<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return view('admin.category.index',compact('categories'));
    }
    
    public function trash()
    {
        $categories = Category::onlyTrashed()->get();
        return view('admin.category.trash',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category=new Category;
        $category->id = $request->category;
        $category->name = $request->name;
        $category->description = $request->description;
        $category->image = $request->image->store('category');
        // if($request->hasfile('image')){
        //     $file = $request->file('image');
        //     $extension = $file->getClientOriginalExtension();
        //     $fileName= time().'.'.$extension;
        //     $file->move('category',$fileName);
        //     $category->image = $fileName;
        // }
        $category->save();
        return redirect()->back()->with('message','category saved successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function change_status(Category $category)
    {
        if($category->status==1){
            $category->update(['status'=>0]);
        }else{
            $category->update(['status'=>1]);
        }
        return redirect()->back()->with('message','status changed successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('admin.category.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
       
        $update=$category->update([
            'name'=>$request->name,
            'description'=>$request->description,
            'image'=>$request->image->store('category')

        ]);
        if($update)
            return redirect('/categories')->with('message','updated successfully');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $delete=$category->delete();
        if($delete)
                return redirect()->back()->with('message','Move to trash successfully');
    }

    public function forceDelete($id)
    {
        $category =Category::withTrashed()->find($id);
        if(!is_null($category)){
            $category->forceDelete();
        }
        return redirect()->back()->with('message','permanently delete successfully');
    }

    public function restore($id){
        //$restore=$category->restore();
        // $id = $category->id;
        $category =Category::withTrashed()->find($id);
        if(!is_null($category)){
            $category->restore();
        }
        return redirect('/categories')->with('message','Restore successfully');
    }
}
