<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Pedido - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white min-h-screen font-sans">
    
    <div class="max-w-4xl mx-auto px-6 py-10">
        <div class="flex items-center gap-4 mb-8">
            <a href="javascript:history.back()" class="w-10 h-10 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center transition-colors">
                <i class="ri-arrow-left-line"></i>
            </a>
            <h1 class="text-3xl font-bold">Pedido #<?php echo $pedido['id_pedido']; ?></h1>
            <span class="px-3 py-1 rounded-full text-xs font-medium bg-purple-500/20 text-purple-300">
                <?php echo $pedido['fecha_pedido']; ?>
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Detalles del pedido -->
            <div class="md:col-span-2 space-y-4">
                <?php foreach ($detalles as $det): ?>
                <div class="bg-white/5 border border-white/10 rounded-xl p-4 flex gap-4 items-center">
                    <div class="w-16 h-16 bg-gray-800 rounded-lg overflow-hidden flex-shrink-0">
                        <?php if($det['imagen_url']): ?>
                            <img src="<?php echo $det['imagen_url']; ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-gray-600"><i class="ri-disc-line"></i></div>
                        <?php endif; ?>
                    </div>
                    <div class="flex-grow">
                        <h3 class="font-bold text-lg leading-tight"><?php echo $det['titulo']; ?></h3>
                        <p class="text-purple-400 text-sm"><?php echo $det['artista']; ?></p>
                    </div>
                    <div class="text-right">
                        <div class="text-gray-400 text-xs">x<?php echo $det['cantidad']; ?></div>
                        <div class="font-mono text-lg">$<?php echo $det['precio_unitario']; ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Resumen y Usuario -->
            <div class="space-y-6">
                <div class="bg-white/5 border border-white/10 rounded-xl p-6">
                    <h3 class="text-sm font-bold text-gray-400 uppercase mb-4">Cliente</h3>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center font-bold">
                            <?php 
                                echo isset($usuario_pedido['nombre']) ? strtoupper(substr($usuario_pedido['nombre'], 0, 1)) : 'U';
                            ?>
                        </div>
                        <div>
                            <div class="font-bold"><?php echo isset($usuario_pedido['nombre']) ? $usuario_pedido['nombre'] : 'Usuario'; ?></div>
                            <div class="text-xs text-gray-500"><?php echo isset($usuario_pedido['correo']) ? $usuario_pedido['correo'] : ''; ?></div>
                        </div>
                    </div>
                </div>

                <div class="bg-white/5 border border-white/10 rounded-xl p-6">
                    <h3 class="text-sm font-bold text-gray-400 uppercase mb-4">Resumen</h3>
                    <div class="flex justify-between items-center mb-2 text-sm">
                        <span class="text-gray-400">Estado</span>
                        <span class="text-white capitalize"><?php echo $pedido['estado'] ?: 'completado'; ?></span>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t border-white/10">
                        <span class="text-lg font-bold">Total</span>
                        <span class="text-2xl font-bold text-purple-400 font-mono">$<?php echo $pedido['total']; ?></span>
                    </div>
                </div>
                
                <?php if($pedido['estado'] !== 'devuelto'): ?>
                <form action="index.php?p=admin_devolver" method="POST" onsubmit="return confirm('¿Devolver este pedido?');">
                    <input type="hidden" name="id_pedido" value="<?php echo $pedido['id_pedido']; ?>">
                    <button class="w-full bg-yellow-500/10 hover:bg-yellow-500/20 text-yellow-500 py-3 rounded-xl transition-colors font-medium border border-yellow-500/20">
                        Devolver Pedido
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
