<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;
use image;





class CategoryController extends Controller
{
    public function index(Request $request) {
       $categories = Category::latest();

       if(!empty($request->get('keyword'))){
           $categories = $categories->where('name','like','%'.$request->get('keyword').'%');
       }
       $categories = $categories->paginate(10);
       return view('admin.category.list', compact('categories'));
    }

    public function create() {
        return view('admin.category.create');
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(),[
            'name'    => 'required',
            'slug' => 'required|unique:categories'
        ]);

        if ($validator->passes()) {
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->save();

            $oldImage = $category->image;

            // Save Image Here
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id.'-'.time().'.'.$ext;
                $sPath = public_path().'/temp/'.$temlImage->name;
                $dPath = public_path().'/uploads/category/'.$newImageName;
                File::copy($sPath,$dPath);

                // Generate Image thumbnail
                $dPath = public_path().'/uploads/category/thumb/'.$newImageName;
                $img = Image::make($sPath);
                // $img->resize(450, 600);
                $img->fit(450, 600, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($dPath);

                $category->image = $newImageName;
                $category->save();

            }

            $request->session()->flash('success','Category created successfully');
            return response()->json([
                'status' => true,
                'message' => 'Category created successfully.',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    public function edit($categoryId, Request $request) {

        $category = Category::find($categoryId);
        if(empty($category)) {
            return redirect()->route('categories.index');
        }
        return view('admin.category.edit', compact('category'));
    }


    public function update($categoryId, Request $request) {

        $category = Category::find($categoryId);
        if(empty($category)) {
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Category not found' ,
            ]);
        }

        $validator = Validator::make($request->all(),[
            'name'    => 'required',
            'slug' => 'required|unique:categories,slug,'.$category->id.',id',
        ]);

        if ($validator->passes()) {
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->save();

            // Save Image Here
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id.'.'.$ext;
                $sPath = public_path().'/temp/'.$temlImage->name;
                $dPath = public_path().'/uploads/category/'.$newImageName;
                File::copy($sPath,$dPath);

                // Generate Image thumbnail
                $dPath = public_path().'/uploads/category/thumb/'.$newImageName;
                $img = Image::make($sPath);
                // $img->resize(450, 600);
                $img->fit(450, 600, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($dPath);

                $category->image = $newImageName;
                $category->save();

                // Delete Old Images Here
                file::delete(public_path().'/uploads/category/thumb/'.$oldImage);
                file::delete(public_path().'/uploads/category/'.$oldImage);

            }

            $request->session()->flash('success','Category Updated successfully');
            return response()->json([
                'status' => true,
                'message' => 'Category Updated successfully.',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    public function delete($categoryId, Request $request) {
        $category = Category::find($categoryId);

        if(empty($category)) {
            return redirect()->route('categories.index');
        }
        file::delete(public_path().'/uploads/category/thumb/'.$oldImage);
        file::delete(public_path().'/uploads/category/'.$oldImage);

        $category->delete();

        $request->session()->flash('success','Category Deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Category Deleted successfully.'
        ]);
    }
}
