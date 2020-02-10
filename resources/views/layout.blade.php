@extends('template')

@section('css')
    @yield('css')
@endsection

@section('js')
    @yield('js')
@endsection

@section('navbar')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-2">
    <div class="container">
        <div class="w-5"></div>
        <span id="name-grower">
        <img src="images/isologo_grower-lab_thin.svg" class="logo-svg"  alt="" >
        grower-lab</span>
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

@section('footer')
<div class="container-fluid height-bar"></div>
<footer class="footer container-fluid border-top">
    <div class="container  d-flex justify-content-around">
        <a class="menu-icon d-flex flex-column justify-content-center" href="main">
            <img src="images/isologo_grower-lab.svg" class="menu-icon-img"alt="">
            <p class="text-icon">inicio</p>
        </a>
        <a class="menu-icon d-flex flex-column justify-content-center" href="stats">
            <img src="images/statistics.svg" class="menu-icon-img"alt="">
            <p class="text-icon">estadísticas</p>
        </a>
        <a class="menu-icon d-flex flex-column justify-content-center" href="program">
            <img src="images/calendar.svg" class="menu-icon-img"alt="">
            <p class="text-icon">programas</p>
        </a>
        <a class="menu-icon d-flex flex-column justify-content-center" href="#">
            <img src="images/settings.svg" class="menu-icon-img"alt="">
            <p class="text-icon">config.</p>
        </a>
    </div>
</footer>
@endsection
