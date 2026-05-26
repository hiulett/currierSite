<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>{{ config('app.name') }} | Acceso</title>

	<link rel="preconnect" href="https://fonts.gstatic.com/">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <x-brand-styles />
	<style>
		body {
            background-color: #f8fafc;
            background-size: cover;
		}
	</style>
</head>

<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
	<main class="d-flex w-100 h-100">
		<div class="container d-flex flex-column">
			<div class="row vh-100">
				<div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
					<div class="d-table-cell align-middle">

						<div class="text-center mt-4 mb-4">
							<h1 class="h2 text-white font-black uppercase tracking-widest">{{ config('app.name') }}</h1>
						</div>

						<div class="card shadow-lg border-0 rounded-3">
							<div class="card-body">
								<div class="m-sm-3">
									{{ $slot }}
								</div>
							</div>
						</div>

                        <div class="text-center mb-3 text-white-50 small">
                            &copy; {{ date('Y') }} LogiSaaS Global
                        </div>

					</div>
				</div>
			</div>
		</div>
	</footer>

</body>

</html>
