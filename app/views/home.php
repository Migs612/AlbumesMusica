<?php include 'layout_header.php'; ?>


<div class="relative rounded-3xl overflow-hidden glass border border-white/10 p-8 md:p-16 mb-16 text-center md:text-left">
    <div class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-96 h-96 bg-teal-500/20 rounded-full blur-3xl"></div>
    
    <div class="relative z-10 flex flex-col md:flex-row items-center gap-12">
        <div class="w-full md:w-1/2 space-y-6">
            <h1 class="text-4xl md:text-6xl font-bold font-unbounded leading-tight">
                Descubre tu <br>
                <span class="text-gradient">Próxima Obsesión</span>
            </h1>
            <p class="text-gray-300 text-lg leading-relaxed">
                Explora miles de álbumes, crea tu colección y conecta con la música como nunca antes. Calidad premium, experiencia inmersiva.
            </p>
            <div class="flex gap-4 justify-center md:justify-start pt-4">
                <a href="index.php?p=catalogo" class="bg-white text-black px-8 py-3 rounded-full font-bold hover:bg-gray-200 transition-colors flex items-center gap-2">
                    <i class="ri-play-circle-fill text-xl"></i> Explorar
                </a>
                <?php if(!isset($_SESSION['usuario_id'])): ?>
                <a href="index.php?p=registro" class="glass px-8 py-3 rounded-full font-bold hover:bg-white/10 transition-colors border border-white/20">
                    Unirse
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        
        <div class="w-full md:w-1/2 flex justify-center">
            <div class="relative w-64 h-64 md:w-80 md:h-80">
                <div class="absolute inset-0 bg-gradient-to-tr from-purple-500 to-teal-500 rounded-2xl rotate-6 opacity-50 blur-sm"></div>
                <div class="absolute inset-0 bg-gray-800 rounded-2xl -rotate-3 border border-white/10 flex items-center justify-center overflow-hidden shadow-2xl">
                    <?php 
                        $hero_image = !empty($productos_destacados) && isset($productos_destacados[0]['imagen_url']) 
                            ? $productos_destacados[0]['imagen_url'] 
                            : "https://images.unsplash.com/photo-1493225255756-d9584f8606e9?q=80&w=800&auto=format&fit=crop";
                    ?>
                    <img src="<?php echo $hero_image; ?>" alt="Hero" class="w-full h-full object-cover opacity-80">
                </div>
            </div>
        </div>
    </div>
</div>


<div class="w-full h-px bg-gradient-to-r from-transparent via-white/10 to-transparent my-16"></div>


<div class="mb-12">
    <div class="flex justify-between items-end mb-8">
        <h2 class="text-3xl font-bold font-unbounded">Tendencias</h2>
        <a href="index.php?p=catalogo" class="text-sm text-purple-400 hover:text-purple-300 flex items-center gap-1">
            Ver todo <i class="ri-arrow-right-line"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        <?php foreach($productos_destacados as $prod): ?>
            <div class="glass-card rounded-xl overflow-hidden h-full flex flex-col group relative">
                <a href="index.php?p=detalle&id=<?php echo $prod['id_producto']; ?>" class="absolute inset-0 z-10"></a>
                
                <div class="aspect-square relative overflow-hidden">
                    <?php if($prod['imagen_url']): ?>
                        <img src="<?php echo $prod['imagen_url']; ?>" alt="Portada" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
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
    })
    .catch(error => {
        console.error('Error:', error);
        
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
    
    
    setTimeout(() => {
        content.classList.add('-translate-y-full');
        content.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 500);
    }, 3000);
}


document.getElementById('modal-carrito').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModal();
    }
});
</script>

<?php include 'layout_footer.php'; ?>
