<?php

namespace LaraDev\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LaraDev\Http\Controllers\Controller;

class FilterController extends Controller
{
    public function search(Request $request)
    {
        session()->remove('category');
        session()->remove('type');
        session()->remove('neighborhood');
        session()->remove('bedrooms');
        session()->remove('suites');

        if($request->search === 'buy'){
            session()->put('sale', true);
            session()->remove('rent');
            $properties = $this->createQuery('category');
        }

        if($request->search === 'rent'){
            session()->put('rent', true);
            session()->remove('sale');
            $properties = $this->createQuery('category');
        }

        if($properties->count()){
            foreach ($properties as $categoryProperty) {
                $category[] = $categoryProperty->category;
            }

            $collect = collect($category);
            return response()->json($this->setResponse('success', $collect->unique()->toArray()));
        }

        return response()->json($this->setResponse('fail', [], 'Ops, não foi retornado nenhum dado  para essa pesquisa!'));
    }

    public function category(Request $request)
    {
        session()->remove('type');
        session()->remove('neighborhood');
        session()->remove('bedrooms');
        session()->remove('suites');

        session()->put('category', $request->search);
        $typeProperties = $this->createQuery('type');

        if($typeProperties->count()){
            foreach ($typeProperties as $property) {
                $type[] = $property->type;
            }

            $collect = collect($type);
            return response()->json($this->setResponse('success', $collect->unique()->toArray()));
        }

        return response()->json($this->setResponse('fail', [], 'Ops, não foi retornado nenhum dado  para essa pesquisa!'));
    }

    public function type(Request $request)
    {
        session()->remove('neighborhood');
        session()->remove('bedrooms');
        session()->remove('suites');

        session()->put('type', $request->search);
        $neighborhoodProperties = $this->createQuery('neighborhood');

        if($neighborhoodProperties->count()){
            foreach ($neighborhoodProperties as $property) {
                $neighborhood[] = $property->neighborhood;
            }

            $collect = collect($neighborhood);
            return response()->json($this->setResponse('success', $collect->unique()->toArray()));
        }

        return response()->json($this->setResponse('fail', [], 'Ops, não foi retornado nenhum dado  para essa pesquisa!'));
    }

    public function neighborhood(Request $request)
    {
        session()->remove('bedrooms');
        session()->remove('suites');

        session()->put('neighborhood', $request->search);
        $bedroomsProperties = $this->createQuery('bedrooms');

        if($bedroomsProperties->count()){
            foreach ($bedroomsProperties as $property) {
                if($property->bedrooms === 0 || $property->bedrooms === 1){
                    $bedrooms[] = $property->bedrooms . ' quarto';
                }else{
                    $bedrooms[] = $property->bedrooms . ' quartos';
                }
            }

            $bedrooms[] = 'Indiferente';

            $collect = collect($bedrooms)->unique()->toArray();
            sort($collect);
            return response()->json($this->setResponse('success', $collect));
        }

        return response()->json($this->setResponse('fail', [], 'Ops, não foi retornado nenhum dado  para essa pesquisa!'));
    }

    public function bedrooms(Request $request)
    {
        session()->remove('suites');

        session()->put('bedrooms', $request->search);
        $suitesProperties = $this->createQuery('suites');

        if($suitesProperties->count()){
            foreach ($suitesProperties as $property) {
                if($property->bedrooms === 0 || $property->bedrooms === 1){
                    $suites[] = $property->suites . ' suíte';
                }else{
                    $suites[] = $property->suites . ' suítes';
                }
            }
            $suites[] = 'Indiferente';

            $collect = collect($suites)->unique()->toArray();
            sort($collect);
            return response()->json($this->setResponse('success', $collect));
        }

        return response()->json($this->setResponse('fail', [], 'Ops, não foi retornado nenhum dado  para essa pesquisa!'));
    }

    private function setResponse(string $status, array $data = null, string $message = null)
    {
        return [
            'status' => $status,
            'data' => $data,
            'message' => $message,
        ];
    }
    private function createQuery($field)
    {
        $sale = session('sale');
        $rent = session('rent');
        $category = session('category');
        $type = session('type');
        $neighborhood = session('neighborhood');
        $bedrooms = session('bedrooms');

        return DB::table('properties')
            ->when($sale, function($query, $sale){
                return $query->where('sale', $sale);
            })
            ->when($rent, function($query, $rent){
                return $query->where('rent', $rent);
            })
            ->when($category, function($query, $category){
                return $query->where('category', $category);
            })
            ->when($type, function($query, $type){
                return $query->whereIn('type', $type);
            })
            ->when($neighborhood, function($query, $neighborhood){
                return $query->whereIn('neighborhood', $neighborhood);
            })
            ->when($bedrooms, function($query, $bedrooms){
                if($bedrooms == 'Indiferente'){
                    return $query;
                }

                $bedrooms = (int) $bedrooms;
                return $query->where('bedrooms', $bedrooms);
            })
            ->get([$field]);

    }
}
