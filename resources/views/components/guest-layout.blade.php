<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>{{ config('app.name') }} | Acceso</title>

	<link rel="preconnect" href="https://fonts.gstatic.com/">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="{{ asset('adminkit/css/light.css') }}" rel="stylesheet">
    <x-brand-styles />
	<style>
		body {
            background: #77b url('{{ asset('adminkit/img/backblue.gif') }}') !important;
            background-size: cover;
            background-attachment: fixed;
		}
        .guest-card {
            border-radius: 1.25rem;
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.9);
        }
	</style>
</head>

<body data-theme="default">
	<main class="d-flex w-100 h-100">
		<div class="container d-flex flex-column">
			<div class="row vh-100">
				<div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
					<div class="d-table-cell align-middle">

						<div class="text-center mb-4">
							<h1 class="h2 text-white font-black uppercase tracking-widest">{{ config('app.name') }}</h1>
						</div>

						<div class="card shadow-lg border-0 guest-card">
							<div class="card-body p-4 p-md-5">
								{{ $slot }}
							</div>
						</div>

                        <div class="text-center mt-4 text-white-50 small font-bold">
                            &copy; {{ date('Y') }} {{ config('app.name') }}
                        </div>

					</div>
				</div>
			</div>
		</div>
	</main>

	<script src="{{ asset('adminkit/js/app.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
    @stack('scripts')
    <script>
        // Kill any lingering service workers from old PWA packages
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.getRegistrations().then(function(registrations) {
                for(let registration of registrations) {
                    registration.unregister();
                }
            });
        }
    </script>
</body>

</html>

