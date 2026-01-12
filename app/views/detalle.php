<?php include 'layout_header.php'; ?>


<div class="flex flex-col md:flex-row gap-8 mb-12 animate-fade-in">
    
    <div class="w-full md:w-1/3 lg:w-1/4 flex-shrink-0">
        <div class="aspect-square rounded-xl overflow-hidden shadow-2xl border border-white/10 relative group">
            <?php if ($producto['imagen_url']): ?>
                <img src="<?php echo $producto['imagen_url']; ?>" alt="Portada" class="w-full h-full object-cover">
            <?php else: ?>
                <div class="w-full h-full bg-gray-800 flex items-center justify-center">
                    <i class="ri-disc-line text-6xl text-gray-600"></i>
                </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="flex flex-col justify-end pb-2">
        <h4 class="text-sm font-bold uppercase tracking-wider text-white/60 mb-2">Álbum</h4>
        <h1 class="text-4xl md:text-6xl font-bold font-unbounded mb-4 leading-tight"><?php echo $producto['titulo']; ?>
        </h1>

        <div class="flex items-center gap-3 text-gray-300 mb-6">
            <div class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center">
                <i class="ri-user-fill text-sm"></i>
            </div>
            <span class="font-bold text-white hover:underline cursor-pointer"><?php echo $producto['artista']; ?></span>
            <span class="text-white/40">•</span>
            <span>2024</span>
            <span class="text-white/40">•</span>
            <span class="text-purple-400 font-bold">Popularidad: <?php echo $producto['popularidad']; ?>%</span>
        </div>

        <div class="flex items-center gap-4">
            <form action="index.php?p=agregar_carrito" method="POST">
                <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                <input type="hidden" name="titulo" value="<?php echo $producto['titulo']; ?>">
                <input type="hidden" name="precio" value="<?php echo $producto['precio']; ?>">

                <button type="submit"
                    class="bg-green-500 text-black px-8 py-3 rounded-full font-bold hover:scale-105 transition-transform flex items-center gap-2">
                    <i class="ri-shopping-cart-fill text-xl"></i>
                    <span class="text-lg">$<?php echo $producto['precio']; ?></span>
                </button>
            </form>

            <?php if(isset($_SESSION['usuario_id'])): ?>
            <button onclick="toggleFavorito(event, '<?php echo $producto['id_producto']; ?>')"
                class="w-12 h-12 rounded-full border border-white/20 flex items-center justify-center hover:bg-white/10 transition-colors <?php echo $es_favorito ? 'text-pink-500 border-pink-500/50' : 'text-white/70 hover:text-white'; ?>" id="btn-favorito">
                <i class="<?php echo $es_favorito ? 'ri-heart-fill' : 'ri-heart-line'; ?> text-xl"></i>
            </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function toggleFavorito(e, id) {
    e.preventDefault();
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
            if (data.action === 'added') {
                icon.classList.remove('ri-heart-line');
                icon.classList.add('ri-heart-fill');
                btn.classList.remove('text-white/70');
                btn.classList.add('text-pink-500', 'border-pink-500/50');
            } else {
                icon.classList.remove('ri-heart-fill');
                icon.classList.add('ri-heart-line');
                btn.classList.remove('text-pink-500', 'border-pink-500/50');
                btn.classList.add('text-white/70');
            }
        } else {
            alert(data.message || 'Error al actualizar favoritos');
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>


<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

    
    <div class="lg:col-span-8">
        <div class="glass rounded-xl p-1">
            <div class="px-4 py-2 border-b border-white/5 text-gray-400 text-sm flex justify-between">
                <span># Título</span>
                <span><i class="ri-time-line"></i></span>
            </div>

            <?php if (!empty($tracks)): ?>
                <?php foreach ($tracks as $track): ?>
                    <div
                        class="group flex items-center justify-between px-4 py-3 hover:bg-white/5 rounded-lg transition-colors cursor-pointer">
                        <div class="flex items-center gap-4">
                            <span
                                class="text-gray-500 w-4 text-center group-hover:hidden"><?php echo $track['track_number']; ?></span>
                            <span class="hidden group-hover:block w-4 text-center text-white"><i
                                    class="ri-play-fill"></i></span>

                            <div>
                                <h4 class="text-white font-medium"><?php echo $track['name']; ?></h4>
                                <p class="text-xs text-gray-500">
                                    <?php echo isset($track['artists'][0]['name']) ? $track['artists'][0]['name'] : $producto['artista']; ?>
                                </p>
                            </div>
                        </div>
                        <span class="text-gray-500 text-sm">
                            <?php
                            $min = floor($track['duration_ms'] / 60000);
                            $sec = floor(($track['duration_ms'] % 60000) / 1000);
                            echo $min . ':' . sprintf('%02d', $sec);
                            ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="p-8 text-center text-gray-500">
                    No hay canciones disponibles para este álbum.
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="lg:col-span-4">
        <div class="glass rounded-xl p-6 mt-6">
            <h3 class="font-bold text-lg mb-4">Álbumes Similares</h3>
            <div class="space-y-4">
                <?php if (!empty($albumes_similares)): ?>
                    <?php foreach ($albumes_similares as $sim): ?>
                        <a href="index.php?p=detalle&id=<?php echo $sim['id_producto']; ?>"
                            class="flex items-center gap-3 group hover:bg-white/5 p-2 rounded-lg transition-colors">
                            <div class="w-16 h-16 rounded overflow-hidden flex-shrink-0">
                                <?php if ($sim['imagen_url']): ?>
                                    <img src="<?php echo $sim['imagen_url']; ?>"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                                <?php else: ?>
                                    <div class="w-full h-full bg-gray-800 flex items-center justify-center"><i
                                            class="ri-disc-fill"></i></div>
                                <?php endif; ?>
                            </div>
                            <div class="overflow-hidden">
                                <h4 class="font-bold text-sm truncate text-white group-hover:text-purple-400 transition-colors">
                                    <?php echo $sim['titulo']; ?></h4>
                                <p class="text-xs text-gray-400 truncate"><?php echo $sim['artista']; ?></p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-sm text-gray-500">No se encontraron álbumes similares.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'layout_footer.php'; ?>