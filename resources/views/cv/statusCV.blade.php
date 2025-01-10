<html>
 <head>
  <script src="https://cdn.tailwindcss.com">
  </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
 </head>
 <body class="bg-gray-100">
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
  <main class="p-8">
   <div class="bg-white p-6 rounded shadow-md">
    <h1 class="text-xl font-bold mb-4">
     CV berhasil di-Upload! silahkan tunggu approval HRD untuk lanjut ke tahap Interview
    </h1>
    <div class="mb-4">
     <span class="font-bold">
      Status
     </span>
     :
     <span class="bg-yellow-200 text-gray-700 px-4 py-2 rounded">
      Menunggu Verifikasi
     </span>
    </div>
    <div class="mb-4">
        <div class="grid grid-cols-[200px_1fr] gap-2">
          <span class="font-bold">Nama</span>
          <span>: Surya Fajar</span>
          
          <span class="font-bold">Email</span>
          <span>: suryafajar04@gmail.com</span>
          
          <span class="font-bold">Nomer Telepon</span>
          <span>: 08767512341</span>
          
          <span class="font-bold">Location</span>
          <span>: Semarang</span>
          
          <span class="font-bold">LinkedIn URL</span>
          <span>: -</span>

          <span class="font-bold">Posisi yang diinginkan</span>
          <span>: -</span>

          <span class="font-bold">Gaji yang diinginkan</span>
          <span>: -</span>
        </div>
       </div>
    <p class="text-gray-700">
     Cek Email untuk update info status yang lebih cepat dan tepat, Good luck!!
    </p>
   </div>
  </main>
 </body>
</html>
