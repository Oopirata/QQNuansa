<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>
        Legalitas
    </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Kaisei+Opti&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Kaisei Opti', sans-serif;
        }
    </style>
</head>
<body class="bg-white">
    <header class="bg-blue-200 p-4 flex justify-between items-center">
        <div class="flex items-center -mr-16">
            <img alt="Company Logo" class="h-14 w-14"  src="{{ asset('images/QQ.png') }}"/>
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
        <div class=" -mr-72" >
            <a class="text-[#5932EA] font-bold transition-colors duration-200 hover:text-[#4D62D7]" href="/uploadcv">
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
    <main class="p-8 -mt-8">
        <div class="bg-yellow-100 p-4 flex justify-center space-x-16 w-2/5 mx-auto">
            <a class="text-gray-700 text-center transition-colors duration-200 hover:text-[#CDC052]" href="/companyprofile">
                Company Profile
            </a>
            <a class="text-gray-700 text-center transition-colors duration-200 hover:text-[#CDC052]" href="/legalitas">
                Legalitas
            </a>
            <a class="text-gray-700 text-center transition-colors duration-200 hover:text-[#CDC052]" href="/missions">
                Missions
            </a>
            <a class="text-gray-700 text-center transition-colors duration-200 hover:text-[#CDC052]" href="/services">
                Services
            </a>
        </div>
    </main>s
    <section class="mt-8 ml-40 mr-40">
        <img alt="Legalitas" class=" w-3/4 align-middle justify-center ml-32 "  src="{{ asset('images/Legalitas.png') }}"/>
    </section>
</body>
</html>