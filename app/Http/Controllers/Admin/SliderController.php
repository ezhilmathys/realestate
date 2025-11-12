<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use App\Slider;
use Carbon\Carbon;
use Brian2694\Toastr\Facades\Toastr;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::latest()->get();

        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:sliders|max:255',
            'image' => 'required|mimes:jpeg,jpg,png'
        ]);
        $image = $request->file('image');
        $slug  = Str::slug($request->title);

        if(isset($image)){
            $currentDate = Carbon::now()->toDateString();
            // Determine safe format
            $mime = $image->getMimeType();
            $format = strtolower(explode('/', $mime)[1] ?? 'jpg');
            if ($format === 'jpeg') { $format = 'jpg'; }
            if (!in_array($format, ['jpg','png','gif','webp'])) { $format = 'jpg'; }
            $imagename = $slug.'-'.$currentDate.'-'.uniqid().'.'.$format;

            if(!Storage::disk('public')->exists('slider')){
                Storage::disk('public')->makeDirectory('slider');
            }
            $slider = Image::make($image)->resize(1600, 480, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->encode($format, 90);
            Storage::disk('public')->put('slider/'.$imagename, $slider);
        }else{
            $imagename = 'default.png';
        }

        $slider = new Slider();
        $slider->title = $request->title;
        $slider->description = $request->description;
        $slider->image = $imagename;
        $slider->save();

        Toastr::success('message', 'Slider created successfully.');
        return redirect()->route('admin.sliders.index');
    }


    public function edit($id)
    {
        $slider = Slider::find($id);

        return view('admin.sliders.edit', compact('slider'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'image' => 'mimes:jpeg,jpg,png'
        ]);
        $image = $request->file('image'); 
        $slug  = Str::slug($request->title);
        $slider = Slider::find($id);
        $slider = Slider::find($id);

        if(isset($image)){
            $currentDate = Carbon::now()->toDateString();
            // Determine safe format
            $mime = $image->getMimeType();
            $format = strtolower(explode('/', $mime)[1] ?? 'jpg');
            if ($format === 'jpeg') { $format = 'jpg'; }
            if (!in_array($format, ['jpg','png','gif','webp'])) { $format = 'jpg'; }
            $imagename = $slug.'-'.$currentDate.'-'.uniqid().'.'.$format;
            if(!Storage::disk('public')->exists('slider')){
                Storage::disk('public')->makeDirectory('slider');
            }
            if(Storage::disk('public')->exists('slider/'.$slider->image)){
                Storage::disk('public')->delete('slider/'.$slider->image);
            }
            $sliderimg = Image::make($image)->resize(1600, 480)->encode($format, 90);
            Storage::disk('public')->put('slider/'.$imagename, $sliderimg);
        }else{
            $imagename = $slider->image;
        }

        $slider->title = $request->title;
        $slider->description = $request->description;
        $slider->image = $imagename;
        $slider->save();

        Toastr::success('message', 'Slider updated successfully.');
        return redirect()->route('admin.sliders.index');
    }


    public function destroy($id)
    {
        $slider = Slider::find($id);

        if(Storage::disk('public')->exists('slider/'.$slider->image)){
            Storage::disk('public')->delete('slider/'.$slider->image);
        }

        $slider->delete();

        Toastr::success('message', 'Slider deleted successfully.');
        return back();
    }
}
