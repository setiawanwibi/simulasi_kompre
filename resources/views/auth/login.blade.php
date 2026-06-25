<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SIPER BBM</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-[#e3e8f1] min-h-screen flex flex-col font-[Poppins]">

<div class="flex-1 flex items-center justify-center px-4">

    <div class="bg-white w-full max-w-sm rounded-2xl shadow-xl p-6 sm:p-8 text-center">

        <img src="/logo_pln.jpg" class="w-14 sm:w-16 mx-auto mb-2">

        <h2 class="text-[#1f3c88] font-semibold text-lg sm:text-xl mt-2">
            SIPER BBM
        </h2>

        <p class="text-xs text-gray-500 mb-6">
            PLN UP3 TANJUNGKARANG
        </p>

        @if ($errors->any())
            <p class="text-red-500 text-sm mb-3">
                {{ $errors->first() }}
            </p>
        @endif

        <form method="POST" action="/login" class="space-y-4 text-left">
        @csrf

        <div>
            <label class="text-sm text-gray-600">Email</label>

            <div class="relative mt-1">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
                    fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path d="M4 6h16v12H4z"/>
                    <path d="M22 6l-10 7L2 6"/>
                </svg>

                <input
                    type="email"
                    name="email"
                    placeholder="nama@example.com"
                    class="w-full bg-[#eef0f3] rounded-xl pl-10 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#243c8f]"
                    required
                >
            </div>
        </div>

        <div>
            <label class="text-sm text-gray-600">Password</label>

            <div class="relative mt-1">
                <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
                    fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <rect x="3" y="11" width="18" height="11" rx="2"/>
                    <path d="M7 11V7a5 5 0 0110 0v4"/>
                </svg>

                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="********"
                    class="w-full bg-[#eef0f3] rounded-xl pl-10 pr-10 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#243c8f]"
                    required>

                <svg onclick="togglePass()"
                    class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer"
                    fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/>
                    <circle cx="12" cy="12" r="3"/>
                </svg>
            </div>
        </div>

        <button
            class="w-full bg-[#243c8f] hover:bg-[#1c3378] transition text-white py-2.5 rounded-xl mt-3 font-semibold">
            Masuk
        </button>

        </form>
        <div class="text-right mt-2">
            <a href="{{ route('password.request') }}"
                class="text-xs text-blue-600 hover:underline">
                Lupa Password?
            </a>
        </div>
    </div>
</div>

<div class="h-14 sm:h-16 bg-[#243c8f]"></div>

<script>
function togglePass(){
    let x = document.getElementById("password");
    x.type = x.type === "password" ? "text" : "password";
}
</script>

</body>
</html>