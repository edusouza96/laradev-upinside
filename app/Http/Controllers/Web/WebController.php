<?php

namespace LaraDev\Http\Controllers\Web;

use LaraDev\Property;
use Illuminate\Http\Request;
use LaraDev\Mail\Web\Contact;
use Illuminate\Support\Facades\Mail;
use LaraDev\Http\Controllers\Controller;

class WebController extends Controller
{
    public function home()
    {
        $head = $this->seo->render(
            env('APP_NAME'),
            'Encontre o imóvel dos seus sonhos na melhor e mais completa imobiliaria do sul da ilha de Florianópolis',
            route('web.home'),
            asset('frontend/assets/images/logo.png')
        );

        $propertyForSale = Property::sale()->available()->limit(3)->get();
        $propertyForRent = Property::rent()->available()->limit(3)->get();
        return view('web.home', [
            'propertyForSale' => $propertyForSale,
            'propertyForRent' => $propertyForRent,
            'head' => $head,
        ]);
    }

    public function spotlight()
    {
        $head = $this->seo->render(
            env('APP_NAME'),
            'Confira nossos maiores empreendimentos e lançamentos no sul da ilha de Florianópolis',
            route('web.spotlight'),
            asset('frontend/assets/images/logo.png')
        );

        return view('web.spotlight', [
            'head' => $head,
        ]);
    }

    public function contact()
    {
        $head = $this->seo->render(
            env('APP_NAME'),
            'Quer conversar com um corretor exclusivo e ter o atendimento diferenciado em busca do seu imóvel dos sonhos? Entre em contato com nossa equipe',
            route('web.contact'),
            asset('frontend/assets/images/logo.png')
        );
        return view('web.contact', [
            'head' => $head
        ]);
    }

    public function sendEmail(Request $request)
    {
        $data = [
            'reply_name' => $request->name,
            'reply_email' => $request->email,
            'cell' => $request->cell,
            'message' => $request->message,
        ];

        /** Para testar em tela*/
        // return new Contact($data);
        Mail::send(new Contact($data));

        return redirect()->route('web.sendEmailSucess');
    }

