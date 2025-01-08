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
        <nav class="space-x-10 -ml-96 mr-96">
            <a class="text-blue-900" href="#">
                Tentang Kami
            </a>
            <a class="text-blue-900" href="#">
                Psiko Test
            </a>
            <a class="text-blue-900" href="#">
                Konsultasi
            </a>
            <a class="text-blue-900" href="#">
                Pelatihan
            </a>
        </nav>
        <div class="flex items-center space-x-2">
            <button class="bg-green-200 text-green-800 px-4 py-2 rounded">
                Log In
            </button>
            <i class="fas fa-user-circle text-blue-900 text-2xl"></i>
        </div>
    </header>
    <main class="p-8 -mt-8">
        <div class="bg-yellow-100 p-4 flex justify-center space-x-16 w-2/5 mx-auto">
            <a class="text-gray-700 text-center" href="#">
                Company Profile
            </a>
            <a class="text-gray-700 text-center" href="/legalitas">
                Legalitas
            </a>
            <a class="text-gray-700 text-center" href="#">
                Missions
            </a>
            <a class="text-gray-700 text-center" href="#">
                Services
            </a>
        </div>
        
        <section class="mt-8 ml-40 mr-40">
            <h1 class="text-center text-2xl font-bold">
                COMPANY PROFILE
            </h1>
            <p class="mt-4">
                PT. Ara Nuansa Katumbiri (ANK) adalah perusahaan konsultan yang berfokus pada pengembangan manajemen sumber daya manusia. Berdiri sejak tahun 2003, perusahaan ini sebelumnya dikenal dengan nama QQNuansa Consultant dan berkantor pusat di Pekalongan, Jawa Tengah. Meskipun demikian, area kerja dan distribusi layanan PT. Ara Nuansa Katumbiri mencakup seluruh wilayah Indonesia, dengan konsentrasi utama di wilayah Jawa Tengah.
            </p>
            <p class="mt-4">
                Perusahaan ini menawarkan tiga layanan utama yang menjadi inti kompetensi dan kegiatan bisnisnya:
            </p>
            <ol class="list-decimal list-inside mt-4">
                <li class="mt-2">
                    <strong>
                        Consulting
                    </strong>
                    <br/>
                    Layanan ini menyediakan jasa konsultan manajemen sumber daya manusia untuk organisasi. Fokus utama adalah membantu organisasi dalam meningkatkan efektivitas pengelolaan SDM melalui pendekatan profesional dan strategis, termasuk penyusunan strategi organisasi, pengembangan sistem SDM, dan solusi manajemen yang relevan.
                </li>
                <li class="mt-2">
                    <strong>
                        Learning Center
                    </strong>
                    <br/>
                    Melalui layanan ini, PT. Ara Nuansa Katumbiri menyelenggarakan berbagai program pelatihan dan pengembangan SDM. Kegiatan yang ditawarkan meliputi:
                </li>
                <li class="mt-2">
                    <strong>
                        Recruitment and Assessment
                    </strong>
                    <br/>
                    Layanan ini bertujuan membantu organisasi dalam mencari, memilih, dan merekomendasikan individu-individu dengan bakat terbaik untuk dikembangkan lebih lanjut. Proses ini mencakup rekrutmen dan penilaian calon karyawan secara menyeluruh, baik untuk kebutuhan industri maupun pendidikan, guna memastikan organisasi mendapatkan SDM yang berkualitas.Dengan berfokus pada pengelolaan sumber daya manusia, PT. Ara Nuansa Katumbiri berperan sebagai mitra strategis bagi organisasi dalam menciptakan tenaga kerja yang produktif dan kompetitif.
                </li>
            </ol>
        </section>
    </main>
</body>
</html>