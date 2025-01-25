<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign-in Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="flex w-full max-w-4xl bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="w-1/3 bg-blue-200 p-8 flex flex-col justify-center items-center">
            <h2 class="text-4xl font-bold text-white mb-4 text-left">
                Developing Your <br>
                <span class="text-orange-500">People</span>
            </h2>
            <div class="mt-8">
                <img alt="logo" class="mb-4" height="150" src="{{ asset('images/QQ.png') }}" width="200"/>
            </div>
        </div>
        <div class="w-2/3 p-16">
            <h2 class="text-2xl font-bold mb-6 text-center">Sign-in</h2>
            <form>
                <div class="mb-4">
                    <input class="shadow appearance-none border-b w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" type="email" placeholder="Email">
                </div>
                <div class="mb-6">
                    <input class="shadow appearance-none border-b w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" placeholder="Password">
                </div>
                <div class="flex items-center justify-center">
                    <a href="/companyprofile">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
                            Login
                        </button>
                    </a>
                </div>
                <p class="text-center text-gray-500 text-xs mt-4">
                    Don't have an account? <a class="text-blue-500 hover:text-blue-800" href="\registrasi">Signup Here</a>
                </p>
            </form>
        </div>
    </div>
</body>
</html>