<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<img src="https://img.shields.io/badge/template-tested-green" alt="Build Status">
<img src="https://img.shields.io/badge/laravel-10.10-red" alt="Laravel 10.10" />
<img src="https://img.shields.io/badge/laravel_breeze-1.28.1-red" alt="Laravel Breeze 1.28.1" />
<img src="https://img.shields.io/badge/bootstrap-5.3.2-red" alt="Bootstrap 5.3.2" />
<img src="https://img.shields.io/badge/vite-5.00-red" alt="Vite 5.00" />
<br>
<img src="https://img.shields.io/badge/license-boolean_95-blue" alt="Licensed to Boolean Students #95" />
<img src="https://img.shields.io/badge/license-boolean_109-blue" alt="Licensed to Boolean Students #109" />
<img src="https://img.shields.io/badge/license-boolean_125-blue" alt="Licensed to Boolean Students #125" />
</p>

# INFO

Questo git-template fornisce lo scaffold di una web application realizzata con Laravel 10, Blade, Breeze, SCSS, Bootstrap e Vite. 

- [Documentazione Laravel 10.x](https://laravel.com/docs/10.x).
- [Documentazione Laravel Breeze](https://laravel.com/docs/10.x/starter-kits).

# SETUP INIZIALE

- Creare un repository a partire da questo template, cliccando in alto a destra sul pulsantone verde `Use this template` e poi su `Create a new repository`
- Clonare il repository appena creato sul proprio PC
- Da phpMyAdmin creare un database, importarvi i dati e segnarvi il nome dato al DB
- Creare un file `.env`. Si può procedere copiandolo da `.env.example`
- Per creare la APP_KEY nel `.env`, lanciare il comando dedicato, ma prima installare le dipendenze composer
	```bash
    composer install
	php artisan key:generate
	```
 - Installare anche le dipendenze NPM
	```bash
	npm i
	```
- Ri-controllare che tutti i dati nel `.env` siano corretti (attenzione al database!)
- Lanciare migrazioni e seeder
	```bash
	php artisan migrate:fresh --seed
	```
- Lanciare il progetto tramite il server built-in di PHP
	```bash
	php artisan serve
	```
- Lanciare vite
	```bash
	npm run dev
	```
- Puntare il browser all'indirizzo mostrato in terminale per controllare che tutto funzioni.
- Navigate all'indirizzo per fare [login](http://localhost:8000/admin). Potete registrare un nuovo utente o usare:
	```bash
	user: luca@lambia.it
	pass: 1backdoor2big
	```
- Una volta loggati dovreste poter raggiungere [la dashboard](http://localhost:8000/admin)

# RISORSE: MODEL, CONTROLLER, MIGRATION, SEEDER

Si possono creare tutti insieme con:

```bash
php artisan make:model NomeModello -rmsR
```

-r o --resource indica se creare un controller di tipo Resource Controller
<br>
-c o --controller crea un normale Controller (se non usato insieme a -r)
<br>
-m o --migration crea la Migration per il modello
<br>
-s o --seed crea il Seeder per il modello
<br>
-R o --requests crea le FormRequest e le usa nel Resource Controller
<br>

Qui trovate la lista dei parametri accettati da [`make:model`](https://artisan.page/#makemodel)

A questo punto potete andare a definire il comportamento di migration e seeder nei relativi file.

Infine lanciate entrambi usando il comando:
```bash
php artisan migrate:fresh --seed
```

# CRUD

Una volta creato un Resource Controller richiamatelo dalla rotte, come abbiamo sempre fatto.
```php
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController; // <---- Importare il controller da usare!!

Route::resource('posts', PostController::class);
```

Testato che tutto funzioni possiamo valutare di spostarlo sotto autenticazione.

Spostare il Resource Controller appena creato da `App\Http\Controllers` a una nuova cartella `App\Http\Controllers\Admin` 

Nel controller correggere il namespace ed importare il Controller generico
```php
<?php
namespace App\Http\Controllers\Admin; // era "App\Http\Controllers"
use App\Http\Controllers\Controller; // Controller di base da importare (!)
//...ecc
```

Ora possiamo spostare le rotte del Resource Controller all'interno del blocco protetto da autenticazione:

```php

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

	Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
	
	// Admin Post CRUD
	Route::resource('posts', PostController::class);
});
```

A questo punto avrebbe senso anche spostare tutte le viste richiamate dal controller. Se prima si creava sotto "views" una cartella "nomeRisorsa" con tutte le viste:
```bash
/resources/views/posts/*.blade.php
```
Adesso invece quella cartella andrà creata sotto "views/admin"
```bash
/resources/views/admin/posts/*.blade.php
```
