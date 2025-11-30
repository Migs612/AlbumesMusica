<?php include 'layout_header.php'; ?>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <h1 class="text-3xl md:text-4xl font-bold font-unbounded text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-rose-400">
        Mis Favoritos
    </h1>
</div>

<?php if(empty($favoritos)): ?>
    <div class="glass rounded-2xl p-12 text-center">
        <i class="ri-heart-broken-line text-6xl text-gray-600 mb-4"></i>
        <p class="text-xl text-gray-400 mb-6">Aún no tienes álbumes favoritos.</p>
        <a href="index.php?p=catalogo" class="inline-block bg-white text-black px-8 py-3 rounded-full font-bold hover:bg-gray-200 transition-colors">
            Explorar Catálogo
        </a>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <?php foreach($favoritos as $prod): ?>
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
                    
                    
                    <button onclick="toggleFavorito(event, '<?php echo $prod['id_producto']; ?>')" class="absolute top-3 right-3 z-20 w-8 h-8 rounded-full bg-black/50 backdrop-blur-md flex items-center justify-center text-pink-500 hover:bg-pink-500 hover:text-white transition-all">
                        <i class="ri-heart-fill"></i>
                    </button>
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
function toggleFavorito(e, id) {
    e.preventDefault();
    e.stopPropagation();
    
    const btn = e.currentTarget;
    const icon = btn.querySelector('i');
    
    const formData = new FormData();
    formData.append('id_producto', id);
    
    fetch('index.php?p=toggle_favorito', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Si estamos en la página de favoritos, recargamos para quitar el elemento
            window.location.reload();
        }
    });
}

// Funciones del carrito (copiadas de home/catalogo)
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
    setTimeout(() => {
        content.classList.add('-translate-y-full');
        content.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 500);
    }, 3000);
}

document.getElementById('modal-carrito').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});
</script>

<?php include 'layout_footer.php'; ?>
