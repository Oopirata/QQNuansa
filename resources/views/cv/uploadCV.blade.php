<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>
   Upload CV
  </title>
  <script src="https://cdn.tailwindcss.com">
  </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
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
  <main class="flex flex-col items-center mt-10">
   <h1 class="text-2xl font-bold mb-6">
    Upload CV anda pada tombol yang ada di bawah ini
   </h1>
   <button class="bg-blue-400 text-white px-6 py-2 rounded shadow mb-6">
    Upload CV
   </button>
   <form class="w-full max-w-lg">
    <div class="flex flex-wrap -mx-3 mb-6">
     <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="nama-lengkap">
       Nama Lengkap
      </label>
      <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="nama-lengkap" type="text"/>
     </div>
     <div class="w-full md:w-1/2 px-3">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="email">
       Email
      </label>
      <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="email" type="email"/>
     </div>
    </div>
    <div class="flex flex-wrap -mx-3 mb-6">
     <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="lokasi">
       Lokasi
      </label>
      <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="lokasi" type="text"/>
     </div>
     <div class="w-full md:w-1/2 px-3">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="nomer-telepon">
       Nomer Telepon
      </label>
      <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="nomer-telepon" type="text"/>
     </div>
    </div>
    <div class="flex flex-wrap -mx-3 mb-6">
     <div class="w-full px-3">
      <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="linkedin-url">
       LinkedIn URL
      </label>
      <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="linkedin-url" type="text"/>
     </div>
    </div>
    <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full px-3">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="linkedin-url">
                Posisi yang diinginkan
            </label>
            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="linkedin-url" type="text"/>
        </div>
    </div>
    <div class="flex flex-wrap -mx-3 mb-6">
        <div class="w-full px-3">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="linkedin-url">
                Gaji yang diinginkan
            </label>
            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="linkedin-url" type="text"/>
        </div>
    </div>
    <div class="flex justify-center">
        <a href="{{ url('/statuscv') }}" class="bg-green-300 text-white px-6 py-2 rounded inline-block">
            Submit
        </a>
    </div>
   </form>
  </main>
 </body>
</html>