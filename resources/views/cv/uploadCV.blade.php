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
  <main class="flex flex-col items-center mt-6">
    <div>
        <h1 class="text-2xl font-bold mb-6 text-center">
            Psikolog Klinis
        </h1>
        <h1 class=" text-lg font-normal mb-6 text-center ml-20 mr-20">
            Psikolog klinis yang bekerja di perusahaan konsultan memiliki peran yang unik karena mereka tidak hanya fokus pada terapi individu seperti di lingkungan klinis tradisional, tetapi juga memberikan layanan yang disesuaikan dengan kebutuhan organisasi. Berikut adalah jobdesk atau tanggung jawab umum seorang psikolog klinis dalam perusahaan konsultan:
        </h1>
    </div>
   <h1 class="text-2xl font-bold mb-6">
    Upload CV anda pada tombol yang ada di bawah ini
   </h1>
   <div class="flex flex-col items-center mb-6">
    <input type="file" name="cv_file" id="cv_file" class="hidden" accept=".pdf,.doc,.docx" />
    <label for="cv_file" class="bg-blue-400 text-white px-6 py-2 rounded shadow mb-2 cursor-pointer hover:bg-blue-500 transition-colors duration-200 flex items-center space-x-2">
        <i class="fas fa-upload mr-2"></i>
        <span>Upload CV</span>
    </label>
    <div id="file-name" class="text-sm text-gray-600 mt-2"></div>
    @error('cv_file')
        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
    @enderror
</div>
   <form class="w-full max-w-lg" method="POST" action="{{ route('cv.upload') }}" enctype="multipart/form-data">
    @csrf
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
                Gaji yang diinginkan
            </label>
            <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="linkedin-url" type="text"/>
        </div>
    </div>
    <div class="flex justify-center">
        <button type="submit" class="bg-green-300 text-white px-6 py-2 rounded hover:bg-green-400 transition-colors duration-200">
            Submit
        </button>
    </div>
   </form>
  </main>
  <script>
    document.getElementById('cv_file').addEventListener('change', function() {
        const fileName = this.files[0]?.name;
        const fileDisplay = document.getElementById('file-name');
        if (fileName) {
            fileDisplay.textContent = 'File terpilih: ' + fileName;
        } else {
            fileDisplay.textContent = '';
        }
    });
</script>
 </body>
</html>
