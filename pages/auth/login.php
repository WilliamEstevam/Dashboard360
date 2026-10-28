<?php

$error = isset($GLOBALS['error']) ? $GLOBALS['error'] : '';
$success = isset($GLOBALS['success']) ? $GLOBALS['success'] : '';
$showRegister = isset($GLOBALS['show_register']) ? $GLOBALS['show_register'] : false;

$mockLideres = array(
    array('nome' => 'Equipe Demo A', 'foto' => 'shared/assets/img/avatar.svg'),
    array('nome' => 'Equipe Demo B', 'foto' => 'shared/assets/img/avatar.svg'),
    array('nome' => 'Equipe Demo C', 'foto' => 'shared/assets/img/avatar.svg')
);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/svg+xml" href="shared/assets/img/brand.svg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso - Dashboard360</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="shared/assets/css/auth.css">
    <link rel="stylesheet" href="shared/assets/css/scrollbars.css">
    <style>
        /* Estilos base garantidos e compactados para remover efeito de zoom */
        html, body { min-height: 100%; margin: 0; padding: 0; overflow-x: hidden; background-color: #020617; font-family: 'Plus Jakarta Sans', sans-serif; }
        .login-container { display: flex; min-height: 100vh; width: 100vw; }
        
        .login-visual { flex: 2; position: relative; background-image: url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=1600'); background-size: cover; background-position: center; display: none; }
        @media (min-width: 1024px) { .login-visual { display: flex; } }
        .login-visual::after { content: ""; position: absolute; inset: 0; background: linear-gradient(to right, rgba(2, 6, 23, 0.98) 0%, rgba(2, 6, 23, 0.2) 100%); }
        
        .login-form-side { flex: 1; max-width: 460px; display: flex; align-items: center; justify-content: center; padding: 2rem; background-color: #020617; border-left: 1px solid rgba(255,255,255,0.05); }
        
        .dashboard-field {
            width: 100%;
            background-color: #0f172a !important; 
            border: 1px solid #1e293b !important;
            border-radius: 0.6rem; /* Mais subtil */
            padding: 0.85rem 1rem; /* Reduzido o padding vertical e horizontal */
            color: #f8fafc !important;
            font-size: 0.8rem; /* Fonte ligeiramente menor */
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            outline: none;
        }
        .dashboard-field::placeholder { color: #475569; }
        .dashboard-field:focus {
            border-color: #6366f1 !important;
            background-color: #020617 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }
        .dashboard-field.token-input {
            color: #818cf8 !important; 
            font-weight: 900;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }
        .dashboard-label {
            display: block;
            font-size: 0.6rem; /* Label menor */
            font-weight: 800;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 0.4rem;
            letter-spacing: 0.1em;
            margin-left: 0.25rem;
        }
        .btn-dashboard {
            width: 100%;
            background: #6366f1;
            color: white;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.75rem; 
            padding: 1rem; 
            border-radius: 0.6rem;
            border: none;
            cursor: pointer;
            transition: all 0.4s;
            box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4);
            margin-top: 1rem;
        }
        .btn-dashboard:hover {
            background: #4f46e5;
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -5px rgba(99, 102, 241, 0.5);
        }
    </style>
</head>
<body class="antialiased text-slate-800 dark:text-slate-100 bg-[#020617]">

<div class="login-container">
    <!-- LADO ESQUERDO: VISUAL -->
    <div class="login-visual">
        <div class="relative z-10 p-16 lg:p-20 flex flex-col justify-center animate-in fade-in slide-in-from-left duration-1000">
            <span class="text-indigo-500 font-black uppercase tracking-[0.3em] text-[10px] mb-6 block">Inteligência Operacional</span>
            <h1 class="text-5xl lg:text-6xl font-black text-white leading-none mb-6 tracking-tighter italic uppercase">
                Venha construir <br>o futuro com <br>a <span class="text-indigo-500">Dashboard360.</span>
            </h1>
            <p class="text-slate-400 text-base leading-relaxed max-w-md opacity-80">Acompanhe indicadores em tempo real, gerencie metas e otimize a produtividade da sua operação em uma única plataforma 360°.</p>
            <div class="mt-16 flex items-center space-x-5">
                <div class="flex -space-x-3">
                    <?php foreach($mockLideres as $l): ?>
                        <div class="w-10 h-10 rounded-xl border-2 border-[#020617] bg-slate-800 overflow-hidden shadow-lg transition-transform hover:scale-110 hover:z-20 cursor-pointer" title="<?php echo $l['nome']; ?>">
                            <img src="<?php echo $l['foto']; ?>" alt="<?php echo $l['nome']; ?>" class="w-full h-full object-cover">
                        </div>
                    <?php endforeach; ?>
                </div>
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Indicadores em uma única plataforma</p>
            </div>
        </div>
    </div>

    <!-- LADO DIREITO: PAINEL DE FORMULÁRIOS -->
    <div class="login-form-side overflow-y-auto custom-scroll relative">
        <div class="w-full max-w-[360px] animate-in fade-in slide-in-from-right duration-700 py-8">
            
            <div class="mb-10">
                <h2 class="text-3xl font-black text-white tracking-tighter italic uppercase leading-none">DASHBOARD <span class="text-indigo-500">360</span></h2>
                <p class="text-[8px] font-black text-slate-600 uppercase tracking-[0.4em] mt-2 ml-1">Portal de Gestão Centralizada</p>
            </div>

            <!-- ALERTAS -->
            <?php if ($error): ?>
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-lg text-red-400 text-[10px] font-black uppercase tracking-wider flex items-center shadow-sm">
                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-lg text-emerald-400 text-[10px] font-medium uppercase tracking-wider flex items-center shadow-sm">
                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <!-- FORMULARIO DE LOGIN -->
            <?php if (DASHBOARD_DEMO): ?>
            <form method="POST" action="index.php" class="mb-6">
                <?php echo dashboard_csrf_field(); ?>
                <p class="text-xs text-slate-400 leading-relaxed mb-3">Explore a versão de portfólio com dados fictícios.</p>
                <button type="submit" name="demo_submit" class="btn-dashboard">Explorar demonstração</button>
                <p class="text-[10px] text-slate-500 mt-3">Ou entre com demo@example.com e senha demo360.</p>
            </form>
            <?php endif; ?>
            <form id="form-login" method="POST" action="index.php" class="space-y-4 transition-all duration-500 block">
                <?php echo dashboard_csrf_field(); ?>
                <div>
                    <label class="dashboard-label">E-mail ou Matrícula</label>
                    <input type="text" name="identificador" required class="dashboard-field" placeholder="ex: DEMO000 ou demo@example.com" autocomplete="username">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="dashboard-label mb-0">Senha de Acesso</label>
                        <?php if (!DASHBOARD_DEMO): ?><a href="#" class="text-[8px] font-black text-slate-500 hover:text-indigo-400 uppercase tracking-widest transition-colors">Recuperar</a><?php endif; ?>
                    </div>
                    <input type="password" name="password" required class="dashboard-field" placeholder="••••••••" autocomplete="current-password">
                </div>

                <button type="submit" name="login_submit" class="btn-dashboard">
                    Acessar Dashboard
                </button>
                
                <div class="text-center mt-4">
                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">
                        Primeiro Acesso? 
                        <button type="button" onclick="showRegister()" class="text-indigo-400 hover:text-indigo-300 font-black ml-1 transition-colors">Criar Conta</button>
                    </p>
                </div>
            </form>

            <!-- FORMULARIO DE REGISTRO (Convite) -->
            <form id="form-register" method="POST" action="index.php" class="space-y-4 transition-all duration-500 hidden">
                <?php echo dashboard_csrf_field(); ?>
                
                <div class="p-4 bg-[#0f172a] border border-indigo-500/20 rounded-lg mb-6 shadow-sm">
                    <p class="text-indigo-400 text-[9px] font-black uppercase tracking-widest mb-1 flex items-center">
                        <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                        Token Necessário
                    </p>
                    <p class="text-slate-400 text-[10px] font-medium leading-relaxed">
                        Para se cadastrar, precisa de um <strong class="text-slate-300">Token de Convite</strong> fornecido pelo seu Coordenador.
                    </p>
                </div>

                <div>
                    <label class="dashboard-label">Token de Convite</label>
                    <input type="text" name="token_convite" required class="dashboard-field token-input" placeholder="EX: EQUIPE-DEMO">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="dashboard-label">Matrícula</label>
                        <input type="text" name="matricula" required class="dashboard-field" placeholder="1001">
                    </div>
                    <div>
                        <label class="dashboard-label">Cargo</label>
                        <div class="relative">
                            <select name="cargo" required class="dashboard-field appearance-none cursor-pointer">
                                <option value="operador">Operador(a)</option>
                                <option value="supervisor">Supervisor(a)</option>
                            </select>
                            <svg class="w-3 h-3 text-slate-500 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="dashboard-label">Nome Completo</label>
                    <input type="text" name="nome" required class="dashboard-field" placeholder="O seu nome">
                </div>

                <div>
                    <label class="dashboard-label">E-mail Corporativo</label>
                    <input type="email" name="email" required class="dashboard-field" placeholder="demo@example.com">
                </div>

                <div>
                    <label class="dashboard-label">Definir Senha</label>
                    <input type="password" name="password" required class="dashboard-field" placeholder="Mínimo 6 caracteres">
                </div>

                <button type="submit" name="register_submit" class="btn-dashboard">
                    Validar e Criar Conta
                </button>
                
                <div class="text-center mt-4">
                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">
                        Já tem uma conta? 
                        <button type="button" onclick="showLogin()" class="text-indigo-400 hover:text-indigo-300 font-black ml-1 transition-colors">Fazer Login</button>
                    </p>
                </div>
            </form>

            <div class="mt-10 pt-6 border-t border-slate-900/50 flex items-center justify-between">
                <div class="flex items-center">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-2 animate-pulse shadow-[0_0_10px_rgba(16,185,129,0.4)]"></span>
                    <span class="text-[8px] font-black text-slate-700 uppercase tracking-widest">Ligação Segura</span>
                </div>
                <p class="text-[8px] font-black text-slate-800 uppercase tracking-tighter italic opacity-30">Dashboard360 · Portfólio</p>
            </div>
        </div>
    </div>
</div>

<script>
    // Função para mostrar o registo e adicionar à History API
    function showRegister(pushHistory = true) {
        document.getElementById('form-login').classList.add('hidden');
        document.getElementById('form-register').classList.remove('hidden');
        if (pushHistory) {
            history.pushState({ form: 'register' }, '', '#cadastro');
        }
    }

    // Função para mostrar o login e adicionar à History API
    function showLogin(pushHistory = true) {
        document.getElementById('form-login').classList.remove('hidden');
        document.getElementById('form-register').classList.add('hidden');
        if (pushHistory) {
            history.pushState({ form: 'login' }, '', window.location.pathname + window.location.search);
        }
    }

    // Interceta o botão "Voltar" do navegador
    window.addEventListener('popstate', function(event) {
        if (window.location.hash === '#cadastro') {
            showRegister(false);
        } else {
            showLogin(false);
        }
    });

    // Lida com o carregamento inicial da página caso o PHP tenha pedido para mostrar o registo após erro
    document.addEventListener('DOMContentLoaded', function() {
        const phpWantsRegister = <?php echo $showRegister ? 'true' : 'false'; ?>;
        if (phpWantsRegister || window.location.hash === '#cadastro') {
            showRegister(false); 
        }
    });
</script>

</body>
</html>
