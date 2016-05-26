<?php

namespace App\Http\Controllers;

use App\Settings;

use App\Locations;

use Illuminate\Http\Request;

use App\Http\Requests;

class sitecontroller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function create()
    {
        $api = Settings::where('setting_name', 'geocoder API key')->first();
        $lat = Settings::where('setting_name', 'map lat')->first();
        $lon = Settings::where('setting_name', 'map lon')->first();
        $zoom = Settings::where('setting_name', 'map zoom')->first();
        
        $lat = $lat['setting_value'];
        $lon = $lon['setting_value'];
        $zoom = $zoom['setting_value'];
        
        $mapsettings = array(
        "lat" => "$lat",
        "lon" => "$lon",
        "zoom" => "$zoom",
        );
        
        $key = $api['setting_value'];
        return view('site.new', compact('key','mapsettings'));
    }
    
        public function store(Request $request)
    {
         $this->validate($request, [
        'type' => 'required|in:micro,tower,other',
        'name' => 'required',
        'other' => 'required_if:type,other',
        'lon' => 'required',
        'lat' => 'required',
        ]);
         
         if($request['type'] == 'other'){
            $type = $request['other'];
         }else{
            $type = $request['type'];
         }
        Locations::create([
            'name' => $request['name'],
            'type' => $type,
            'longitude' => $request['lon'],
            'latitude' => $request['lat'],
        ]);
        
        return redirect("/");
    }
}
