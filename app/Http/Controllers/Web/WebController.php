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

    public function experience()
    {
        $filter = new FilterController();
        $filter->clearAllData();

        $properties = Property::whereNotNull('experience')->get();

        return view('web.filter', [
            'properties' => $properties
        ]);
    }

    public function experienceCategory(Request $request)
    {
        $filter = new FilterController();
        $filter->clearAllData();

        if($request->category == 'cobertura'){
            $properties = Property::where('experience', 'Cobertura')->get();
        }else if($request->category == 'alto-padrao'){
            $properties = Property::where('experience', 'Alto Padrão')->get();
        }else if($request->category == 'de-frente-para-o-mar'){
            $properties = Property::where('experience', 'De Frente para o Mar')->get();
        }else if($request->category == 'condominio-fechado'){
            $properties = Property::where('experience', 'Condomínio Fechado')->get();
        }else if($request->category == 'compacto'){
            $properties = Property::where('experience', 'Compacto')->get();
        }else if($request->category == 'lojas-e-salas'){
            $properties = Property::where('experience', 'Lojas e Salas')->get();
        }else{
            $properties = Property::whereNotNull('experience')->get();
        }

        return view('web.filter', [
            'properties' => $properties
        ]);
    }
}
