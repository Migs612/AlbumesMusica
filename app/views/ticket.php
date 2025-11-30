<?php include 'layout_header.php'; ?>

<div class="max-w-3xl mx-auto bg-white/10 backdrop-blur-md border border-white/20 p-8 rounded-xl shadow-2xl mt-8">
    <div class="text-center mb-8">
        <div class="inline-block p-4 rounded-full bg-green-500/20 mb-4">
            <svg class="w-12 h-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <h1 class="text-3xl font-bold text-white mb-2">¡Gracias por tu compra!</h1>
        <p class="text-gray-300">Tu pedido ha sido procesado exitosamente.</p>
    </div>

    <div class="border-b border-white/10 pb-6 mb-6 flex justify-between items-center">
        <div>
            <p class="text-sm text-gray-400 uppercase tracking-wide">ID de Pedido</p>
            <p class="text-2xl font-bold text-white">#<?php echo $id_pedido; ?></p>
        </div>
        <div class="text-right">
            <p class="text-sm text-gray-400 uppercase tracking-wide">Fecha</p>
            <p class="text-lg text-white"><?php echo $pedido['fecha_pedido']; ?></p>
        </div>
    </div>

    <div class="mb-8">
        <h2 class="text-xl font-semibold text-white mb-4">Detalle del Pedido</h2>
        <div class="bg-black/20 rounded-lg overflow-hidden">
            <?php foreach($productos_ticket as $prod): ?>
                <div class="flex items-center p-4 border-b border-white/5 last:border-0">
                    <img loading="lazy" src="<?php echo $prod['imagen']; ?>" alt="<?php echo $prod['titulo']; ?>" class="w-16 h-16 object-cover rounded shadow-md mr-4">
                    <div class="flex-1">
                        <h3 class="text-white font-medium"><?php echo $prod['titulo']; ?></h3>
                        <p class="text-gray-400 text-sm"><?php echo $prod['artista']; ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-white font-bold">$<?php echo number_format($prod['subtotal'], 2); ?></p>
                        <p class="text-gray-500 text-xs"><?php echo $prod['cantidad']; ?> x $<?php echo $prod['precio']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="flex justify-end mt-4">
            <div class="text-right">
                <p class="text-gray-400 text-sm uppercase">Total Pagado</p>
                <p class="text-3xl font-bold text-green-400">$<?php echo number_format($pedido['total'], 2); ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white/5 p-6 rounded-lg mb-8 border border-white/10">
        <p class="text-center text-gray-300 italic font-light text-lg">
            "La música es el arte más directo, entra por el oído y va al corazón."
        </p>
    </div>

    <div class="text-center flex flex-col sm:flex-row justify-center gap-4">
        <a href="index.php?p=catalogo" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-full font-bold transition duration-300 shadow-lg hover:shadow-blue-600/50">
            Seguir Comprando
        </a>
        <a href="index.php?p=historial" class="bg-transparent border border-white/30 hover:bg-white/10 text-white px-8 py-3 rounded-full font-bold transition duration-300">
            Ver Historial
        </a>
    </div>
</div>

<?php include 'layout_footer.php'; ?>
