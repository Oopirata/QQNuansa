<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>
        Company Profile
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
        <nav class="space-x-10 -ml-80 mr-96 ml-">
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
    </main>
        <section class="mt-8 ml-40 mr-40">
            <div style="display: flex; justify-content: center; align-items: center;">
                <img alt="Missions Icon" class="h-36 w-36 -mt-5 mb-5 -ml-10" src="{{ asset('images/missions.jpeg') }}"/>
            </div>
            
            <h1 class="text-center text-2xl font-bold -ml-4"> 
                OUR MISSION
            </h1>
            <div style="display: flex; justify-content:center; align-items:center">
                <p class="mt-4 ml-10 mr-5 leading-relaxed text-lg text-center">
                    Memberikan layanan untuk meningkatkan potensi individu secara optimal & positif dari berbagai aspek kognitif, emosi, sosial, komunikasi, perilaku, spiritual, visual motorik. Menyediakan jasa aplikasi psikologi dan konsultan bagi kepentingan pengembangan SDM organisasi dan perusahaan. Menyelenggarakan berbagai aktivitas, program dan sistem pelayanan aplikasi psikologi untuk pengembangan SDM.
                </p>
            </div>
        </section>
</body>
</html>