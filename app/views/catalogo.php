<?php include 'layout_header.php'; ?>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <h1 class="text-3xl md:text-4xl font-bold font-unbounded text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-teal-300">
        Descubrir Álbumes
    </h1>
    
    <div class="relative w-full md:w-1/3">
        <form action="index.php" method="GET" class="w-full">
            <input type="hidden" name="p" value="catalogo">
            <input type="text" name="q" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>" placeholder="Buscar álbumes..." class="w-full bg-white/5 border border-white/10 rounded-full py-2 px-4 pl-10 text-white focus:outline-none focus:border-purple-500 transition-colors">
            <button type="submit" class="absolute left-3 top-2.5 text-gray-400 hover:text-white bg-transparent border-none cursor-pointer">
                <i class="ri-search-line"></i>
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-12 gap-8">
    
    
    <div class="md:col-span-3">
        <div class="glass rounded-2xl p-6 sticky top-24">
            <h3 class="font-unbounded font-semibold mb-4 text-xl flex items-center gap-2">
                <i class="ri-filter-3-line"></i> Filtros
            </h3>
            
            <div class="space-y-6">
                <div>
                    <h4 class="text-sm font-semibold mb-3 text-gray-400 uppercase tracking-wider">Género</h4>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="index.php?p=catalogo" 
                           class="px-3 py-2 rounded text-xs font-medium text-center border transition-all <?php echo !isset($_GET['genero']) ? 'bg-purple-500/20 text-purple-300 border-purple-500/30' : 'bg-transparent text-gray-400 border-white/10 hover:bg-white/5'; ?>">
                            Todos
                        </a>
                        <a href="index.php?p=catalogo&genero=1" 
                           class="px-3 py-2 rounded text-xs font-medium text-center border transition-all <?php echo (isset($_GET['genero']) && $_GET['genero'] == 1) ? 'bg-purple-500/20 text-purple-300 border-purple-500/30' : 'bg-transparent text-gray-400 border-white/10 hover:bg-white/5'; ?>">
                            Rock
                        </a>
                        <a href="index.php?p=catalogo&genero=2" 
                           class="px-3 py-2 rounded text-xs font-medium text-center border transition-all <?php echo (isset($_GET['genero']) && $_GET['genero'] == 2) ? 'bg-purple-500/20 text-purple-300 border-purple-500/30' : 'bg-transparent text-gray-400 border-white/10 hover:bg-white/5'; ?>">
                            Pop
                        </a>
                        <a href="index.php?p=catalogo&genero=3" 
                           class="px-3 py-2 rounded text-xs font-medium text-center border transition-all <?php echo (isset($_GET['genero']) && $_GET['genero'] == 3) ? 'bg-purple-500/20 text-purple-300 border-purple-500/30' : 'bg-transparent text-gray-400 border-white/10 hover:bg-white/5'; ?>">
                            Jazz
                        </a>
                        <a href="index.php?p=catalogo&genero=4" 
                           class="px-3 py-2 rounded text-xs font-medium text-center border transition-all <?php echo (isset($_GET['genero']) && $_GET['genero'] == 4) ? 'bg-purple-500/20 text-purple-300 border-purple-500/30' : 'bg-transparent text-gray-400 border-white/10 hover:bg-white/5'; ?>">
                            Latino
                        </a>
                        <a href="index.php?p=catalogo&genero=5" 
                           class="px-3 py-2 rounded text-xs font-medium text-center border transition-all <?php echo (isset($_GET['genero']) && $_GET['genero'] == 5) ? 'bg-purple-500/20 text-purple-300 border-purple-500/30' : 'bg-transparent text-gray-400 border-white/10 hover:bg-white/5'; ?>">
                            Hip-Hop
                        </a>
                        <a href="index.php?p=catalogo&genero=6" 
                           class="px-3 py-2 rounded text-xs font-medium text-center border transition-all <?php echo (isset($_GET['genero']) && $_GET['genero'] == 6) ? 'bg-purple-500/20 text-purple-300 border-purple-500/30' : 'bg-transparent text-gray-400 border-white/10 hover:bg-white/5'; ?>">
                            Electronic
                        </a>
                        <a href="index.php?p=catalogo&genero=7" 
                           class="px-3 py-2 rounded text-xs font-medium text-center border transition-all <?php echo (isset($_GET['genero']) && $_GET['genero'] == 7) ? 'bg-purple-500/20 text-purple-300 border-purple-500/30' : 'bg-transparent text-gray-400 border-white/10 hover:bg-white/5'; ?>">
                            Indie
                        </a>
                        <a href="index.php?p=catalogo&genero=8" 
                           class="px-3 py-2 rounded text-xs font-medium text-center border transition-all <?php echo (isset($_GET['genero']) && $_GET['genero'] == 8) ? 'bg-purple-500/20 text-purple-300 border-purple-500/30' : 'bg-transparent text-gray-400 border-white/10 hover:bg-white/5'; ?>">
                            Metal
                        </a>
                        <a href="index.php?p=catalogo&genero=9" 
                           class="px-3 py-2 rounded text-xs font-medium text-center border transition-all <?php echo (isset($_GET['genero']) && $_GET['genero'] == 9) ? 'bg-purple-500/20 text-purple-300 border-purple-500/30' : 'bg-transparent text-gray-400 border-white/10 hover:bg-white/5'; ?>">
                            R&B
                        </a>
                        <a href="index.php?p=catalogo&genero=10" 
                           class="px-3 py-2 rounded text-xs font-medium text-center border transition-all <?php echo (isset($_GET['genero']) && $_GET['genero'] == 10) ? 'bg-purple-500/20 text-purple-300 border-purple-500/30' : 'bg-transparent text-gray-400 border-white/10 hover:bg-white/5'; ?>">
                            Classical
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="md:col-span-9">
        <?php if(empty($productos)): ?>
            <div class="glass rounded-2xl p-12 text-center">
                <i class="ri-disc-line text-6xl text-gray-600 mb-4"></i>
                <p class="text-xl text-gray-400">No se encontraron álbumes en esta categoría.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach($productos as $prod): ?>
                    <div class="glass-card rounded-xl overflow-hidden h-full flex flex-col group relative">
                        <a href="index.php?p=detalle&id=<?php echo $prod['id_producto']; ?>" class="absolute inset-0 z-10"></a>
                        
                        <div class="aspect-square relative overflow-hidden">
                            <?php if($prod['imagen_url']): ?>
                                <img loading="lazy" src="<?php echo $prod['imagen_url']; ?>" alt="Portada" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <?php else: ?>
                                <div class="w-full h-full bg-gray-800 flex items-center justify-center">
                                    <i class="ri-music-fill text-4xl text-gray-600"></i>
                                </div>
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition-colors"></div>
                        </div>
                        
                        <div class="p-4 flex-grow flex flex-col pointer-events-none">
                            <h3 class="font-semibold text-white truncate mb-1 relative z-0"><?php echo $prod['titulo']; ?></h3>
                            <div class="flex items-center gap-2 mb-3 relative z-0">
                                <span class="text-xs px-2 py-0.5 rounded bg-white/10 text-gray-300 backdrop-blur-sm">
                                    <?php echo $prod['artista']; ?>
                                </span>
                            </div>
                            
                            <div class="mt-auto flex justify-between items-center pt-3 border-t border-white/5 relative z-20 pointer-events-auto">
                                <span class="text-lg font-bold text-purple-400">$<?php echo $prod['precio']; ?></span>
                                <button onclick="confirmarAgregarCarrito(event, '<?php echo $prod['id_producto']; ?>', '<?php echo htmlspecialchars(addslashes($prod['titulo']), ENT_QUOTES); ?>', '<?php echo $prod['precio']; ?>')" class="w-8 h-8 rounded-full bg-white/10 hover:bg-purple-500 flex items-center justify-center transition-colors cursor-pointer">
                                    <i class="ri-add-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>


