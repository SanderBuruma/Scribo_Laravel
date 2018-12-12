<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<img src="{{ asset("svg/cubit32.svg") }}" height="32" alt="blue cube svg icon" /><a class="navbar-brand" href="{{ route('home') }}">Scribo Cursim</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	
	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item active">
				<a class="nav-link" href="{{ route('home') }}">Home<span class="sr-only">(current)</span></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{ route('about') }}">About</a>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="fas fa-user"></i>@guest -Guest- @else {{ Auth::user()->name }} @endguest
				</a>
				<div class="dropdown-menu" aria-labelledby="navbarDropdown">
					@guest
					<a class="dropdown-item" href="{{ route('login')}}"><i class="fas fa-sign-in-alt"></i> Log In!</a>
					<a class="dropdown-item" href="{{ route('register') }}"><i class="fas fa-user-plus"></i> Register!</a>
					@else
					
					<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
						@csrf
					</form>
					<a class="dropdown-item" href="{{ route('logout') }}"
						onclick="event.preventDefault();
						document.getElementById('logout-form').submit();">
						<i class="fas fa-sign-out-alt"></i> {{ __('Log Out!') }}
					</a>

					<div class="dropdown-divider"></div>
						@if(Auth::user()->hasRole(Auth::user(),'3'))
							<a class="dropdown-item" href="{{ route('admin.index') }}"><i class="fas fa-toolbox"></i> Admin Interface</a>
						@endif
						<a class="dropdown-item" href="{{ route('user.index') }}"><i class="fas fa-user-edit"></i> User Interface</a>
					@endguest
				</div>
			</li>
			{{-- <li class="nav-item">
				<a class="nav-link" href="{{ route('admin.index') }}">Admin</a>
			</li> --}}
		</ul>
		<form class="form-inline my-2 my-lg-0">
		<input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
		<button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Search</button>
		</form>
	</div>
</nav>