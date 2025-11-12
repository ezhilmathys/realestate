<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use App\Testimonial;
use Carbon\Carbon;
use Brian2694\Toastr\Facades\Toastr;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::latest()->get();

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'required|mimes:jpeg,jpg,png',
            'testimonial' => 'required|max:200'
        ]);
        $image = $request->file('image');
        $slug  = Str::slug($request->name);

        if(isset($image)){
            $currentDate = Carbon::now()->toDateString();
            // Determine safe format
            $mime = $image->getMimeType();
            $format = strtolower(explode('/', $mime)[1] ?? 'jpg');
            if ($format === 'jpeg') { $format = 'jpg'; }
            if (!in_array($format, ['jpg','png','gif','webp'])) { $format = 'jpg'; }
            $imagename = $slug.'-'.$currentDate.'-'.uniqid().'.'.$format;

            if(!Storage::disk('public')->exists('testimonial')){
                Storage::disk('public')->makeDirectory('testimonial');
            }
            $testimonial = Image::make($image)->resize(160, 160)->encode($format, 90);
            Storage::disk('public')->put('testimonial/'.$imagename, $testimonial);
        }else{
            $imagename = 'default.png';
        }

        $testimonial = new Testimonial();
        $testimonial->name = $request->name;
        $testimonial->testimonial = $request->testimonial;
        $testimonial->image = $imagename;
        $testimonial->save();

        Toastr::success('message', 'Testimonial created successfully.');
        return redirect()->route('admin.testimonials.index');
    }


    public function edit($id)
    {
        $testimonial = Testimonial::find($id);

        return view('admin.testimonials.edit', compact('testimonial'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'image' => 'mimes:jpeg,jpg,png',
            'testimonial' => 'required|max:200',
        ]);
        $image = $request->file('image'); 
        $slug  = Str::slug($request->name);
        $testimonial = Testimonial::find($id);
        $testimonial = Testimonial::find($id);

        if(isset($image)){
            $currentDate = Carbon::now()->toDateString();
            // Determine safe format
            $mime = $image->getMimeType();
            $format = strtolower(explode('/', $mime)[1] ?? 'jpg');
            if ($format === 'jpeg') { $format = 'jpg'; }
            if (!in_array($format, ['jpg','png','gif','webp'])) { $format = 'jpg'; }
            $imagename = $slug.'-'.$currentDate.'-'.uniqid().'.'.$format;
            if(!Storage::disk('public')->exists('testimonial')){
                Storage::disk('public')->makeDirectory('testimonial');
            }
            if(Storage::disk('public')->exists('testimonial/'.$testimonial->image)){
                Storage::disk('public')->delete('testimonial/'.$testimonial->image);
            }
            $testimonialimg = Image::make($image)->resize(160, 160)->encode($format, 90);
            Storage::disk('public')->put('testimonial/'.$imagename, $testimonialimg);
        }else{
            $imagename = $testimonial->image;
        }

        $testimonial->name = $request->name;
        $testimonial->testimonial = $request->testimonial;
        $testimonial->image = $imagename;
        $testimonial->save();

        Toastr::success('message', 'Testimonial updated successfully.');
        return redirect()->route('admin.testimonials.index');
    }


    public function destroy($id)
    {
        $testimonial = Testimonial::find($id);

        if(Storage::disk('public')->exists('testimonial/'.$testimonial->image)){
            Storage::disk('public')->delete('testimonial/'.$testimonial->image);
        }

        $testimonial->delete();

        Toastr::success('message', 'Testimonial deleted successfully.');
        return back();
    }
}
