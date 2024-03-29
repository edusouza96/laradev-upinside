@extends('web.master.master')

@section('content')
    <div class="main_contact py-5 bg-light text-center">
        <div class="container">
            <h1 class="text-front">Entre em Contato Conosco</h1>
            <p class="mb-0">Quer conversar com um corretor exclusivo e ter o atendimento diferenciado em busca do seu imóvel
                dos sonhos?</p>
            <p>Preencha o formulário abaixo e vamos lhe direcionar para alguém que entende a sua necessidade!</p>

            <div class="row text-left">
                <form action="{{ route('web.sendEmail') }}" method="POST" autocomplete="off">
                    @csrf
                    <h2 class="icon-envelope text-black-50">Envie um e-mail</h2>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Insira seu nome" name="name">
                    </div>

                    <div class="form-group">
                        <input type="email" class="form-control" placeholder="Insira seu melhor e-mail" name="email">
                    </div>

                    <div class="form-group">
                        <input type="tell" class="form-control" placeholder="Insira seu telefone com DDD" name="cell">
                    </div>

                    <div class="form-group">
                        <textarea name="message" rows="5" class="form-control" placeholder="Escreva sua mensagem..."></textarea>
                    </div>

                    <div class="form-group text-right">
                        <button class="btn btn-front">Enviar Contato</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="main_contact_types bg-white p-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-12 col-md-4">
                    <h2 class="icon-envelope">Por E-mail</h2>
                    <p>Nossos atendentes irão entrar em contato com você assim que possível.</p>
                    <p class="pt-2"><a href="mailto:contato@laradev.com.br" class="text-front">contato@laradev.com.br</a></p>
                </div>

                <div class="col-12 col-md-4">
                    <h2 class="icon-phone">Por Telefone</h2>
                    <p>Estamos disponíveis nos números abaixo no horário comercial.</p>
                    <p class="pt-2 text-front">+55 (48) 3322-1234</p>
                </div>

                <div class="col-12 col-md-4">
                    <h2 class="icon-share-alt">Redes Sociais</h2>
                    <p>Fique por dentro do tudo o que a gente compartilha em nossas redes sociais!</p>
                    <p><button class="btn btn-front icon-facebook icon-notext"></button> <button class="btn btn-front icon-twitter icon-notext"></button> <button class="btn btn-front icon-instagram icon-notext"></button></p>
                </div>
            </div>
        </div>
    </div>
@endsection
