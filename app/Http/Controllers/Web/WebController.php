<?php

namespace LaraDev\Http\Controllers\Web;

use Illuminate\Http\Request;
use LaraDev\Http\Controllers\Controller;
use LaraDev\Property;

class WebController extends Controller
{
    public function home()
    {
        $propertyForSale = Property::sale()->available()->limit(3)->get();
        $propertyForRent = Property::rent()->available()->limit(3)->get();
        return view('web.home', [
            'propertyForSale' => $propertyForSale,
            'propertyForRent' => $propertyForRent,
        ]);
    }

    public function contact()
    {
        return view('web.contact');
    }
    public function rent()
    {
        $filter = new FilterController();
        $filter->clearAllData();

        $properties = Property::rent()->available()->get();
        return view('web.filter', [
            'properties' => $properties,
            'type' => 'rent'
        ]);
    }
    public function rentProperty(Request $request)
    {
        $property = Property::where('slug', $request->slug)->first();
        return view('web.property', ['property' => $property]);
    }
    public function buy()
    {
        $filter = new FilterController();
        $filter->clearAllData();

        $properties = Property::sale()->available()->get();
        return view('web.filter', [
            'properties' => $properties,
            'type' => 'sale'
        ]);
    }
    public function buyProperty(Request $request)
    {
        $property = Property::where('slug', $request->slug)->first();
        return view('web.property', [
            'property' => $property,
            'type' => 'sale'
        ]);

    }
    public function filter()
    {
        $filter = new FilterController();
        $itemProperties = $filter->createQuery('id');

        foreach ($itemProperties as $property) {
            $properties[] = $property->id;
        }

        if(!empty($properties)){
            $properties = Property::whereIn('id', $properties)->get();
        }else{
            $properties = Property::all();
        }
        return view('web.filter', [
            'properties' => $properties
        ]);
    }
}
