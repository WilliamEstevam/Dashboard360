<?php
if (!defined('DASHBOARD_DEMO')) { header('HTTP/1.1 404 Not Found'); exit; }
$currentView = 'links_uteis';
include 'shared/ui/header.php';

$links = array(
    array('titulo' => 'Indicadores', 'url' => 'index.php?route=dashboard', 'desc' => 'Visão consolidada dos indicadores demonstrativos.', 'icon' => '📊', 'cor' => 'bg-indigo-600'),
    array('titulo' => 'Atendimentos', 'url' => 'index.php?route=tu', 'desc' => 'Performance e detalhes de atendimentos fictícios.', 'icon' => '📋', 'cor' => 'bg-blue-600'),
    array('titulo' => 'Monitor WFM', 'url' => 'index.php?route=pausas', 'desc' => 'Cenários demonstrativos de acompanhamento de pausas.', 'icon' => '⏱️', 'cor' => 'bg-emerald-600'),
    array('titulo' => 'Metas', 'url' => 'index.php?route=metas', 'desc' => 'Acompanhamento de metas e tendências sintéticas.', 'icon' => '🎯', 'cor' => 'bg-amber-600'),
    array('titulo' => 'Comunidade', 'url' => 'index.php?route=comunidade', 'desc' => 'Mural de comunicação com conteúdo de exemplo.', 'icon' => '💬', 'cor' => 'bg-rose-600'),
    array('titulo' => 'Autor', 'url' => 'https://github.com/WilliamEstevam', 'desc' => 'Conheça o perfil do desenvolvedor no GitHub.', 'icon' => '💻', 'cor' => 'bg-gradient-to-br from-indigo-600 to-cyan-500')
);
?>

<div class="p-10 max-w-[1600px] mx-auto animate-in fade-in duration-700">
    <!-- Header -->
    <div class="mb-12">
        <h2 class="text-3xl font-black italic tracking-tighter text-slate-800 dark:text-white uppercase">Links <span class="text-indigo-600 dark:text-indigo-400">Úteis</span></h2>
        <p class="text-slate-400 dark:text-slate-500 text-sm font-medium mt-1 uppercase tracking-widest italic text-xs">Atalhos da plataforma e perfil do autor</p>
    </div>

    <!-- Grid de Links -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
        <?php foreach ($links as $l): ?>
            <!-- CORREÇÃO: Substituímos a tag <a> por uma <div> com onclick nativo para forçar a abertura da aba -->
            <div onclick="window.open('<?php echo $l['url']; ?>', '_blank', 'noopener,noreferrer')" 
                 class="cursor-pointer bg-white dark:bg-[#0f172a] rounded-[2.5rem] p-10 border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-xl dark:hover:shadow-indigo-500/10 transition-all duration-500 group relative overflow-hidden flex flex-col justify-between h-[300px]">
                
                <!-- Ícone Flutuante de Fundo -->
                <div class="absolute -right-8 -top-8 text-8xl opacity-[0.03] dark:opacity-[0.05] group-hover:scale-110 transition-transform duration-700 pointer-events-none filter grayscale group-hover:grayscale-0">
                    <?php echo $l['icon']; ?>
                </div>

                <div class="relative z-10 pointer-events-none">
                    <div class="w-16 h-16 rounded-2xl <?php echo $l['cor']; ?> text-white flex items-center justify-center text-3xl shadow-lg mb-8 group-hover:scale-110 transition-transform">
                        <?php echo $l['icon']; ?>
                    </div>
                    
                    <h3 class="text-xl font-black text-slate-800 dark:text-white uppercase italic tracking-tighter mb-4"><?php echo $l['titulo']; ?></h3>
                    <p class="text-slate-400 dark:text-slate-500 text-sm font-medium leading-relaxed max-w-[240px]">
                        <?php echo $l['desc']; ?>
                    </p>
                </div>

                <div class="pt-6 border-t border-slate-50 dark:border-slate-800 flex items-center justify-between text-[10px] font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400 opacity-0 group-hover:opacity-100 transition-opacity relative z-10 pointer-events-none">
                    <span>Aceder agora</span>
                    <svg class="w-5 h-5 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4-4m4 4H3"></path></svg>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Informativo -->
    <div class="mt-16 p-10 bg-indigo-50/50 dark:bg-indigo-500/10 rounded-[3rem] border border-indigo-100/50 dark:border-indigo-500/20 flex items-center space-x-6 transition-colors duration-500">
        <div class="w-12 h-12 bg-white dark:bg-[#0f172a] rounded-2xl flex items-center justify-center text-indigo-600 dark:text-indigo-400 shadow-sm flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <p class="text-[10px] font-bold text-indigo-500 dark:text-indigo-400 uppercase tracking-widest leading-relaxed">
            Os atalhos desta versão usam módulos demonstrativos. Todos os indicadores e registros apresentados são fictícios.
        </p>
    </div>
</div>

<?php include 'shared/ui/footer.php'; ?>