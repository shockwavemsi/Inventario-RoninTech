<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $config->nombre_empresa ?? 'Sistema de Inventario' }} - @yield('title', 'Panel')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- CSS Cyberpunk Theme -->
    <link rel="stylesheet" href="{{ secure_asset('css/menu.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/compras.css') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('extra-css')
</head>

<body>
    <!-- BOTÓN MENU HAMBURGUESA -->
    <button id="menu-toggle" class="menu-toggle" aria-label="Abrir menú">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <!-- OVERLAY -->
    <div id="sidebar-overlay" class="sidebar-overlay"></div>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h3>{{ $config->nombre_empresa ?? 'Mi Empresa' }}</h3>
        <div id="menu-contenedor"></div>
        <a href="{{ route('logout') }}" class="mt-4">
            <i class="bi bi-box-arrow-right"></i> Cerrar sesión
        </a>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="content">
        @yield('content')
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ✅ CARGA DE MENÚ SEGÚN ROL -->
@php
    $user = auth()->user();
    $roleName = $user->role->name ?? 'user';
    $menuScript = $roleName === 'admin' ? 'js/menu.js' : 'js/userMenu.js';
@endphp
<script src="{{ secure_asset($menuScript) }}"></script>

    <!-- ✅ CARGAR COMPRAS SOLO EN /compras -->
    @if(str_contains(request()->path(), 'compras'))
        <!-- ✅ MODAL MANAGER (SIN MÓDULOS - DEBE IR PRIMERO) -->
        <script src="{{ secure_asset('js/compras/modales/modal-manager.js') }}"></script>

        <!-- ✅ COMPRAS (MÓDULO) -->
        <script type="module" src="{{ secure_asset('js/compras/compras.js') }}"></script>
    @endif

    <!-- ✅ CARGAR VENTAS SOLO EN /ventas -->
    @if(str_contains(request()->path(), 'ventas'))
        <script type="module" src="{{ secure_asset('js/ventas/venta.js') }}"></script>
    @endif

    @yield('extra-js')

</body>
</html>