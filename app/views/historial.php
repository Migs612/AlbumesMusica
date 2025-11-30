<?php include 'layout_header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold mb-8 text-white drop-shadow-lg">Historial de Pedidos</h1>

    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-white">
                <thead class="bg-white/20 text-gray-100 uppercase text-sm tracking-wider">
                    <tr>
                        <th class="p-6 font-semibold">ID Pedido</th>
                        <th class="p-6 font-semibold">Fecha</th>
                        <th class="p-6 font-semibold">Total</th>
                        <th class="p-6 font-semibold">Estado</th>
                        <th class="p-6 font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    <?php foreach($pedidos as $pedido): ?>
                        <tr class="hover:bg-white/5 transition duration-200">
                            <td class="p-6 font-medium text-gray-300">#<?php echo $pedido['id_pedido']; ?></td>
                            <td class="p-6 text-gray-300"><?php echo $pedido['fecha_pedido']; ?></td>
                            <td class="p-6 font-bold text-green-400">$<?php echo $pedido['total']; ?></td>
                            <td class="p-6">
                                <span class="px-3 py-1 bg-green-500/20 text-green-300 border border-green-500/30 rounded-full text-sm font-medium">
                                    <?php echo ucfirst($pedido['estado']); ?>
                                </span>
                            </td>
                            <td class="p-6">
                                <a href="index.php?p=ticket&id=<?php echo $pedido['id_pedido']; ?>" class="text-blue-400 hover:text-blue-300 hover:underline transition duration-200">Ver Ticket</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'layout_footer.php'; ?>
