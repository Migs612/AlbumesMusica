<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial Usuario - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white min-h-screen font-sans">
    
    <div class="max-w-5xl mx-auto px-6 py-10">
        <div class="flex items-center gap-4 mb-8">
            <a href="index.php?p=admin_usuarios" class="w-10 h-10 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center transition-colors">
                <i class="ri-arrow-left-line"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold"><?php echo htmlspecialchars($usuario_ver['nombre']); ?></h1>
                <p class="text-gray-400"><?php echo htmlspecialchars($usuario_ver['correo']); ?></p>
            </div>
        </div>

        <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
            <i class="ri-history-line text-purple-400"></i> Historial de Pedidos
        </h2>

        <div class="bg-white/5 border border-white/10 rounded-xl overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-black/20">
                    <tr>
                        <th class="p-4 text-gray-400 font-medium">ID Pedido</th>
                        <th class="p-4 text-gray-400 font-medium">Fecha</th>
                        <th class="p-4 text-gray-400 font-medium">Items</th>
                        <th class="p-4 text-gray-400 font-medium">Total</th>
                        <th class="p-4 text-gray-400 font-medium">Estado</th>
                        <th class="p-4 text-right"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php if (empty($pedidos_usuario)): ?>
                        <tr><td colspan="6" class="p-8 text-center text-gray-500">Este usuario no ha realizado pedidos.</td></tr>
                    <?php else: ?>
                        <?php foreach($pedidos_usuario as $ped): ?>
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="p-4 font-mono text-gray-300">#<?php echo $ped['id_pedido']; ?></td>
                            <td class="p-4 text-sm text-gray-400"><?php echo $ped['fecha_pedido']; ?></td>
                            <td class="p-4 text-sm">
                                <?php 
                                    echo "Ver detalle para items";
                                ?>
                            </td>
                            <td class="p-4 font-bold text-purple-400">$<?php echo $ped['total']; ?></td>
                            <td class="p-4">
                                <?php 
                                    $est = $ped['estado'] ?: 'completado';
                                    $col = $est === 'devuelto' ? 'text-red-400 bg-red-500/10' : 'text-green-400 bg-green-500/10';
                                ?>
                                <span class="px-2 py-1 rounded text-xs font-medium <?php echo $col; ?>">
                                    <?php echo ucfirst($est); ?>
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <a href="index.php?p=admin_ver_pedido&id=<?php echo $ped['id_pedido']; ?>" class="text-purple-400 hover:text-white text-sm font-medium">
                                    Ver Detalle <i class="ri-arrow-right-line"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
