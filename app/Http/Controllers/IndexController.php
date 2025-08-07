<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class IndexController extends Controller
{
    /**
     * Display a listing of the properties.
     */
    // public function index(Request $request)
    // {
        
    //     $testimonials = $this->getTestimonials();
    //     $assetBase = asset('');
        
    //     return view('frontend.index', compact('testimonials', 'assetBase'));
    // }

    
    public function index()
    {
        return view('frontend.home');
    }
    
    public function about()
    {
        return view('frontend.about');
    }
    
    public function contact()
    {
        return view('frontend.contact');
    }
    
    public function sermons()
    {
        return view('frontend.sermons');
    }
    
    public function events()
    {
        return view('frontend.events');
    }
    
    public function donations()
    {
        return view('frontend.donations');
    }




    /**
     * Get testimonials for a specific page section
     *
     * @return array
     */
    public function getTestimonials()
    {
        $path = public_path('data/testimonials.json');
        
        if (!File::exists($path)) {
            return [];
        }
        
        $testimonials = json_decode(File::get($path), true);
        
        return $testimonials;
    }




}
