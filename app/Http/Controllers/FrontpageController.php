<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Testimonial;
use App\Property;
use App\Service;
use App\Slider;
use App\Post;

class FrontpageController extends Controller
{
    
    public function index()
    {
        $sliders        = Slider::latest()->get();
        $properties     = Property::latest()->where('featured',1)->with('rating')->withCount('comments')->take(6)->get();
        $latest         = Property::latest()->with('rating')->withCount('comments')->take(12)->get();
        $services       = Service::orderBy('service_order')->get();
        $testimonials   = Testimonial::latest()->get();
        $posts          = Post::latest()->where('status',1)->take(6)->get();

        $enableSlider   = false; // Disable legacy slider to remove white gap under navbar
        return view('frontend.index', compact('sliders','properties','latest','services','testimonials','posts','enableSlider'));
    }


    public function search(Request $request)
    {
        $city     = strtolower($request->city);
        $type     = $request->type;
        $purpose  = $request->purpose; // rent|sale
        $bedroom  = $request->bedroom;
        $bathroom = $request->bathroom;
        $minprice = $request->minprice;
        $maxprice = $request->maxprice;
        $minarea  = $request->minarea;
        $maxarea  = $request->maxarea;
        $featured = $request->featured;
        $keywords = $request->q;       // title/address/city search
        $sort     = $request->sort;    // newest|price_asc|price_desc

        $properties = Property::query()->withCount('comments')
            ->when($city, function ($query, $city) {
                return $query->whereRaw('LOWER(city) = ?', [$city]);
            })
            ->when($type, function ($query, $type) {
                return $query->where('type', '=', $type);
            })
            ->when($purpose, function ($query, $purpose) {
                return $query->where('purpose', '=', $purpose);
            })
            ->when($bedroom, function ($query, $bedroom) {
                return $query->where('bedroom', '>=', $bedroom);
            })
            ->when($bathroom, function ($query, $bathroom) {
                return $query->where('bathroom', '>=', $bathroom);
            })
            ->when($minprice, function ($query, $minprice) {
                return $query->where('price', '>=', (int) $minprice);
            })
            ->when($maxprice, function ($query, $maxprice) {
                return $query->where('price', '<=', (int) $maxprice);
            })
            ->when($minarea, function ($query, $minarea) {
                return $query->where('area', '>=', (int) $minarea);
            })
            ->when($maxarea, function ($query, $maxarea) {
                return $query->where('area', '<=', (int) $maxarea);
            })
            ->when($featured, function ($query) {
                return $query->where('featured', '=', 1);
            })
            ->when($keywords, function ($query) use ($keywords) {
                $k = trim($keywords);
                return $query->where(function ($q) use ($k) {
                    $q->where('title', 'like', "%{$k}%")
                      ->orWhere('address', 'like', "%{$k}%")
                      ->orWhere('city', 'like', "%{$k}%");
                });
            });

        // Sorting
        switch ($sort) {
            case 'price_asc':
                $properties = $properties->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $properties = $properties->orderBy('price', 'desc');
                break;
            default:
                $properties = $properties->orderBy('created_at', 'desc');
        }

        $properties = $properties->paginate(10)->appends($request->query());

        // Distinct lists for filters in the sidebar
        $bedroomdistinct = Property::select('bedroom')
            ->whereNotNull('bedroom')
            ->distinct()
            ->orderBy('bedroom')
            ->get();
        $bathroomdistinct = Property::select('bathroom')
            ->whereNotNull('bathroom')
            ->distinct()
            ->orderBy('bathroom')
            ->get();

        return view('pages.search', compact('properties', 'bedroomdistinct', 'bathroomdistinct'));
    }

}