<div id="modal-carrito" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/60 backdrop-blur-sm opacity-0 transition-opacity duration-300">
    <div class="bg-gray-900 border border-white/10 p-8 rounded-2xl shadow-2xl max-w-md w-full mx-4 transform scale-95 transition-transform duration-300" id="modal-content">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-purple-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ri-shopping-cart-2-line text-3xl text-purple-400"></i>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2">¿Añadir al carrito?</h3>
            <p class="text-gray-300">
                Estás a punto de añadir <br>
                <span id="modal-titulo" class="font-semibold text-purple-400 text-lg block mt-1"></span>
                por <span id="modal-precio" class="font-bold text-white text-xl block mt-1"></span>
            </p>
        </div>
        
        <form id="form-agregar-carrito" action="index.php?p=agregar_carrito" method="POST" class="flex gap-3 justify-center">
            <input type="hidden" name="id_producto" id="modal-id">
            <input type="hidden" name="titulo" id="modal-titulo-input">
            <input type="hidden" name="precio" id="modal-precio-input">
            <input type="hidden" name="ajax" value="1">
            
            <button type="submit" class="px-8 py-2.5 rounded-xl bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-500 hover:to-blue-500 text-white font-bold shadow-lg shadow-purple-500/25 transition-all transform hover:scale-105">
                Sí, añadir
            </button>
            <button type="button" onclick="cerrarModal()" class="px-6 py-2.5 rounded-xl text-gray-400 hover:text-white hover:bg-white/10 transition-colors font-medium">
                Cancelar
            </button>
            
        </form>
    </div>
