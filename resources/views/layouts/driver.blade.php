<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>@yield('title','Driver')</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

@vite('resources/css/app.css')

<style>

.nav-item{
display:flex;
flex-direction:column;
align-items:center;
font-size:11px;
color:#9ca3af;
transition:all .3s ease;
position:relative;
}

.nav-item span{
margin-top:4px;
}

.icon-wrapper{
width:48px;
height:48px;
display:flex;
align-items:center;
justify-content:center;
border-radius:9999px;
transition:all .3s ease;
}

.nav-item.active{
color:#233e8b;
}

.nav-item.active .icon-wrapper{
background:#233e8b;
color:white;
transform:translateY(-18px);
box-shadow:0 10px 25px rgba(0,0,0,0.15);
}

.nav-item.active span{
margin-top:-6px;
font-weight:500;
}

</style>

</head>


<body class="bg-[#eef1f6] font-[Poppins] flex flex-col min-h-screen">

@php
function menuActiveDriver($route){
return request()->routeIs($route)
? 'text-[#233e8b] font-semibold'
: 'text-gray-500 hover:text-[#233e8b]';
}
@endphp



<div class="hidden md:flex bg-white px-8 py-4 shadow-sm justify-between items-center">

<div class="flex items-center gap-2 font-semibold text-[#233e8b]">
<img src="/pln.jpg" class="w-9 h-9 object-contain">
SIPER BBM
</div>

<div class="flex items-center gap-8 text-sm">

<a href="{{ route('driver.permohonan.index') }}"
class="flex items-center gap-2 {{ menuActiveDriver('driver.permohonan.*') }}">
<i class="fa fa-file"></i>
Permohonan
</a>

<a href="{{ route('driver.laporan.index') }}"
class="flex items-center gap-2 {{ menuActiveDriver('driver.laporan.*') }}">
<i class="fa fa-gas-pump"></i>
Laporan BBM
</a>

<span class="text-gray-600">
{{ auth()->user()->name }}
</span>

<button onclick="openLogoutModal()"
class="text-red-500 hover:text-red-600">
Logout
</button>

</div>

</div>


<div class="md:hidden bg-white px-4 py-3 shadow-sm flex justify-between items-center">

<div class="flex items-center gap-2 font-semibold text-[#233e8b]">
<img src="/pln.jpg" class="w-8 h-8 object-contain">
SIPER BBM
</div>

<div class="text-sm text-gray-600">
{{ auth()->user()->name }}
</div>

</div>


<div class="flex-1 p-6 pb-32">

<div class="bg-white rounded-2xl shadow-sm p-6 min-h-[600px]">
@yield('content')
</div>

</div>


<div class="md:hidden fixed bottom-0 left-0 w-full flex justify-center pb-4">

<div class="relative bg-white w-[92%] h-20 rounded-t-3xl shadow-xl flex justify-around items-center">

<a href="{{ route('driver.permohonan.index') }}"
class="nav-item {{ request()->routeIs('driver.permohonan.*') ? 'active' : '' }}">

<div class="icon-wrapper">
<i class="fa fa-file text-xl"></i>
</div>

<span>Permohonan</span>

</a>


<a href="{{ route('driver.laporan.index') }}"
class="nav-item {{ request()->routeIs('driver.laporan.*') ? 'active' : '' }}">

<div class="icon-wrapper">
<i class="fa fa-gas-pump text-xl"></i>
</div>

<span>Laporan</span>

</a>

<button onclick="openLogoutModal()" class="nav-item text-red-500">

<div class="icon-wrapper">
<i class="fa fa-right-from-bracket text-xl"></i>
</div>

<span>Logout</span>

</button>

</div>

</div>



<div id="logoutModal"
class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

<div class="bg-white rounded-2xl shadow-xl w-[90%] max-w-sm p-6 text-center">

<div class="text-3xl text-red-500 mb-3">
<i class="fa fa-right-from-bracket"></i>
</div>

<h3 class="text-lg font-semibold text-gray-700 mb-2">
Konfirmasi Logout
</h3>

<p class="text-sm text-gray-500 mb-6">
Apakah Anda yakin ingin keluar dari sistem?
</p>

<div class="flex gap-3">

<button onclick="closeLogoutModal()"
class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 rounded-lg text-sm">
Batal
</button>

<form action="{{ route('logout') }}" method="POST" class="flex-1">
@csrf
<button
class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg text-sm">
Logout
</button>
</form>

</div>

</div>

</div>



<script>

function openLogoutModal(){
const modal=document.getElementById('logoutModal');
modal.classList.remove('hidden');
modal.classList.add('flex');
}

function closeLogoutModal(){
const modal=document.getElementById('logoutModal');
modal.classList.remove('flex');
modal.classList.add('hidden');
}

window.onclick=function(event){
const modal=document.getElementById('logoutModal');
if(event.target===modal){
closeLogoutModal();
}
}

</script>


</body>
</html>