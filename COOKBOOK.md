Seguono gli passaggi utilizzati per realizzare questo template a partire dal precedente.

# REFACTORING DASHBOARD - LAYOUTS E VIEWS

- Oltre alla rotta di base avremo una rotta `/dashboard` la cui View si trova sotto [`/resources/views/dashboard.blade.php`](/resources/views/dashboard.blade.php)
- Creiamo un file `layouts/admin.blade.php`, che sarà il layout da utilizzare per la dashboard e tutte le pagine del back-office
	<details>
	<summary>Clicca per mostrare il codice</summary>

	```php
	<!doctype html>
	<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<title>{{ config('app.name', 'Laravel') }}</title>

		<!-- Fontawesome 6 cdn -->
		<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css' integrity='sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==' crossorigin='anonymous' referrerpolicy='no-referrer' />

		<!-- Fonts -->
		<link rel="dns-prefetch" href="//fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

		<!-- Usando Vite -->
		@vite(['resources/js/app.js'])
	</head>

	<body>
		<div id="app">

			<div class="container-fluid vh-100">
				<div class="row h-100">
					<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-dark navbar-dark sidebar collapse">
						<div class="position-sticky pt-3">
							<ul class="nav flex-column">

								<li class="nav-item">
									<a class="nav-link text-white" href="/">
										<i class="fa-solid fa-home-alt fa-lg fa-fw"></i> Home
									</a>
								</li>

								<li class="nav-item">
									<a class="nav-link text-white {{ Route::currentRouteName() == 'admin.dashboard' ? 'bg-secondary' : '' }}" href="{{route('admin.dashboard')}}">
										<i class="fa-solid fa-tachometer-alt fa-lg fa-fw"></i> Dashboard
									</a>
								</li>

								<li class="nav-item">
									<a class="nav-link text-white" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
										<i class="fa-solid fa-sign-out-alt fa-lg fa-fw"></i> {{ __('Logout') }}
									</a>
									<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
										@csrf
									</form>
								</li>

							</ul>

						</div>
					</nav>

					<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
						@yield('content')
					</main>
				</div>
			</div>

		</div>
	</body>

	</html>
	```
	</details>

	
- Modifichiamo [`la View della dashboard`](/resources/views/dashboard.blade.php) per utilizzare il layout appena creato
	<details>
	<summary>Clicca per mostrare il codice</summary>
	
	```php
	@extends('layouts.admin')

	@section('content')
	<div class="container-fluid mt-4">
		<div class="row justify-content-center">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">{{ __('Dashboard') }}</div>

					<div class="card-body">
						@if (session('status'))
						<div class="alert alert-success" role="alert">
							{{ session('status') }}
						</div>
						@endif

						{{ __('You are logged in!') }}
					</div>
				</div>
			</div>
		</div>
	</div>
	@endsection
	```
	</details>

 - Attenzione!! A questo punto avrete degli errori se non completate prima la parte successiva "ROUTES E CONTROLLER", non vi spaventate, è normale

# REFACTORING DASHBOARD - ROUTES E CONTROLLER

- Creare un controller `Admin/DashboardController` 
	```bash
	php artisan make:controller Admin/DashboardController
	```
- Restituire la View della Dashboard dal metodo index del DashboardController
	```php
	public function index() {
		return view('admin.dashboard');
	}
	```
- Riorganizzare le cartella delle Views per il backoffice:

	Creare una cartella `/resources/views/admin`

	Spostare la View della dashboard da `/resources/views/dashboard.blade.php` alla cartella admin apppena creata

- Modificare il file delle Routes `web.php` come segue:
	<details>
	<summary>Clicca per mostrare il codice</summary>

	```php
	use Illuminate\Support\Facades\Route;
	use App\Http\Controllers\Admin\DashboardController; //<---- Import del controller precedentemente creato!
	
	/* ... */

	Route::get('/', function () {
		return view('welcome');
	});

	Route::middleware(['auth'])
		->prefix('admin') //definisce il prefisso "admin/" per le rotte di questo gruppo
		->name('admin.') //definisce il pattern con cui generare i nomi delle rotte cioè "admin.qualcosa"
		->group(function () {
		
			//Siamo nel gruppo quindi:
			// - il percorso "/" diventa "admin/"
			// - il nome della rotta ->name("dashboard") diventa ->name("admin.dashboard")
			Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

	});

	require __DIR__.'/auth.php';
	```
	</details>

- Modificare il valore della costante HOME nel file `app/Provider/RouteServiceProvider.php`
	```php
	public const HOME = '/admin';
	```
	In questo modo, dopo l’autenticazione, l’utente verrà reindirizzato alla dashboard, che risponde alla rotta `/admin`

- Modificare il link alla dashboard dal menu del layout di base qui: resources/views/layouts/app.blade.php:70
	```php
    <a class="dropdown-item" href="{{ url('dashboard') }}">{{__('Dashboard')}}</a>
	```
     modificare in:
	```php
    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">{{__('Dashboard')}}</a>
	```