</div>


<div id="modal-exito" class="fixed inset-0 z-[60] hidden flex items-start justify-center pt-20 pointer-events-none">
    <div class="bg-green-500/90 backdrop-blur-md border border-green-400/50 text-white px-8 py-4 rounded-2xl shadow-2xl flex items-center gap-4 transform -translate-y-full transition-all duration-500 opacity-0" id="modal-exito-content">
        <div class="bg-white/20 rounded-full p-2">
            <i class="ri-check-line text-2xl font-bold"></i>
        </div>
        <div>
            <h4 class="font-bold text-lg">¡Agregado con éxito!</h4>
            <p class="text-green-100">El álbum ya está en tu carrito.</p>
        </div>
    </div>
</div>

<script>
function confirmarAgregarCarrito(e, id, titulo, precio) {
    e.preventDefault();
    e.stopPropagation();
    
    document.getElementById('modal-id').value = id;
    document.getElementById('modal-titulo').textContent = titulo;
    document.getElementById('modal-titulo-input').value = titulo;
    document.getElementById('modal-precio').textContent = '$' + precio;
    document.getElementById('modal-precio-input').value = precio;
    
    const modal = document.getElementById('modal-carrito');
    const content = document.getElementById('modal-content');
    
    modal.classList.remove('hidden');
    // Small delay to allow display:block to apply before opacity transition
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
    }, 10);
}

function cerrarModal() {
    const modal = document.getElementById('modal-carrito');
    const content = document.getElementById('modal-content');
    
    modal.classList.add('opacity-0');
    content.classList.remove('scale-100');
    content.classList.add('scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Manejar el envío del formulario con AJAX
document.getElementById('form-agregar-carrito').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('index.php?p=agregar_carrito', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            cerrarModal();
            mostrarExito();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Fallback en caso de error
        this.submit();
    });
});

function mostrarExito() {
    const modal = document.getElementById('modal-exito');
    const content = document.getElementById('modal-exito-content');
    
    modal.classList.remove('hidden');
    
    setTimeout(() => {
        content.classList.remove('-translate-y-full');
        content.classList.remove('opacity-0');
    }, 10);
    
    // Ocultar automáticamente después de 3 segundos
    setTimeout(() => {
        content.classList.add('-translate-y-full');
        content.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 500);
    }, 3000);
}

// Close modal when clicking outside
document.getElementById('modal-carrito').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModal();
    }
});
</script>

<?php include 'layout_footer.php'; ?>
