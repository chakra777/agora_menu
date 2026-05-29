<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú — Cafetería AGORA</title>
    <link rel="stylesheet" href="dist/output.css">
</head>
<body class="bg-agora-50 text-agora-900 min-h-screen">
    <div class="flex min-h-screen">
        <aside id="sidebar" class="w-64 bg-agora-800 text-white flex flex-col shrink-0 transition-all duration-300">
            <div class="p-4 flex justify-between items-center border-b border-agora-700">
                <div class="flex items-center min-w-0">
                    <div class="h-10 w-10 rounded-full bg-agora-500 flex items-center justify-center shrink-0 font-bold text-lg" aria-hidden="true">A</div>
                    <div class="ml-3 sidebar-text min-w-0">
                        <span class="text-xl font-semibold tracking-wide block truncate">AGORA</span>
                        <span class="text-xs text-agora-300 block">Cafetería</span>
                    </div>
                </div>
                <button id="collapse-btn" type="button" class="p-1 rounded-md hover:bg-agora-700 focus:outline-none shrink-0" aria-label="Colapsar menú">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                </button>
            </div>
            <nav id="nav-categorias" class="mt-4 px-2 flex-1 overflow-y-auto space-y-1" aria-label="Categorías del menú">
                <p class="px-2 py-2 text-xs uppercase tracking-wider text-agora-400 sidebar-text">Cargando…</p>
            </nav>
            <div class="p-4 border-t border-agora-700 flex flex-col space-y-2 shrink-0">
                <button id="admin-btn" type="button" class="w-full text-left flex items-center px-3 py-2 text-sm font-medium rounded-md text-agora-300 hover:bg-agora-700 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <span class="sidebar-text">Admin</span>
                </button>
                <p class="text-[10px] text-agora-400 sidebar-text text-center mt-1">Solo productos disponibles</p>
            </div>
        </aside>

        <main class="flex-1 flex flex-col min-w-0">
            <!-- 1. VISTA MENÚ PÚBLICO -->
            <div id="vista-menu" class="flex-1 flex flex-col min-w-0">
                <header class="bg-white border-b border-agora-200 px-6 py-5 shadow-sm">
                    <h1 id="titulo-categoria" class="text-2xl font-semibold text-agora-800">Menú</h1>
                    <p class="text-sm text-agora-500 mt-1">Cafetería AGORA</p>
                </header>
                <div class="flex-1 p-6 overflow-y-auto">
                    <div id="estado-carga" class="text-center py-16 text-agora-500">
                        Cargando menú…
                    </div>
                    <div id="estado-error" class="hidden text-center py-16">
                        <p class="text-red-600 font-medium">No se pudo cargar el menú.</p>
                        <p class="text-sm text-agora-500 mt-2">Verifica que el servidor y la base de datos estén activos.</p>
                    </div>
                    <div id="lista-productos" class="hidden grid gap-4 sm:grid-cols-2 lg:grid-cols-3"></div>
                    <p id="sin-productos" class="hidden text-center py-16 text-agora-500">No hay productos disponibles en esta categoría.</p>
                </div>
            </div>

            <!-- 2. VISTA LOGIN -->
            <div id="vista-login" class="hidden flex-1 flex items-center justify-center p-6 bg-agora-50">
                <div class="w-full max-w-md bg-white rounded-xl shadow-lg border border-agora-200 p-8 space-y-6">
                    <div class="text-center">
                        <div class="h-12 w-12 rounded-full bg-agora-600 text-white flex items-center justify-center mx-auto mb-3 text-xl font-bold shadow-md">A</div>
                        <h2 class="text-2xl font-bold text-agora-900">Iniciar Sesión</h2>
                        <p class="text-sm text-agora-500 mt-1">Acceso al Panel de Administración</p>
                    </div>
                    <form id="login-form" class="space-y-4" onsubmit="handleLogin(event)">
                        <div id="login-error" class="hidden p-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded-md"></div>
                        <div>
                            <label for="login-usuario" class="block text-sm font-medium text-agora-700 mb-1">Usuario</label>
                            <input type="text" id="login-usuario" required class="w-full px-3.5 py-2 border border-agora-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-agora-500 focus:border-agora-500 transition-all text-sm" />
                        </div>
                        <div>
                            <label for="login-contrasena" class="block text-sm font-medium text-agora-700 mb-1">Contraseña</label>
                            <input type="password" id="login-contrasena" required class="w-full px-3.5 py-2 border border-agora-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-agora-500 focus:border-agora-500 transition-all text-sm" />
                        </div>
                        <button type="submit" class="w-full bg-agora-600 hover:bg-agora-700 text-white font-medium py-2 rounded-md shadow-md hover:shadow-lg transition-all focus:outline-none focus:ring-2 focus:ring-agora-500">
                            Ingresar
                        </button>
                    </form>
                </div>
            </div>

            <!-- 3. VISTA ADMIN DASHBOARD -->
            <div id="vista-admin" class="hidden flex-1 flex flex-col min-w-0 bg-agora-50 overflow-hidden">
                <header class="bg-white border-b border-agora-200 px-6 py-4 shadow-sm flex flex-wrap justify-between items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-agora-900">Panel de Administración</h1>
                        <p id="admin-user-info" class="text-xs text-agora-500 mt-0.5">Sesión iniciada como: Admin</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button onclick="abrirModalProducto('add')" type="button" class="bg-agora-600 hover:bg-agora-700 text-white text-sm font-medium px-4 py-2 rounded-md shadow-md transition-all flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Agregar Producto
                        </button>
                        <button onclick="handleLogout()" type="button" class="border border-red-300 hover:bg-red-50 text-red-600 text-sm font-medium px-4 py-2 rounded-md transition-all">
                            Cerrar Sesión
                        </button>
                    </div>
                </header>
                
                <div class="flex-1 p-6 overflow-y-auto">
                    <div id="admin-error-loading" class="hidden text-center py-16">
                        <p class="text-red-600 font-medium">No se pudieron cargar los productos.</p>
                        <button onclick="cargarProductosAdmin()" class="mt-4 bg-agora-600 text-white text-sm px-4 py-2 rounded-md">Reintentar</button>
                    </div>
                    <div id="admin-tabla-wrapper" class="bg-white rounded-lg border border-agora-200 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-agora-200 text-left text-sm">
                                <thead class="bg-agora-50 text-agora-700 uppercase font-semibold text-xs tracking-wider">
                                    <tr>
                                        <th class="px-6 py-3">ID</th>
                                        <th class="px-6 py-3">Imagen</th>
                                        <th class="px-6 py-3">Descripción</th>
                                        <th class="px-6 py-3">Categoría</th>
                                        <th class="px-6 py-3">Costo</th>
                                        <th class="px-6 py-3 text-center">Stock</th>
                                        <th class="px-6 py-3 text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="admin-tbody" class="divide-y divide-agora-100 text-agora-900">
                                    <!-- Rendered dynamically -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL PRODUCTO -->
    <div id="modal-producto" class="hidden fixed inset-0 z-50 overflow-y-auto flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl shadow-xl border border-agora-200 w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden animate-scale-up">
            <header class="bg-agora-50 border-b border-agora-200 px-6 py-4 flex justify-between items-center shrink-0">
                <h3 id="modal-titulo" class="text-lg font-semibold text-agora-900">Editar Producto</h3>
                <button onclick="cerrarModalProducto()" type="button" class="text-agora-400 hover:text-agora-600 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </header>

            <form id="form-producto" onsubmit="guardarProducto(event)" class="flex-1 flex flex-col md:flex-row overflow-hidden min-h-0">
                <!-- Form Inputs Panel -->
                <div class="flex-1 p-6 overflow-y-auto space-y-4 border-b md:border-b-0 md:border-r border-agora-200">
                    <div id="form-error" class="hidden p-3 text-sm text-red-700 bg-red-50 border border-red-200 rounded-md"></div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="prod-id" class="block text-sm font-medium text-agora-700 mb-1">ID Producto</label>
                            <input type="number" id="prod-id" required class="w-full px-3 py-2 border border-agora-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-agora-500 text-sm" />
                        </div>
                        <div>
                            <label for="prod-costo" class="block text-sm font-medium text-agora-700 mb-1">Costo (Precio)</label>
                            <input type="number" step="0.01" id="prod-costo" required class="w-full px-3 py-2 border border-agora-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-agora-500 text-sm" />
                        </div>
                    </div>

                    <div>
                        <label for="prod-descrip" class="block text-sm font-medium text-agora-700 mb-1">Descripción / Nombre</label>
                        <input type="text" id="prod-descrip" required class="w-full px-3 py-2 border border-agora-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-agora-500 text-sm" oninput="sugerirBusquedaImagen()" />
                    </div>

                    <div class="grid grid-cols-2 gap-4 items-end">
                        <div>
                            <label for="prod-catego" class="block text-sm font-medium text-agora-700 mb-1">Categoría</label>
                            <select id="prod-catego" required class="w-full px-3 py-2 border border-agora-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-agora-500 text-sm bg-white">
                                <!-- Loaded dynamically -->
                            </select>
                        </div>
                        <div class="flex items-center h-10">
                            <label class="inline-flex items-center cursor-pointer select-none">
                                <input type="checkbox" id="prod-stock" class="sr-only peer" checked />
                                <div class="relative w-11 h-6 bg-agora-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-agora-500 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-agora-600"></div>
                                <span class="ml-3 text-sm font-medium text-agora-900">En Stock (Disponible)</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label for="prod-imagen" class="block text-sm font-medium text-agora-700 mb-1">URL de la Imagen</label>
                        <div class="flex gap-2">
                            <input type="text" id="prod-imagen" class="w-full px-3 py-2 border border-agora-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-agora-500 text-sm" placeholder="Ruta local o URL web..." oninput="actualizarPreviewImagen()" />
                            <button onclick="buscarImagenWeb()" type="button" class="shrink-0 bg-agora-600 hover:bg-agora-700 text-white text-xs font-semibold px-4 rounded-md shadow-sm transition-all">
                                Buscar Web
                            </button>
                        </div>
                    </div>

                    <div>
                        <span class="block text-sm font-medium text-agora-700 mb-2">Vista Previa de Imagen</span>
                        <div class="w-full h-40 bg-agora-100 rounded-lg overflow-hidden border border-agora-200 flex items-center justify-center relative">
                            <img id="form-img-preview" src="" alt="Previsualización" class="w-full h-full object-cover hidden" />
                            <span id="form-img-placeholder" class="text-xs text-agora-400">Sin imagen o ruta inválida</span>
                        </div>
                    </div>
                </div>

                <!-- Web Image Search Panel -->
                <div class="w-full md:w-[380px] p-6 flex flex-col overflow-hidden min-h-0 bg-agora-50">
                    <h4 class="text-sm font-bold text-agora-900 mb-3 flex items-center gap-2">
                        <svg class="h-4 w-4 text-agora-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Buscador de Imágenes Web
                    </h4>
                    <div class="flex gap-2 mb-4 shrink-0">
                        <input type="text" id="search-img-term" class="w-full px-3 py-1.5 border border-agora-300 rounded-md text-xs focus:outline-none bg-white" placeholder="Ej. chapata de jamon..." />
                        <button onclick="buscarImagenWebManual()" type="button" class="shrink-0 bg-agora-600 text-white text-xs px-3 rounded-md">
                            Buscar
                        </button>
                    </div>
                    <div id="search-img-loading" class="hidden text-center py-6 text-xs text-agora-500 shrink-0">
                        Buscando imágenes...
                    </div>
                    <div id="search-img-results" class="flex-1 overflow-y-auto grid grid-cols-2 gap-2 pr-1 min-h-0 align-content-start">
                        <!-- Loaded dynamically -->
                        <p class="col-span-2 text-center text-xs text-agora-400 py-8">Ingresa un término y busca imágenes web</p>
                    </div>
                </div>
            </form>

            <footer class="bg-agora-50 border-t border-agora-200 px-6 py-4 flex justify-end gap-3 shrink-0">
                <button onclick="cerrarModalProducto()" type="button" class="border border-agora-300 bg-white hover:bg-agora-50 text-agora-700 text-sm font-medium px-4 py-2 rounded-md transition-all">
                    Cancelar
                </button>
                <button form="form-producto" type="submit" class="bg-agora-600 hover:bg-agora-700 text-white text-sm font-medium px-5 py-2 rounded-md shadow-md transition-all">
                    Guardar Producto
                </button>
            </footer>
        </div>
    </div>

    <script>
        const formatoPrecio = new Intl.NumberFormat('es-MX', {
            style: 'currency',
            currency: 'MXN',
        });

        let menuData = { categorias: [] };
        let categoriaActiva = null;
        let adminProductos = [];
        let modalMode = 'add'; // 'add' or 'edit'

        const navCategorias = document.getElementById('nav-categorias');
        const tituloCategoria = document.getElementById('titulo-categoria');
        const listaProductos = document.getElementById('lista-productos');
        const estadoCarga = document.getElementById('estado-carga');
        const estadoError = document.getElementById('estado-error');
        const sinProductos = document.getElementById('sin-productos');
        let placeholderDefault = 'assets/placeholders/default.svg';

        // Vistas
        const vistaMenu = document.getElementById('vista-menu');
        const vistaLogin = document.getElementById('vista-login');
        const vistaAdmin = document.getElementById('vista-admin');
        const adminBtn = document.getElementById('admin-btn');
        const adminUserInfo = document.getElementById('admin-user-info');
        const loginError = document.getElementById('login-error');

        // Modal
        const modalProducto = document.getElementById('modal-producto');
        const modalTitulo = document.getElementById('modal-titulo');
        const formProducto = document.getElementById('form-producto');
        const formError = document.getElementById('form-error');
        const prodId = document.getElementById('prod-id');
        const prodCosto = document.getElementById('prod-costo');
        const prodDescrip = document.getElementById('prod-descrip');
        const prodCatego = document.getElementById('prod-catego');
        const prodStock = document.getElementById('prod-stock');
        const prodImagen = document.getElementById('prod-imagen');
        const formImgPreview = document.getElementById('form-img-preview');
        const formImgPlaceholder = document.getElementById('form-img-placeholder');
        const searchImgTerm = document.getElementById('search-img-term');
        const searchImgResults = document.getElementById('search-img-results');
        const searchImgLoading = document.getElementById('search-img-loading');

        function mostrarVista(vista) {
            vistaMenu.classList.add('hidden');
            vistaLogin.classList.add('hidden');
            vistaAdmin.classList.add('hidden');

            if (vista === 'menu') {
                vistaMenu.classList.remove('hidden');
            } else if (vista === 'login') {
                vistaLogin.classList.remove('hidden');
                loginError.classList.add('hidden');
                loginError.textContent = '';
                document.getElementById('login-usuario').value = '';
                document.getElementById('login-contrasena').value = '';
            } else if (vista === 'admin') {
                vistaAdmin.classList.remove('hidden');
            }
        }

        adminBtn.addEventListener('click', async () => {
            try {
                const res = await fetch('api/login.php');
                const check = await res.json();
                if (check.logged_in) {
                    adminUserInfo.textContent = `Sesión iniciada como: ${check.user} (${check.role})`;
                    mostrarVista('admin');
                    cargarProductosAdmin();
                } else {
                    mostrarVista('login');
                }
            } catch (e) {
                mostrarVista('login');
            }
        });

        // Autenticación
        async function handleLogin(e) {
            e.preventDefault();
            const usuario = document.getElementById('login-usuario').value;
            const contrasena = document.getElementById('login-contrasena').value;
            loginError.classList.add('hidden');

            try {
                const res = await fetch('api/login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ usuario, contraseña: contrasena })
                });
                const data = await res.json();
                if (data.success) {
                    adminUserInfo.textContent = `Sesión iniciada como: ${data.user} (${data.role})`;
                    mostrarVista('admin');
                    cargarProductosAdmin();
                } else {
                    loginError.textContent = data.error || 'Usuario o contraseña incorrectos.';
                    loginError.classList.remove('hidden');
                }
            } catch (err) {
                loginError.textContent = 'Error de red al intentar iniciar sesión.';
                loginError.classList.remove('hidden');
            }
        }

        async function handleLogout() {
            try {
                await fetch('api/logout.php');
            } catch (e) {}
            mostrarVista('menu');
            cargarMenu();
        }

        // Cargar productos en panel admin
        async function cargarProductosAdmin() {
            const tbody = document.getElementById('admin-tbody');
            const errorDiv = document.getElementById('admin-error-loading');
            const wrapper = document.getElementById('admin-tabla-wrapper');
            
            tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-agora-500">Cargando productos del catálogo...</td></tr>';
            errorDiv.classList.add('hidden');
            wrapper.classList.remove('hidden');

            try {
                const res = await fetch('api/productos_admin.php');
                if (!res.ok) throw new Error('Error al obtener productos');
                adminProductos = await res.json();
                
                tbody.innerHTML = '';
                if (adminProductos.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-agora-500">No hay productos registrados.</td></tr>';
                    return;
                }

                adminProductos.forEach(prod => {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-agora-50 transition-colors border-b border-agora-100';
                    
                    const isStock = Number(prod.Stock) === 1;
                    const badgeClass = isStock ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
                    const badgeText = isStock ? 'En Stock' : 'Agotado';
                    const imgUrl = prod.imagen_resuelta || placeholderDefault;

                    tr.innerHTML = `
                        <td class="px-6 py-4 font-mono text-xs text-agora-600 font-semibold">${prod.ID_PROD}</td>
                        <td class="px-6 py-3">
                            <div class="h-10 w-10 rounded border border-agora-200 overflow-hidden bg-agora-50 shrink-0">
                                <img src="${escapeHtml(imgUrl)}" alt="${escapeHtml(prod.DESCRIP)}" class="h-full w-full object-cover" />
                            </div>
                        </td>
                        <td class="px-6 py-4 font-medium text-agora-900">${escapeHtml(prod.DESCRIP)}</td>
                        <td class="px-6 py-4 text-agora-600 text-sm">${escapeHtml(prod.CATEGO)}</td>
                        <td class="px-6 py-4 font-semibold text-agora-900">${formatoPrecio.format(Number(prod.COSTO))}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${badgeClass}">
                                ${badgeText}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="abrirModalProducto('edit', ${prod.ID_PROD})" class="text-agora-600 hover:text-agora-900 font-medium text-sm transition-colors mr-2">Editar</button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            } catch (err) {
                wrapper.classList.add('hidden');
                errorDiv.classList.remove('hidden');
            }
        }

        // Modal de edición/inserción
        async function abrirModalProducto(mode, id = null) {
            modalMode = mode;
            formError.classList.add('hidden');
            formError.textContent = '';
            formProducto.reset();

            // Populate categories dropdown
            try {
                const res = await fetch('api/categorias.php');
                const categorias = await res.json();
                prodCatego.innerHTML = '';
                categorias.forEach(cat => {
                    const opt = document.createElement('option');
                    opt.value = cat.categoria;
                    opt.textContent = cat.categoria;
                    prodCatego.appendChild(opt);
                });
            } catch (e) {
                prodCatego.innerHTML = '<option value="">Error al cargar categorías</option>';
            }

            if (mode === 'add') {
                modalTitulo.textContent = 'Agregar Producto';
                prodId.disabled = false;
                prodId.readOnly = false;
                prodId.classList.remove('bg-gray-100', 'text-gray-500');
                
                // Sugerir siguiente ID disponible
                let maxId = 0;
                adminProductos.forEach(p => {
                    if (Number(p.ID_PROD) > maxId) maxId = Number(p.ID_PROD);
                });
                prodId.value = maxId + 1;
                
                prodStock.checked = true;
                actualizarPreviewImagen();
                limpiarBusquedaImagen();
            } else if (mode === 'edit') {
                modalTitulo.textContent = 'Editar Producto';
                prodId.disabled = true;
                prodId.readOnly = true;
                prodId.classList.add('bg-gray-100', 'text-gray-500');

                const prod = adminProductos.find(p => Number(p.ID_PROD) === Number(id));
                if (prod) {
                    prodId.value = prod.ID_PROD;
                    prodCosto.value = prod.COSTO;
                    prodDescrip.value = prod.DESCRIP;
                    prodCatego.value = prod.CATEGO;
                    prodStock.checked = Number(prod.Stock) === 1;
                    prodImagen.value = prod.imagen_url_raw || '';
                    actualizarPreviewImagen();
                    sugerirBusquedaImagen();
                    limpiarBusquedaImagen();
                }
            }

            modalProducto.classList.remove('hidden');
            modalProducto.classList.add('flex');
        }

        function cerrarModalProducto() {
            modalProducto.classList.add('hidden');
            modalProducto.classList.remove('flex');
        }

        async function guardarProducto(e) {
            e.preventDefault();
            formError.classList.add('hidden');

            const payload = {
                action: modalMode,
                id_prod: Number(prodId.value),
                descrip: prodDescrip.value,
                costo: Number(prodCosto.value),
                stock: prodStock.checked ? 1 : 0,
                catego: prodCatego.value,
                imagen_url: prodImagen.value
            };

            try {
                const res = await fetch('api/guardar_producto.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                
                const data = await res.json();
                if (data.success) {
                    cerrarModalProducto();
                    cargarProductosAdmin();
                } else {
                    formError.textContent = data.error || 'Error al guardar el producto.';
                    formError.classList.remove('hidden');
                }
            } catch (err) {
                formError.textContent = 'Error de conexión al guardar el producto.';
                formError.classList.remove('hidden');
            }
        }

        // Buscador de imágenes en la web
        function sugerirBusquedaImagen() {
            searchImgTerm.value = prodDescrip.value;
        }

        function limpiarBusquedaImagen() {
            searchImgResults.innerHTML = '<p class="col-span-2 text-center text-xs text-agora-400 py-8">Haz clic en "Buscar Web" o escribe un término</p>';
            searchImgLoading.classList.add('hidden');
        }

        function buscarImagenWeb() {
            sugerirBusquedaImagen();
            if (searchImgTerm.value.trim() !== '') {
                ejecutarBusqueda(searchImgTerm.value.trim());
            }
        }

        function buscarImagenWebManual() {
            const term = searchImgTerm.value.trim();
            if (term !== '') {
                ejecutarBusqueda(term);
            }
        }

        async function ejecutarBusqueda(term) {
            searchImgLoading.classList.remove('hidden');
            searchImgResults.innerHTML = '';
            
            try {
                const res = await fetch('api/buscar_imagen.php?q=' + encodeURIComponent(term));
                if (!res.ok) throw new Error('Búsqueda fallida');
                const data = await res.json();
                
                searchImgLoading.classList.add('hidden');
                
                if (data.success && data.images && data.images.length > 0) {
                    data.images.forEach(img => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'group relative aspect-[4/3] rounded-md overflow-hidden border border-agora-200 bg-white hover:border-agora-500 hover:shadow-md transition-all shrink-0';
                        btn.innerHTML = `
                            <img src="${escapeHtml(img.thumbnail)}" alt="${escapeHtml(img.title)}" class="w-full h-full object-cover" />
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                <span class="text-[10px] text-white font-medium text-center p-1 leading-tight line-clamp-2">${escapeHtml(img.title)}</span>
                            </div>
                        `;
                        btn.addEventListener('click', () => {
                            prodImagen.value = img.image;
                            actualizarPreviewImagen();
                        });
                        searchImgResults.appendChild(btn);
                    });
                } else {
                    searchImgResults.innerHTML = '<p class="col-span-2 text-center text-xs text-red-500 py-8">No se encontraron imágenes</p>';
                }
            } catch (e) {
                searchImgLoading.classList.add('hidden');
                searchImgResults.innerHTML = '<p class="col-span-2 text-center text-xs text-red-500 py-8">Error al buscar imágenes</p>';
            }
        }

        function actualizarPreviewImagen() {
            const url = prodImagen.value.trim();
            if (url === '') {
                formImgPreview.classList.add('hidden');
                formImgPreview.src = '';
                formImgPlaceholder.classList.remove('hidden');
            } else {
                formImgPlaceholder.classList.add('hidden');
                formImgPreview.src = url;
                formImgPreview.classList.remove('hidden');
            }
        }

        formImgPreview.addEventListener('error', function() {
            formImgPreview.classList.add('hidden');
            formImgPlaceholder.textContent = 'Error al cargar imagen';
            formImgPlaceholder.classList.remove('hidden');
        });

        function renderProductos(categoria) {
            tituloCategoria.textContent = categoria.nombre;
            listaProductos.innerHTML = '';

            if (!categoria.productos || categoria.productos.length === 0) {
                listaProductos.classList.add('hidden');
                sinProductos.textContent = 'Sin productos';
                sinProductos.classList.remove('hidden');
                return;
            }

            sinProductos.classList.add('hidden');
            listaProductos.classList.remove('hidden');

            categoria.productos.forEach((producto) => {
                const imagen = producto.imagen || placeholderDefault;
                const card = document.createElement('article');
                card.className = 'bg-white rounded-lg shadow-md border border-agora-100 overflow-hidden hover:shadow-lg transition-shadow flex flex-col';
                card.innerHTML = `
                    <div class="aspect-[4/3] bg-agora-100 overflow-hidden shrink-0">
                        <img
                            src="${escapeHtml(imagen)}"
                            alt="${escapeHtml(producto.DESCRIP)}"
                            class="w-full h-full object-cover"
                            loading="lazy"
                            decoding="async"
                        />
                    </div>
                    <div class="p-4 flex flex-col flex-1">
                        <h2 class="text-lg font-medium text-agora-900 leading-snug">${escapeHtml(producto.DESCRIP)}</h2>
                        <p class="mt-2 text-xl font-semibold text-agora-600 mt-auto pt-2">${formatoPrecio.format(Number(producto.COSTO))}</p>
                    </div>
                `;
                const img = card.querySelector('img');
                img.addEventListener('error', function onError() {
                    this.removeEventListener('error', onError);
                    if (this.src.endsWith(placeholderDefault) || this.dataset.fallback) return;
                    this.dataset.fallback = '1';
                    this.src = placeholderDefault;
                });
                listaProductos.appendChild(card);
            });
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function seleccionarCategoria(nombre) {
            mostrarVista('menu');
            const categoria = menuData.categorias.find((c) => c.nombre === nombre);
            if (!categoria) return;
            categoriaActiva = nombre;

            navCategorias.querySelectorAll('[data-categoria]').forEach((btn) => {
                const activo = btn.dataset.categoria === nombre;
                btn.classList.toggle('bg-agora-900', activo);
                btn.classList.toggle('text-white', activo);
                btn.classList.toggle('text-agora-200', !activo);
                btn.classList.toggle('hover:bg-agora-700', !activo);
            });

            renderProductos(categoria);
        }

        function renderNav() {
            navCategorias.innerHTML = '';
            menuData.categorias.forEach((cat) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.dataset.categoria = cat.nombre;
                btn.className = 'sidebar-text w-full text-left flex items-center px-3 py-2.5 text-base font-medium rounded-md text-agora-200 hover:bg-agora-700 hover:text-white transition-colors';
                btn.innerHTML = `<span class="truncate">${escapeHtml(cat.nombre)}</span><span class="ml-auto text-xs opacity-70">${cat.productos.length}</span>`;
                btn.addEventListener('click', () => seleccionarCategoria(cat.nombre));
                navCategorias.appendChild(btn);
            });
        }

        async function cargarMenu() {
            try {
                const res = await fetch('api/menu.php');
                if (!res.ok) throw new Error('Error de red');
                menuData = await res.json();
                if (menuData.placeholderDefault) {
                    placeholderDefault = menuData.placeholderDefault;
                }

                estadoCarga.classList.add('hidden');
                estadoError.classList.add('hidden');

                if (!menuData.categorias || menuData.categorias.length === 0) {
                    sinProductos.textContent = 'No hay productos disponibles en el menú.';
                    sinProductos.classList.remove('hidden');
                    navCategorias.innerHTML = '<p class="px-2 py-2 text-sm text-agora-400 sidebar-text">Sin categorías</p>';
                    return;
                }

                renderNav();
                seleccionarCategoria(menuData.categorias[0].nombre);
            } catch (e) {
                estadoCarga.classList.add('hidden');
                estadoError.classList.remove('hidden');
            }
        }

        const sidebar = document.getElementById('sidebar');
        const collapseBtn = document.getElementById('collapse-btn');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');

        collapseBtn.addEventListener('click', () => {
            sidebar.classList.toggle('w-64');
            sidebar.classList.toggle('w-16');
            collapseBtn.querySelector('svg').classList.toggle('rotate-180');
            sidebarTexts.forEach((text) => text.classList.toggle('hidden'));
        });

        cargarMenu();
    </script>
</body>
</html>
