@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verificá tu dirección de correo (e-mail)') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Un link para la verificación de la contraseña fue enviada a tu dirección de e-mail.') }} 
                        </div>
                    @endif

                    {{ __('Antes de proseguir, por favor, revisa tu e-mail en busca del link de verificación.') }} 
                    {{ __('Si no recibiste el e-mail') }}, 
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('haz click aquí para recibir otro.') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
