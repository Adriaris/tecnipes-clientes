<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <!-- Logo a la izquierda -->
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('/images/logo.png') }}" alt="Logo" style="height: 40px;">
        </a>

        <!-- Título al centro -->
        <div class="titulo-container">
            <span class="titulo" id="titulo">{{ session('titulo', 'Clientes') }}</span>
        </div>

        <!-- Botón Hamburguesa a la derecha -->
        <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Elementos del Menú -->
        <div class="collapse navbar-collapse titulo2" id="navbarNavDropdown">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'lista-basculas' ? 'active' : '' }}"
                        href="{{ route('lista-basculas') }}">Básculas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Route::currentRouteName() == 'lista-clientes' ? 'active' : '' }}"
                        href="{{ route('lista-clientes') }}">Clientes</a>
                </li>

                @auth
                    @can('accessModeratorAndAdmin')
                        <!-- Versión de escritorio: Dropdown, visible solo en pantallas mayores a 991px -->
                        <li class="nav-item dropdown d-none d-lg-block" id="desktopMenu">
                            <a class="nav-link dropdown-toggle {{ Route::is('admin.clientes-papelera', 'admin.basculas-papelera') ? 'active' : '' }}"
                                href="#" id="papeleraDropdown" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Papelera
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="papeleraDropdown">
                                <li>
                                    <a class="dropdown-item {{ Route::currentRouteName() == 'admin.clientes-papelera' ? 'active' : '' }}"
                                        href="{{ route('admin.clientes-papelera') }}">Clientes Eliminados</a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ Route::currentRouteName() == 'admin.basculas-papelera' ? 'active' : '' }}"
                                        href="{{ route('admin.basculas-papelera') }}">Básculas Eliminadas</a>
                                </li>
                            </ul>
                        </li>

                        <!-- Versión móvil: Elementos de menú separados, visibles solo en pantallas de 991px o menos -->
                        <li class="nav-item d-lg-none" id="mobileMenuClients">
                            <a class="nav-link {{ Route::currentRouteName() == 'admin.clientes-papelera' ? 'active' : '' }}"
                                href="{{ route('admin.clientes-papelera') }}">Clientes Eliminados</a>
                        </li>
                        <li class="nav-item d-lg-none" id="mobileMenuScales">
                            <a class="nav-link {{ Route::currentRouteName() == 'admin.basculas-papelera' ? 'active' : '' }}"
                                href="{{ route('admin.basculas-papelera') }}">Básculas Eliminadas</a>
                        </li>
                    @endcan
                    @can('accessAdmin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.home') }}">Administración</a>
                        </li>
                    @endcan
                @endauth



                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile') }}" id="perfil">
                        <!-- Icono visible solo en pantallas grandes (lg en adelante) -->
                        <i class="ps-1 bi bi-person-circle large-icon d-none d-lg-inline-block nav-icon"></i>
                        <!-- Texto visible solo en pantallas menores a lg -->
                        <span class="d-inline-block d-lg-none">Perfil</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tituloElement = document.getElementById('titulo');

        function capitalizeWords(str) {
            return str.toLowerCase().replace(/(^|\s)\p{L}/gu, function(char) {
                return char.toUpperCase();
            });
        }

        function truncateTitle() {
            let tituloText = tituloElement.innerText;

            // Capitalizar cada palabra
            tituloText = capitalizeWords(tituloText);

            if (window.innerWidth <= 767 && tituloText.length > 23) {
                tituloText = tituloText.substring(0, 23) + '...';
            }

            tituloElement.innerText = tituloText;
        }

        // Truncar el título al cargar la página y al cambiar el tamaño de la ventana
        truncateTitle();
        window.addEventListener('resize', truncateTitle);
    });

    document.addEventListener("DOMContentLoaded", function() {
        function toggleMenu() {
            const width = window.innerWidth;
            const desktopMenu = document.getElementById('desktopMenu');
            const mobileMenuClients = document.getElementById('mobileMenuClients');
            const mobileMenuScales = document.getElementById('mobileMenuScales');

            if (width <= 991) {
                desktopMenu.classList.add('d-none');
                mobileMenuClients.classList.remove('d-none');
                mobileMenuScales.classList.remove('d-none');
            } else {
                desktopMenu.classList.remove('d-none');
                mobileMenuClients.classList.add('d-none');
                mobileMenuScales.classList.add('d-none');
            }
        }

        toggleMenu(); // Ejecutar al cargar la página
        window.addEventListener('resize',
            toggleMenu); // Ejecutar cada vez que se cambie el tamaño de la pantalla
    });
</script>
