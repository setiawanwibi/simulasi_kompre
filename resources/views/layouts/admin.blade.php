<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title','Admin')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script>
        if (localStorage.getItem("sidebarState") === "closed") {
            document.documentElement.classList.add("sidebar-closed");
        }
    </script>

    <style>
        .sidebar-closed #sidebar {
            display: none !important;
        }

        .sidebar-closed .main-content {
            margin-left: 0 !important;
        }

        .sidebar-closed .topbar {
            left: 0 !important;
        }
            
        html, body {
            overflow-x: hidden;
            width: 100%;
        }
    </style>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite('resources/css/app.css')
</head>

<body class="bg-[#eef1f6] font-[Poppins] overflow-x-hidden">

<div class="flex">

        <aside id="sidebar"
            class="bg-[#233e8b] text-white flex flex-col
            w-[240px] max-w-[100vw]
            transition-all duration-300
            fixed inset-y-0 left-0 z-40">

        <div class="px-4 pt-3 pb-3 flex flex-col items-center gap-3 border-b border-white/10">

            <div class="w-full flex justify-end">
                <button onclick="toggleSidebar()"
                    class="w-9 h-9 flex items-center justify-center
                           bg-white/10 hover:bg-white/20
                           rounded-lg transition text-white">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>

            <div class="flex flex-col items-center gap-2">
                <img src="/pln.jpg" class="w-12 h-12 object-contain">
                <span class="font-semibold text-sm text-center">
                    SIPER BBM
                </span>
            </div>

        </div>

        <nav class="flex-1 px-3 py-4 space-y-2 text-sm">

            @php
            use App\Models\PermohonanBBM;
            function menuActive($route){
                return request()->routeIs($route)
                ? 'bg-white/20'
                : 'hover:bg-white/10';
            }

            $pendingPermohonan = PermohonanBBM::where('status','Pending')->count();
            @endphp

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-3 rounded-xl {{ menuActive('admin.dashboard') }}">
                <i class="fa fa-home w-5 text-center"></i>
                Dashboard
            </a>

            <a href="{{ route('admin.permohonan.index') }}"
            class="flex items-center justify-between px-3 py-3 rounded-xl transition-all
            {{ menuActive('admin.permohonan.*') }}">

                <div class="flex items-center gap-3">
                    <i class="fa fa-file w-5 text-center"></i>
                    <span>Permohonan BBM</span>
                </div>

            @if($pendingPermohonan > 0)
            <span class="bg-amber-100 text-amber-700
                        text-[10px] font-semibold
                        min-w-[18px] h-[18px]
                        px-1.5
                        rounded-full
                        flex items-center justify-center
                        border border-amber-200">
                {{ $pendingPermohonan }}
            </span>
            @endif
            </a>

            <a href="{{ route('admin.laporan-bbm.index') }}"
               class="flex items-center gap-3 px-3 py-3 rounded-xl {{ menuActive('admin.laporan-bbm.*') }}">
                <i class="fa fa-chart-bar w-5 text-center"></i>
                Laporan BBM
            </a>

            <a href="{{ route('admin.kendaraan.index') }}"
               class="flex items-center gap-3 px-3 py-3 rounded-xl {{ menuActive('admin.kendaraan.*') }}">
                <i class="fa fa-car w-5 text-center"></i>
                Kendaraan
            </a>

            @if(auth()->user()->role == 'super_admin')
            <a href="{{ route('admin.akun.index') }}"
            class="flex items-center gap-3 px-3 py-3 rounded-xl {{ menuActive('admin.akun.*') }}">
                <i class="fa fa-user w-5 text-center"></i>
                Akun
            </a>
            @endif

            <a href="{{ route('admin.jenis-bbm.index') }}"
            class="flex items-center gap-3 px-3 py-3 rounded-xl {{ menuActive('admin.jenis-bbm.*') }}">
                <i class="fa fa-gas-pump w-5 text-center"></i>
                Jenis BBM
            </a>

            <form action="{{ route('logout') }}" method="POST" class="pt-3">
                @csrf

                <button type="button"
                        onclick="openLogoutModal()"
                        class="w-full flex items-center justify-center gap-2
                            bg-red-500 hover:bg-red-600
                            py-3 rounded-xl text-sm transition">
                    <i class="fa fa-right-from-bracket"></i>
                    Logout
                </button>
            </form>

            </nav>

    </aside>


    <div class="flex-1 flex flex-col main-content md:ml-[240px] w-full">

        {{-- TOPBAR --}}
        <div class="bg-white px-6 py-4 flex justify-between items-center shadow-sm
                    fixed top-0 left-0 md:left-[240px] right-0 z-30 topbar">

            <div class="flex items-center gap-3">

                <button id="topbarToggle"
                    onclick="toggleSidebar()"
                    class="w-9 h-9 flex items-center justify-center
                           bg-white/10 hover:bg-white/20
                           text-[#233e8b]
                           rounded-lg transition hidden">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <p class="text-sm text-gray-500">
                    Selamat datang,
                    <span class="font-semibold text-gray-700">
                        {{ auth()->user()->name }}
                    </span>
                </p>

            </div>

            <div class="dropdown">
                <div class="cursor-pointer" data-bs-toggle="dropdown">
                    <div class="w-10 h-10 bg-[#233e8b] text-white
                                rounded-full flex items-center justify-center font-semibold">
                        {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                    </div>
                </div>

                <ul class="dropdown-menu dropdown-menu-end">

                    @if(auth()->user()->role == 'super_admin')
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.profile') }}">
                            Pengaturan Akun
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    @endif
                    <li>
                        <button type="button"
                            onclick="openLogoutModal()"
                            class="dropdown-item text-danger">
                            <i class="fa fa-right-from-bracket me-2"></i>
                            Logout
                        </button>
                    </li>
                </ul>
            </div>

        </div>

        <div class="p-6 flex-1 mt-[80px] overflow-y-auto min-h-[calc(100vh-80px)]">
            <div class="bg-white rounded-2xl shadow-sm p-6 min-h-[600px]">
                @yield('content')
            </div>
        </div>

    </div>

<div id="logoutModal"
     class="fixed inset-0 z-[9999] hidden">

    <!-- overlay -->
    <div class="fixed inset-0 bg-black/40"></div>

    <!-- center wrapper -->
    <div class="fixed inset-0 flex items-center justify-center px-4 overflow-hidden">

        <div class="bg-white rounded-2xl shadow-xl w-[92%] max-w-sm mx-auto p-6 text-center">

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
                        type="button"
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 rounded-lg text-sm">
                    Batal
                </button>

                <form action="{{ route('logout') }}" method="POST" class="flex-1">
                    @csrf
                    <button class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg text-sm">
                        Logout
                    </button>
                </form>

            </div>

        </div>

    </div>
</div>


<script>

    document.addEventListener("DOMContentLoaded", function(){

        const sidebar = document.getElementById('sidebar');
        const topbarToggle = document.getElementById('topbarToggle');

        if (localStorage.getItem("sidebarState") === "closed") {
            sidebar.classList.add("hidden");
            topbarToggle.classList.remove("hidden");
        } else {
            sidebar.classList.remove("hidden");
            topbarToggle.classList.add("hidden");
        }
    });

    function toggleSidebar(){

        const sidebar = document.getElementById('sidebar');
        const topbarToggle = document.getElementById('topbarToggle');

        if (sidebar.classList.contains('hidden')) {

            sidebar.classList.remove('hidden');
            document.documentElement.classList.remove("sidebar-closed");
            topbarToggle.classList.add('hidden');
            localStorage.setItem("sidebarState", "open");

        } else {

            sidebar.classList.add('hidden');
            document.documentElement.classList.add("sidebar-closed");
            topbarToggle.classList.remove('hidden');
            localStorage.setItem("sidebarState", "closed");
        }
    }

    function openLogoutModal(){
        const modal = document.getElementById('logoutModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeLogoutModal(){
        const modal = document.getElementById('logoutModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }

    window.addEventListener('click', function(event){

        const modal = document.getElementById('logoutModal');

        if(event.target === modal){
            closeLogoutModal();
        }
    });

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>