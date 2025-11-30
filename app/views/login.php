<?php include 'layout_header.php'; ?>

<div class="max-w-md mx-auto glass rounded-2xl p-8 mt-10 border border-white/10">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold font-unbounded mb-2">Bienvenido</h2>
        <p class="text-gray-400">Inicia sesión para continuar</p>
    </div>
    
    <?php if(isset($error)): ?>
        <div class="bg-red-500/20 border border-red-500/50 text-red-200 px-4 py-3 rounded mb-6 text-sm flex items-center gap-2">
            <i class="ri-error-warning-line"></i> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form action="index.php?p=login" method="POST" class="space-y-6">
        <div>
            <label class="block text-gray-300 text-sm font-bold mb-2" for="correo">Correo Electrónico</label>
            <div class="relative">
                <i class="ri-mail-line absolute left-3 top-3 text-gray-500"></i>
                <input class="w-full bg-black/20 border border-white/10 rounded-lg py-3 px-4 pl-10 text-white focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all" 
                       id="correo" name="correo" type="email" placeholder="ejemplo@correo.com" required>
            </div>
        </div>
        
        <div>
            <label class="block text-gray-300 text-sm font-bold mb-2" for="contrasena">Contraseña</label>
            <div class="relative">
                <i class="ri-lock-line absolute left-3 top-3 text-gray-500"></i>
                <input class="w-full bg-black/20 border border-white/10 rounded-lg py-3 px-4 pl-10 text-white focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition-all" 
                       id="contrasena" name="contrasena" type="password" placeholder="••••••••" required>
            </div>
        </div>

        <button class="w-full bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-500 hover:to-blue-500 text-white font-bold py-3 px-4 rounded-lg shadow-lg transform hover:scale-[1.02] transition-all" type="submit">
            Entrar
        </button>

        <div class="text-center mt-6">
            <p class="text-gray-400 text-sm">¿No tienes cuenta?</p>
            <a class="text-purple-400 hover:text-purple-300 font-bold text-sm" href="index.php?p=registro">
                Regístrate gratis
            </a>
        </div>
    </form>
</div>

<?php include 'layout_footer.php'; ?>
