<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - Admin</title>
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
        <h1 class="text-3xl font-bold mb-8">Base de Usuarios</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($usuarios as $usr): ?>
            <a href="index.php?p=admin_usuario_historial&id=<?php echo $usr['id_usuario']; ?>" class="bg-white/5 border border-white/10 rounded-xl p-6 flex items-center gap-4 hover:bg-white/10 transition-colors cursor-pointer group">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-lg font-bold group-hover:scale-110 transition-transform">
                    <?php echo strtoupper(substr($usr['nombre'], 0, 1)); ?>
                </div>
                <div>
                    <h3 class="font-bold text-lg"><?php echo htmlspecialchars($usr['nombre']); ?></h3>
                    <p class="text-gray-400 text-sm"><?php echo htmlspecialchars($usr['correo']); ?></p>
                    <p class="text-gray-600 text-xs mt-1">ID: #<?php echo $usr['id_usuario']; ?> <span class="ml-2 text-purple-400 text-xs">Ver historial <i class="ri-arrow-right-line"></i></span></p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
