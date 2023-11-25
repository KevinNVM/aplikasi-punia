<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles()

    <style>
        @media only screen and (max-width: 760px),
        (min-device-width: 760px) and (max-device-width: 760px) {

            /* Force table to not be like tables anymore */
            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
            }

            /* Hide table headers (but not display: none;, for accessibility) */
            thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            tr {
                margin: 0 0 1rem 0;
            }


            td {
                /* Behave  like a "row" */
                border: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.5);
                position: relative;
                padding-left: 50%;
            }

            td:before {
                /* Now like a table header */
                position: absolute;
                /* Top/left values mimic padding */
                top: 0;
                left: 6px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
            }

            td:nth-of-type(1):before {
                content: "";
            }

            td:nth-of-type(2):before {
                content: "Jenis";
            }

            td:nth-of-type(3):before {
                content: "Nama";
            }

            td:nth-of-type(4):before {
                content: "Jumlah";
            }

            td:nth-of-type(5):before {
                content: "Tanggal";
            }



        }
    </style>
</head>

<body class="antialiased">
    <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
        {{-- @if (Route::has('login'))
            <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
                @else
                    <a href="{{ route('login') }}"
                        class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log
                        in</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                    @endif
                @endauth
            </div>
        @endif --}}

        <div class="max-w-7xl mx-auto p-6 lg:p-8">

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <x-welcome />
                    </div>
                </div>
            </div>

            <section id="public-table">
                @livewire('public-tx-table')
            </section>
        </div>
    </div>

    @livewireScripts()
</body>

</html>
