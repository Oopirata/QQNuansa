<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pilih Posisi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kaisei+Opti&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Kaisei Opti', sans-serif;
        }
    </style>
</head>
<body class=" bg-white">
    <header class="bg-blue-200 p-4 flex justify-between items-center">
        <div class="flex items-center -mr-16">
            <img alt="Company Logo" class="h-14 w-14" src="{{ asset('images/QQ.png') }}"/>
        </div>
        <nav class="space-x-10 -ml-60 mr-80">
            <a class="text-blue-900 transition-colors duration-200 hover:text-[#4D62D7]" href="/companyprofile">
                Tentang Kami
            </a>
            <a class="text-blue-900 transition-colors duration-200 hover:text-[#4D62D7]" href="#">
                Psiko Test
            </a>
            <a class="text-blue-900 transition-colors duration-200 hover:text-[#4D62D7]" href="#">
                Konsultasi
            </a>
            <a class="text-blue-900 transition-colors duration-200 hover:text-[#4D62D7]" href="#">
                Pelatihan
            </a>
        </nav>
        <div class="-mr-72">
            <a class="text-[#5932EA] font-bold transition-colors duration-200 hover:text-[#4D62D7]" href="{{ url('/posisicv') }}">
                We are hiring!!
            </a>
        </div>
        <div class="flex items-center space-x-2">
            <a href="/">
                <button class="bg-green-200 text-green-800 px-4 py-2 rounded mr-3">
                    Log In
                </button>
            </a>
            <i class="fas fa-user-circle text-blue-900 text-3xl"></i>
        </div>
    </header>

    <div class=" min-h-screen flex flex-col items-center justify-center -mt-24">
        <div class="text-center" x-data="{ open: false }">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <img src="{{ asset('images/posisi.jpg') }}" 
                 alt="Cartoon figure with a tie standing next to a large magnifying glass" 
                 class="mx-auto mb-4" 
                 width="200" 
                 height="200">
            
            <p class="text-gray-700 mb-4">
                Mau masuk di posisi yang mana?
            </p>

            <div class="flex justify-center">
                <div class="relative">
                    <button @click="open = !open" 
                            type="button"
                            class="bg-blue-100 text-gray-700 py-2 px-4 rounded w-48 flex items-center justify-between">
                        <span>---Pilih Posisi---</span>
                        <span :class="{'rotate-180': open}" class="transition-transform duration-200">▼</span>
                    </button>

                    <form action="{{ route('positions.apply') }}" method="POST">
                        @csrf
                        <div x-show="open" 
                             @click.away="open = false"
                             class="absolute mt-1 w-48 bg-white border border-gray-200 rounded shadow-lg z-10">
                            @foreach(['Psikolog Klinis', 'Konselor', 'Asisten Psikolog', 'Administrator', 
                                     'Resepsionis', 'Manajer Klinik', 'Koordinator Program', 'Psikometris', 
                                     'Asisten Administrasi', 'Tenaga IT'] as $position)
                                <button type="submit" 
                                        name="position" 
                                        value="{{ $position }}"
                                        class="w-full text-left px-4 py-2 hover:bg-blue-50 text-gray-700">
                                    {{ $position }}
                                </button>
                            @endforeach
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>