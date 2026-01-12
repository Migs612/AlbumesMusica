<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lavender Tunes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        h1, h2, h3, h4, .font-unbounded {
            font-family: 'Unbounded', sans-serif;
        }
        
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        .glass-card:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.2);
        }
        .text-gradient {
            background: linear-gradient(to right, #a78bfa, #2dd4bf);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #111827; 
        }
        ::-webkit-scrollbar-thumb {
            background: #374151; 
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #4b5563; 
        }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col">


<div class="fixed inset-0 -z-10 opacity-30 pointer-events-none" 
     style="background: radial-gradient(circle at 20% 20%, rgba(139, 92, 246, 0.15) 0%, transparent 50%), 
                        radial-gradient(circle at 80% 80%, rgba(45, 212, 191, 0.15) 0%, transparent 50%);">
</div>

<nav class="fixed w-full z-50 glass border-b-0 border-white/10">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <a href="index.php?p=home" class="text-2xl font-bold font-unbounded flex items-center gap-2">
            <i class="ri-disc-line text-purple-400"></i>
            <span class="text-gradient">Lavender Tunes</span>
        </a>
        
        <div class="hidden md:flex items-center gap-8 font-medium text-sm text-gray-300">
            <a href="index.php?p=catalogo" class="hover:text-white transition-colors flex items-center gap-1">
                <i class="ri-album-line"></i> Catálogo
            </a>
            
            <?php if(isset($_SESSION['usuario_id'])): ?>
                <a href="index.php?p=favoritos" class="hover:text-white transition-colors flex items-center gap-1">
                    <i class="ri-heart-line"></i> Favoritos
                </a>
                <a href="index.php?p=historial" class="hover:text-white transition-colors flex items-center gap-1">
                    <i class="ri-history-line"></i> Pedidos
                </a>
                <div class="h-4 w-px bg-white/20"></div>
                <span class="text-purple-300"><?php echo $_SESSION['usuario_nombre']; ?></span>
                <a href="index.php?p=logout" class="hover:text-red-400 transition-colors">
                    <i class="ri-logout-box-line"></i>
                </a>
            <?php else: ?>
                <div class="h-4 w-px bg-white/20"></div>
                <a href="index.php?p=login" class="hover:text-white transition-colors">Login</a>
                <a href="index.php?p=registro" class="bg-white/10 hover:bg-white/20 px-4 py-2 rounded-full transition-all border border-white/10">
                    Registro
                </a>
            <?php endif; ?>

            <a href="index.php?p=carrito" class="relative group">
                <div class="p-2 bg-purple-500/20 rounded-full group-hover:bg-purple-500/40 transition-colors">
                    <i class="ri-shopping-cart-2-line text-purple-300"></i>
                </div>
                <?php if(isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0): ?>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">
                        <?php echo count($_SESSION['carrito']); ?>
                    </span>
                <?php endif; ?>
            </a>
        </div>
    </div>
</nav>

<div class="pt-24 pb-12 flex-grow container mx-auto px-4">
