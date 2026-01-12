<?php include 'layout_header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-8 text-white drop-shadow-lg">Carrito de Compras</h1>

    <?php if(empty($carrito)): ?>
        <div class="bg-white/10 backdrop-blur-md border border-white/20 p-12 rounded-xl shadow-xl text-center">
            <p class="text-2xl text-gray-200 mb-6">Tu carrito está vacío.</p>
            <a href="index.php?p=catalogo" class="inline-block bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-500 hover:to-blue-500 text-white font-bold py-3 px-8 rounded-full transition duration-300 shadow-lg">
                Ir a comprar música
            </a>
        </div>
    <?php else: ?>
        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-white">
                    <thead class="bg-white/20 text-gray-100 uppercase text-sm tracking-wider">
                        <tr>
                            <th class="p-6 font-semibold">Producto</th>
                            <th class="p-6 font-semibold">Precio</th>
                            <th class="p-6 font-semibold">Cantidad</th>
                            <th class="p-6 font-semibold">Subtotal</th>
                            <th class="p-6 font-semibold text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        <?php foreach($carrito as $item): ?>
                            <tr class="hover:bg-white/5 transition duration-200">
                                <td class="p-6 font-medium text-lg"><?php echo $item['titulo']; ?></td>
                                <td class="p-6 text-gray-300">$<?php echo $item['precio']; ?></td>
                                <td class="p-6 text-gray-300 flex items-center gap-2">
                                    <span><?php echo $item['cantidad']; ?></span>
                                    <button onclick="abrirModalCantidad('<?php echo $item['id_producto']; ?>', '<?php echo $item['cantidad']; ?>', '<?php echo htmlspecialchars(addslashes($item['titulo']), ENT_QUOTES); ?>')" class="text-blue-400 hover:text-blue-300 transition-colors p-1 rounded hover:bg-white/10">
                                        <i class="ri-pencil-line"></i>
                                    </button>
                                </td>
                                <td class="p-6 font-bold text-green-400">$<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></td>
                                <td class="p-6 text-right">
                                    <form action="index.php?p=eliminar_carrito" method="POST" class="inline-block">
                                        <input type="hidden" name="id_producto" value="<?php echo $item['id_producto']; ?>">
                                        <button type="submit" class="text-red-400 hover:text-red-300 transition-colors p-2 rounded hover:bg-red-500/20" title="Eliminar del carrito">
                                            <i class="ri-delete-bin-line text-xl"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="p-6 bg-black/20 flex flex-col md:flex-row justify-end items-center gap-6 border-t border-white/10">
                <span class="text-3xl font-bold text-white">Total: <span class="text-green-400">$<?php echo number_format($total, 2); ?></span></span>
                <form action="index.php?p=procesar_pedido" method="POST">
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-8 py-3 rounded-full font-bold text-lg transition duration-300 shadow-lg hover:shadow-green-500/50 transform hover:scale-105">
                        Finalizar Compra
                    </button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>


<div id="modal-cantidad" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/60 backdrop-blur-sm opacity-0 transition-opacity duration-300">
    <div class="bg-gray-900 border border-white/10 p-8 rounded-2xl shadow-2xl max-w-sm w-full mx-4 transform scale-95 transition-transform duration-300" id="modal-cantidad-content">
        <div class="text-center mb-6">
            <h3 class="text-xl font-bold text-white mb-2">Editar Cantidad</h3>
            <p class="text-gray-400 text-sm" id="modal-cantidad-titulo"></p>
        </div>
        
        <form action="index.php?p=actualizar_carrito" method="POST" class="flex flex-col gap-4">
            <input type="hidden" name="id_producto" id="modal-cantidad-id">
            
            <div class="flex items-center justify-center gap-4">
                <button type="button" onclick="ajustarCantidad(-1)" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
                    <i class="ri-subtract-line"></i>
                </button>
                <input type="number" name="cantidad" id="modal-cantidad-input" min="1" class="w-20 bg-black/30 border border-white/20 rounded-lg py-2 text-center text-white font-bold text-xl focus:outline-none focus:border-purple-500" required>
                <button type="button" onclick="ajustarCantidad(1)" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
                    <i class="ri-add-line"></i>
                </button>
            </div>
            
            <div class="flex gap-3 mt-4">
                <button type="submit" class="flex-1 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold shadow-lg shadow-blue-500/25 transition-all">
                    Guardar
                </button>
                <button type="button" onclick="cerrarModalCantidad()" class="flex-1 py-2.5 rounded-xl text-gray-400 hover:text-white hover:bg-white/10 transition-colors font-medium">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirModalCantidad(id, cantidad, titulo) {
    document.getElementById('modal-cantidad-id').value = id;
    document.getElementById('modal-cantidad-input').value = cantidad;
    document.getElementById('modal-cantidad-titulo').textContent = titulo;
    
    const modal = document.getElementById('modal-cantidad');
    const content = document.getElementById('modal-cantidad-content');
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
    }, 10);
}

function cerrarModalCantidad() {
    const modal = document.getElementById('modal-cantidad');
    const content = document.getElementById('modal-cantidad-content');
    
    modal.classList.add('opacity-0');
    content.classList.remove('scale-100');
    content.classList.add('scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function ajustarCantidad(delta) {
    const input = document.getElementById('modal-cantidad-input');
    let val = parseInt(input.value) || 0;
    val += delta;
    if (val < 1) val = 1;
    input.value = val;
}


document.getElementById('modal-cantidad').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModalCantidad();
    }
});
</script>

<?php include 'layout_footer.php'; ?>
