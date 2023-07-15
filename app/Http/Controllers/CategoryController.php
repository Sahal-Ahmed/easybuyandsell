<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        //$categories = Category::all();
        $startDate = Carbon::parse('2023-01-01');
        $endDate = Carbon::parse('2023-12-31');

        $categories = Category::whereBetween('created_at', [$startDate, $endDate])->get();


        
        // //$category=new Category;
        // $startDate = Carbon::parse('2023-07-01');
        // $endDate = Carbon::parse('2023-07-31');
        //  // Replace with the name of the image you want to fetch

        // //$imagePath = null;

        // for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
        //     $year = $date->format('Y');
        //     $month = $date->format('m');
        //     $day = $date->format('d');
        //     $category=new Category;
        //     $imageName = $category->image;    
        //     $folderName = "$year/$month/$day";

        //     $folderPath = public_path('images/' . $folderName);

        //     if (File::exists($folderPath . $imageName)) {
        //         $imagePath = 'images/' . $folderName ;
                
        //         break; // Exit the loop if the image is found
        //         //continue;
        //     }
        // }
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

        // $folderName = Carbon::now()->format('Y-m-d');
        // $category->image = $request->image->store('category');
        $date = Carbon::now();
        $year = $date->format('Y');
        $month = $date->format('m');
        $day = $date->format('d');

        $folderName = "$year/$month/$day";

        $folderPath = public_path('images/' . $folderName);
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }
        if($request->hasfile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName= time().'.'.$extension;
            $file->move($folderPath,$fileName);
            $category->image = $fileName;
        }
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
