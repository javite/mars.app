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
    <div class="w-5"></div>
    <span id="name"><img src="/images/logo-web.png" class="logo-svg"  alt="" >MARS - <strong>SunFlash</strong></span>
        <form class="contenedor-usuario" id="logout-form" action="logout" method="POST" >
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

