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
  <!-- Main Content -->
<main class="p-8">
    <h1 class="text-center text-2xl font-bold mb-8">
     OUR SERVICES
    </h1>
    <div class="grid grid-cols-3 gap-8 max-w-6xl mx-auto">
     <!-- Recruitment -->
     <div class="text-center">
      <img alt="Recruitment Icon" class="mx-auto mb-4" height="100" src="{{ asset('images/recruitment.png') }}" width="100"/>
      <h2 class="text-xl font-bold">
       Recruitment
      </h2>
      <p>
       Pencarian kandidat-kandidat terbaik sesuai kualifikasi yang dibutuhkan perusahaan
      </p>
     </div>
     <!-- Psiko Test -->
     <div class="text-center">
      <img alt="Psiko Test Icon" class="mx-auto mb-4" height="100" src="{{ asset('images/psiko test.jpeg') }}" width="100"/>
      <h2 class="text-xl font-bold">
       Psiko Test
      </h2>
      <p>
       Menggali potensi yang dimiliki oleh kandidat
      </p>
     </div>
     <!-- Assessment -->
     <div class="text-center">
      <img alt="Assessment Icon" class="mx-auto mb-4" height="100" src="{{ asset('images/assesment.jpeg') }}" width="100"/>
      <h2 class="text-xl font-bold">
       Assessment
      </h2>
      <p>
       Menggali potensi dan kompetensi yang dimiliki oleh kandidat
      </p>
     </div>
    </div>
    
    <!-- Second row with 2 items centered -->
    <div class="grid grid-cols-2 gap-8 max-w-4xl mx-auto mt-8">
     <!-- Assessment Center -->
     <div class="text-center">
      <img alt="Assessment Center Icon" class="mx-auto mb-4" height="100" src="{{ asset('images/assesment center.jpeg') }}" width="100"/>
      <h2 class="text-xl font-bold">
       Assessment Center
      </h2>
      <p>
       Menggali kompetensi kandidat melalui multi metode dan multi asesor
      </p>
     </div>
     <!-- Head Hunter -->
     <div class="text-center">
      <img alt="Head Hunter Icon" class="mx-auto mb-4" height="100" src="{{ asset('images/head hunter.jpeg') }}" width="100"/>
      <h2 class="text-xl font-bold">
       Head Hunter
      </h2>
      <p>
       Menggali kompetensi kandidat melalui multi metode dan multi asesor
      </p>
     </div>
    </div>
   </main>
 </body>
</html>
