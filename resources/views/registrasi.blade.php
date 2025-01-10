<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>
   Create an Account
  </title>
  <script src="https://cdn.tailwindcss.com">
  </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&amp;display=swap" rel="stylesheet"/>
  <style>
   body {
            font-family: 'Roboto', sans-serif;
        }
  </style>
 </head>
 <body class="flex items-center justify-center min-h-screen bg-gray-100">
  <div class="flex w-full max-w-4xl bg-white shadow-lg rounded-lg overflow-hidden">
   <div class="w-1/2 bg-blue-200 p-8 flex flex-col justify-center items-center">
    <h2 class="text-4xl font-bold text-white mb-4">
     Developing Your
     <span class="text-orange-400">
      People
     </span>
    </h2>
    <img alt="logo" class="mb-4" height="150" src="{{ asset('images/QQ.png') }}" width="200"/>
   </div>
   <div class="w-1/2 p-8">
    <h2 class="text-2xl font-bold mb-6">
     Create an Account
    </h2>
    <form>
     <div class="mb-4">
      <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Full Name" type="text"/>
     </div>
     <div class="mb-4">
      <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Email" type="email"/>
     </div>
     <div class="mb-4">
      <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Password" type="password"/>
     </div>
     <div class="mb-4">
      <button class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600" type="submit">
       Create Account
      </button>
     </div>
     <p class="text-center text-gray-600">
      Already have an account?
      <a class="text-blue-500" href="/">
       Login
      </a>
     </p>
    </form>
   </div>
  </div>
 </body>
</html>
