@extends('template')

@section('css')
    @yield('css')
@endsection

@section('js')
    @yield('js')
@endsection

@section('navbar')
<nav class="navbar">
    <div class="container">
    <img src="/images/logo.svg" class="logo-svg"  alt="" >
    <span id="name"><strong>MARS</strong> Optoelectrónica</span>
    <form class="contenedor-usuario" id="logout-form" action="{{ route('logout') }}" method="POST" >
                                        @csrf
        <button type="submit" id="btn-logout">
            <a class="usuario" href="">
                <i class="material-icons user-icon">account_circle</i>
            </a>
        </button>
    </form>
    </div>
</nav>

@endsection

@section('content')
    @yield('content')
@endsection

