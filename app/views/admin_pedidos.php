<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Lavender Tunes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white min-h-screen font-sans">
    
    <nav class="bg-black/40 border-b border-white/10 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="index.php" class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-400 to-pink-400">
                Lavender Admin
            </a>
            <div class="flex gap-6">
                <a href="index.php?p=admin_pedidos" class="<?php echo (!isset($_GET['p']) || $_GET['p'] == 'admin_pedidos') ? 'text-purple-400' : 'text-gray-400 hover:text-white'; ?>">Pedidos</a>
                <a href="index.php?p=admin_usuarios" class="<?php echo (isset($_GET['p']) && $_GET['p'] == 'admin_usuarios') ? 'text-purple-400' : 'text-gray-400 hover:text-white'; ?>">Usuarios</a>
                <a href="index.php" class="text-gray-400 hover:text-white text-sm flex items-center gap-1"><i class="ri-logout-box-r-line"></i> Salir</a>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-10">
        <h1 class="text-3xl font-bold mb-8">Gestión de Pedidos</h1>

        <!-- Filtros -->
        <div class="bg-white/5 border border-white/10 rounded-xl p-6 mb-8">
            <form action="index.php" method="GET" class="flex flex-wrap gap-4 items-end">
                <input type="hidden" name="p" value="admin_pedidos">
                
                <div>
                    <label class="block text-xs text-gray-400 mb-1">Usuario</label>
                    <select name="usuario" class="bg-black/20 border border-white/10 rounded-lg px-4 py-2 text-sm text-gray-300 focus:outline-none focus:border-purple-500 w-48">
                        <option value="">Todos</option>
                        <?php foreach ($usuarios as $usr): ?>
                            <option value="<?php echo $usr['id_usuario']; ?>" <?php echo (isset($_GET['usuario']) && $_GET['usuario'] == $usr['id_usuario']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($usr['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs text-gray-400 mb-1">Producto</label>
                    <input type="text" name="producto" placeholder="Nombre álbum..." value="<?php echo htmlspecialchars($_GET['producto'] ?? ''); ?>" class="bg-black/20 border border-white/10 rounded-lg px-4 py-2 text-sm text-gray-300 focus:outline-none focus:border-purple-500 w-48">
                </div>

                <div>
                    <label class="block text-xs text-gray-400 mb-1">Precio Min</label>
                    <input type="number" name="precio_min" placeholder="0" value="<?php echo htmlspecialchars($_GET['precio_min'] ?? ''); ?>" class="bg-black/20 border border-white/10 rounded-lg px-4 py-2 text-sm text-gray-300 focus:outline-none focus:border-purple-500 w-24">
                </div>

                <div>
                    <label class="block text-xs text-gray-400 mb-1">Precio Max</label>
                    <input type="number" name="precio_max" placeholder="1000" value="<?php echo htmlspecialchars($_GET['precio_max'] ?? ''); ?>" class="bg-black/20 border border-white/10 rounded-lg px-4 py-2 text-sm text-gray-300 focus:outline-none focus:border-purple-500 w-24">
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-purple-600 hover:bg-purple-500 text-white px-4 py-2 rounded-lg text-sm transition-colors">Filtrar</button>
                    <a href="index.php?p=admin_pedidos" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg text-sm transition-colors">Limpiar</a>
                </div>
            </form>
        </div>

        <!-- Tabla -->
        <div class="overflow-x-auto bg-white/5 border border-white/10 rounded-xl">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-white/10 text-gray-400 text-xs uppercase tracking-wider">
                        <th class="p-6">ID</th>
                        <th class="p-6">Usuario</th>
                        <th class="p-6">Fecha</th>
                        <th class="p-6">Total</th>
                        <th class="p-6">Estado</th>
                        <th class="p-6 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-white/5">
                    <?php if (empty($pedidos)): ?>
                        <tr><td colspan="6" class="p-6 text-center text-gray-500">No hay pedidos encontrados.</td></tr>
                    <?php else: ?>
                        <?php foreach ($pedidos as $pedido): ?>
                            <tr class="hover:bg-white/5 transition-colors cursor-pointer" onclick="window.location='index.php?p=admin_ver_pedido&id=<?php echo $pedido['id_pedido']; ?>'">
                                <td class="p-6 text-gray-300">#<?php echo $pedido['id_pedido']; ?></td>
                                <td class="p-6">
                                    <div class="font-medium text-white"><?php echo htmlspecialchars($pedido['nombre_usuario']); ?></div>
                                    <div class="text-xs text-gray-500"><?php echo htmlspecialchars($pedido['correo']); ?></div>
                                </td>
                                <td class="p-6 text-gray-400"><?php echo $pedido['fecha_pedido']; ?></td>
                                <td class="p-6 font-mono text-purple-400">$<?php echo $pedido['total']; ?></td>
                                <td class="p-6">
                                    <?php 
                                        $estado = (!empty($pedido['estado'])) ? $pedido['estado'] : 'completado';
                                        $color = $estado === 'devuelto' ? 'bg-red-500/20 text-red-400' : 'bg-green-500/20 text-green-400';
                                    ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium <?php echo $color; ?>">
                                        <?php echo ucfirst($estado); ?>
                                    </span>
                                </td>
                                <td class="p-6 text-right flex justify-end gap-2" onclick="event.stopPropagation();">
                                    <?php if($estado !== 'devuelto'): ?>
                                    <form action="index.php?p=admin_devolver" method="POST" onsubmit="return confirm('¿Seguro quieres devolver este pedido?');">
                                        <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
                                        <button title="Devolver" class="p-2 bg-yellow-500/10 text-yellow-500 rounded hover:bg-yellow-500/20">
                                            <i class="ri-arrow-go-back-line"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                    
                                    <form action="index.php?p=admin_eliminar" method="POST" onsubmit="return confirm('¿Eliminar permanentemente?');">
                                        <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
                                        <button title="Eliminar" class="p-2 bg-red-500/10 text-red-500 rounded hover:bg-red-500/20">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
