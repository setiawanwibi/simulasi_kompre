<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password - SIPER BBM</title>
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
Buat Password Baru
</p>

@if ($errors->any())
<p class="text-red-500 text-sm mb-3">
{{ $errors->first() }}
</p>
@endif

<form method="POST" action="{{ route('password.update') }}" class="space-y-4 text-left">
@csrf

<input type="hidden" name="token" value="{{ $token }}">

<div>
<label class="text-sm text-gray-600">Email</label>

<input
type="email"
name="email"
value="{{ $email }}"
class="w-full bg-[#eef0f3] rounded-xl px-3 py-2.5 text-sm mt-1 focus:outline-none focus:ring-2 focus:ring-[#243c8f]"
required
>
</div>

<div>
<label class="text-sm text-gray-600">Password Baru</label>

<input
type="password"
name="password"
placeholder="Masukkan password baru"
class="w-full bg-[#eef0f3] rounded-xl px-3 py-2.5 text-sm mt-1 focus:outline-none focus:ring-2 focus:ring-[#243c8f]"
required
>
</div>

<div>
<label class="text-sm text-gray-600">Konfirmasi Password</label>

<input
type="password"
name="password_confirmation"
placeholder="Ulangi password"
class="w-full bg-[#eef0f3] rounded-xl px-3 py-2.5 text-sm mt-1 focus:outline-none focus:ring-2 focus:ring-[#243c8f]"
required
>
</div>

<button
class="w-full bg-[#243c8f] hover:bg-[#1c3378] transition text-white py-2.5 rounded-xl font-semibold">
Reset Password
</button>

</form>

</div>

</div>

<div class="h-14 sm:h-16 bg-[#243c8f]"></div>

</body>
</html>