    public function sendEmailSucess()
    {
        return view('web.contact_success');
    }
    public function rent()
    {
        $head = $this->seo->render(
            env('APP_NAME'),
            'Alugue o imóvel dos seus sonhos na melhor e mais completa imobiliaria do sul da ilha de Florianópolis',
            route('web.rent'),
            asset('frontend/assets/images/logo.png')
        );

        $filter = new FilterController();
        $filter->clearAllData();

        $properties = Property::rent()->available()->get();
        return view('web.filter', [
            'properties' => $properties,
            'type' => 'rent',
            'head' => $head,
        ]);
    }
    public function rentProperty(Request $request)
    {
        $property = Property::where('slug', $request->slug)->first();

        $property->increment('views');
        // $property->views = $property->views + 1;
        $property->save();

        $head = $this->seo->render(
            env('APP_NAME'),
            $property->headline ?? $property->title,
            route('web.rentProperty', ['slug' => $property->slug]),
            $property->cover()
        );
        return view('web.property', [
            'property' => $property,
            'type' => 'rent',
            'head' => $head,
        ]);
    }
    public function buy()
    {
        $head = $this->seo->render(
            env('APP_NAME'),
            'Compre o imóvel dos seus sonhos na melhor e mais completa imobiliaria do sul da ilha de Florianópolis',
            route('web.buy'),
            asset('frontend/assets/images/logo.png')
        );

        $filter = new FilterController();
        $filter->clearAllData();

        $properties = Property::sale()->available()->get();
        return view('web.filter', [
            'properties' => $properties,
            'type' => 'sale',
            'head' => $head,
        ]);
    }
    public function buyProperty(Request $request)
    {
        $property = Property::where('slug', $request->slug)->first();

        $property->increment('views');
        // $property->views = $property->views + 1;
        $property->save();

        $head = $this->seo->render(
            env('APP_NAME'),
            $property->headline ?? $property->title,
            route('web.buyProperty', ['slug' => $property->slug]),
            $property->cover()
        );

        return view('web.property', [
            'property' => $property,
            'type' => 'sale',
            'head' => $head,
        ]);

    }
    public function filter()
    {
        $head = $this->seo->render(
            env('APP_NAME'),
            'Filtre o imóvel dos seus sonhos na melhor e mais completa imobiliaria do sul da ilha de Florianópolis',
            route('web.filter'),
            asset('frontend/assets/images/logo.png')
        );

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
            'properties' => $properties,
            'head' => $head,
        ]);
    }

    public function experience()
    {
        $head = $this->seo->render(
            env('APP_NAME'),
            'Viva a experiencia de encontrar o imóvel dos seus sonhos na melhor e mais completa imobiliaria do sul da ilha de Florianópolis',
            route('web.experience'),
            asset('frontend/assets/images/logo.png')
        );

        $filter = new FilterController();
        $filter->clearAllData();

        $properties = Property::whereNotNull('experience')->get();

        return view('web.filter', [
            'properties' => $properties,
            'head' => $head,
        ]);
    }

    public function experienceCategory(Request $request)
    {
        $filter = new FilterController();
        $filter->clearAllData();

        if($request->category == 'cobertura'){
            $head = $this->seo->render(
                env('APP_NAME'),
                'Viva a experiencia de morar na cobertura ...',
                route('web.experienceCategory', ['category' => 'cobertura']),
                asset('frontend/assets/images/logo.png')
            );
            $properties = Property::where('experience', 'Cobertura')->get();

        }else if($request->category == 'alto-padrao'){
            $head = $this->seo->render(
                env('APP_NAME'),
                'Viva a experiencia de morar num imóvel de alto padrão ...',
                route('web.experienceCategory', ['category' => 'alto-padrao']),
                asset('frontend/assets/images/logo.png')
            );
            $properties = Property::where('experience', 'Alto Padrão')->get();

        }else if($request->category == 'de-frente-para-o-mar'){
            $head = $this->seo->render(
                env('APP_NAME'),
                'Viva a experiencia de morar num imóvel de alto padrão ...',
                route('web.experienceCategory', ['category' => 'de-frente-para-o-mar']),
                asset('frontend/assets/images/logo.png')
            );
            $properties = Property::where('experience', 'De Frente para o Mar')->get();

        }else if($request->category == 'condominio-fechado'){
            $head = $this->seo->render(
                env('APP_NAME'),
                'Viva a experiencia de morar num imóvel de alto padrão ...',
                route('web.experienceCategory', ['category' => 'condominio-fechado']),
                asset('frontend/assets/images/logo.png')
            );
            $properties = Property::where('experience', 'Condomínio Fechado')->get();

        }else if($request->category == 'compacto'){
            $head = $this->seo->render(
                env('APP_NAME'),
                'Viva a experiencia de morar num imóvel de alto padrão ...',
                route('web.experienceCategory', ['category' => 'compacto']),
                asset('frontend/assets/images/logo.png')
            );
            $properties = Property::where('experience', 'Compacto')->get();

        }else if($request->category == 'lojas-e-salas'){
            $head = $this->seo->render(
                env('APP_NAME'),
                'Viva a experiencia de morar num imóvel de alto padrão ...',
                route('web.experienceCategory', ['category' => 'lojas-e-salas']),
                asset('frontend/assets/images/logo.png')
            );
            $properties = Property::where('experience', 'Lojas e Salas')->get();

        }else{
            $head = $this->seo->render(
                env('APP_NAME'),
                'Viva a experiencia de morar na praia em uma das capitais mais bonitas do Brasil',
                route('web.experience'),
                asset('frontend/assets/images/logo.png')
            );
            $properties = Property::whereNotNull('experience')->get();
        }

        return view('web.filter', [
            'properties' => $properties,
            'head' => $head,
        ]);
    }
}
