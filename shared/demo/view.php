<?php
// Variáveis para os mesmos templates visuais, usando somente dados sintéticos.
$successMsg = '';
$errorMsg = '';
$people = dashboard_demo_people();
$ultimaData = date('Y-m-d', strtotime('-1 day'));
$hoje = date('Y-m-d');
$dataVisualD1 = date('d/m/Y', strtotime($ultimaData));
$filtroDataIni = dashboard_demo_date('data_ini', $ultimaData);
$filtroDataFim = dashboard_demo_date('data_fim', $ultimaData);
$filtroData = dashboard_demo_date('data_ref', $ultimaData);
$filtroCoord = dashboard_demo_text('coordenador', '');
$filtroSuper = dashboard_demo_text('supervisor', '');
$filtroGerente = dashboard_demo_text('gerente', '');
$userCargo = $user['cargo'];
$userNomeOficial = $user['nome'];
$isAdmin = $userCargo === 'administrador';
$isGerente = $userCargo === 'gerente';
$isCoordenador = $userCargo === 'coordenador';
$isSupervisor = $userCargo === 'supervisor';
$isGestao = in_array($userCargo, array('administrador', 'gerente', 'coordenador'));
$listaCoords = array('Coordenação Demo 1');
$listaSupers = array('Supervisão Demo A', 'Supervisão Demo B');
$listaGerentes = array('Gestão Demo 1');
$mapSupCoordView = array('Supervisão Demo A' => 'Coordenação Demo 1', 'Supervisão Demo B' => 'Coordenação Demo 1');
if ($currentView === 'banheiro') {
    $filtroCoord = isset($_POST['coord_banheiro']) ? $_POST['coord_banheiro'] : $filtroCoord;
    $filtroSuper = isset($_POST['super_banheiro']) ? $_POST['super_banheiro'] : $filtroSuper;
}
$filteredPeople = dashboard_demo_filtered($people, $filtroCoord, $filtroSuper, $filtroGerente);
if ($filtroData !== $ultimaData || $filtroDataIni > $ultimaData || $filtroDataFim < $ultimaData) {
    if (in_array($currentView, array('tu', 'cubo_tu', 'tempo_logado', 'pausas_consolidadas', 'absenteismo'))) $filteredPeople = array();
}
$limits = array('limite_refeicao' => 60, 'limite_banheiro' => 10, 'limite_lanche' => 20, 'limite_descanso' => 10);
if (isset($_SESSION['demo_limits'])) $limits = $_SESSION['demo_limits'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $currentView !== 'banheiro') {
    if (!dashboard_csrf_validate()) {
        $errorMsg = 'Token inválido. Recarregue a página e tente novamente.';
    } else {
        switch ($currentView) {
            case 'ajustes_pausa':
                $key = isset($_POST['meta_chave']) ? $_POST['meta_chave'] : '';
                $value = isset($_POST['meta_valor']) ? (int)$_POST['meta_valor'] : 0;
                if (isset($limits[$key]) && $value > 0 && $value <= 1440) {
                    $limits[$key] = $value;
                    $_SESSION['demo_limits'] = $limits;
                    $successMsg = 'Limite atualizado nesta sessão de demonstração.';
                } else $errorMsg = 'Informe um limite válido entre 1 e 1440 minutos.';
                break;
            case 'pausas_consolidadas':
                $key = (isset($_POST['data_ref_agente']) ? $_POST['data_ref_agente'] : '') . '_' .
                    (isset($_POST['mat_agente']) ? $_POST['mat_agente'] : '') . '_' .
                    (isset($_POST['atividade_agente']) ? $_POST['atividade_agente'] : '');
                $reason = isset($_POST['motivo']) ? trim($_POST['motivo']) : '';
                if ($reason !== '') {
                    $_SESSION['demo_justifications'][$key] = array('motivo' => $reason,
                        'obs' => isset($_POST['observacao']) ? trim($_POST['observacao']) : '',
                        'autor' => $user['nome'], 'data' => date('d/m/Y H:i'));
                    $successMsg = 'Justificativa salva nesta sessão de demonstração.';
                } else $errorMsg = 'Selecione um motivo para justificar.';
                break;
            case 'banco_horas':
                if (isset($_POST['data_queima'], $_POST['matricula_agente']) && validar_data($_POST['data_queima'])) {
                    $_SESSION['demo_schedules'][$_POST['matricula_agente']] = $_POST['data_queima'];
                    $successMsg = 'Agendamento salvo nesta sessão de demonstração.';
                } else $errorMsg = 'Informe uma data válida.';
                break;
            case 'comunidade':
                $message = isset($_POST['conteudo']) ? trim($_POST['conteudo']) : '';
                if ($message !== '') {
                    $_SESSION['demo_posts'][] = array('id' => time(), 'autor_nome' => $user['nome'],
                        'autor_cargo' => $user['cargo'], 'autor_foto' => '', 'conteudo' => $message,
                        'tipo' => isset($_POST['tipo']) ? $_POST['tipo'] : 'informativo', 'likes' => 0,
                        'dislikes' => 0, 'data' => date('Y-m-d H:i:s'), 'imagem_path' => '');
                    $successMsg = 'Comunicado salvo nesta sessão. Arquivos não são armazenados na demonstração.';
                } else $errorMsg = 'Escreva um comunicado.';
                break;
            case 'usuarios':
                if (isset($_POST['nome'], $_POST['email'], $_POST['matricula'], $_POST['cargo']) &&
                    trim($_POST['nome']) !== '' && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $_SESSION['demo_users'][] = array('id' => time(), 'nome' => trim($_POST['nome']),
                        'email' => trim($_POST['email']), 'matricula' => trim($_POST['matricula']),
                        'cargo' => $_POST['cargo'], 'foto' => '');
                    $successMsg = 'Colaborador de exemplo adicionado apenas nesta sessão. A senha não é armazenada.';
                } else $errorMsg = 'Preencha os campos com dados de exemplo válidos.';
                break;
            case 'perfil':
                if (!isset($_POST['senha_atual']) || $_POST['senha_atual'] !== 'demo360') {
                    $errorMsg = 'A senha da demonstração é demo360.';
                } elseif (!isset($_POST['nome'], $_POST['email']) || trim($_POST['nome']) === '' || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $errorMsg = 'Preencha nome e e-mail de exemplo válidos.';
                } elseif (!empty($_POST['nova_senha'])) {
                    $errorMsg = 'A credencial pública demo360 permanece fixa na demonstração.';
                } else {
                    $_SESSION['user']['nome'] = trim($_POST['nome']);
                    $_SESSION['user']['email'] = trim($_POST['email']);
                    $user = $_SESSION['user'];
                    $successMsg = 'Informações atualizadas nesta sessão. Fotos não são armazenadas na demonstração.';
                }
                break;
        }
    }
}

$kpiTotal = 0;
$retidos = 0;
$cancelados = 0;
$ranking = array();
foreach ($filteredPeople as $person) {
    $kpiTotal += $person['atendidas'];
    $retidos += $person['retido'];
    $cancelados += $person['cancelados'];
    $rank = $person;
    $rank['operador'] = $person['nome'];
    $rank['pct_total_retencao'] = round(100 * $person['retido'] / $person['atendidas'], 1);
    $ranking[] = $rank;
}
usort($ranking, 'dashboard_demo_rank');
$kpiRetencao = $kpiTotal ? round(100 * $retidos / $kpiTotal, 1) : 0;
$kpiCancelamento = $kpiTotal ? round(100 * $cancelados / $kpiTotal, 1) : 0;
$topUF = 'SP';
$chartLabels = array();
$chartRetencao = array();
$chartMeta = array();
$historicoDias = array();
$dailyPercentages = array(59, 63, 58, 71, 66, 69, 61, 73, 64, 67, 70, 62, 65, 64, $kpiRetencao);
for ($i = 0; $i < 15; $i++) {
    $day = date('Y-m-d', strtotime('-' . (14 - $i) . ' days', strtotime($ultimaData)));
    $total = $i === 14 ? $kpiTotal : 560 + (($i * 37) % 180);
    $chartLabels[] = date('d/m', strtotime($day));
    $chartRetencao[] = $dailyPercentages[$i];
    $chartMeta[] = $currentView === 'dashboard' ? 65 : 60;
    $historicoDias[] = array('data' => date('d/m/Y', strtotime($day)), 'total' => $total,
        'retencao' => $dailyPercentages[$i], 'cancelamento' => $i === 14 ? $kpiCancelamento : 8 + ($i % 7));
}
$historicoDias = array_reverse($historicoDias);
$chartN4Keys = array('Retido', 'Cancelado', 'Orientação', 'Outros');
$other = $kpiTotal - $retidos - $cancelados;
$chartN4Values = array($retidos, $cancelados, (int)floor($other * .6), $other - (int)floor($other * .6));
$topOperadores = array();
foreach (array_slice($ranking, 0, 4) as $rank) {
    $topOperadores[] = array('nome' => $rank['nome'], 'supervisor' => $rank['supervisor'], 'score' => $rank['retido']);
}
$insights = array(
    array('tipo' => 'success', 'titulo' => 'Cenário demonstrativo', 'texto' => 'Os indicadores desta versão são gerados com dados sintéticos.'),
    array('tipo' => 'success', 'titulo' => 'Visão centralizada', 'texto' => 'Atendimentos, retenção e cancelamento reunidos em uma única consulta.')
);
$metaRetencao = 60;
$metaCancelamento = 20;
$mesTotal = 0;
foreach ($historicoDias as $day) $mesTotal += $day['total'];
$mesRetencao = $kpiRetencao;
$mesCancelamento = $kpiCancelamento;
$mesesPt = array('01'=>'Janeiro','02'=>'Fevereiro','03'=>'Março','04'=>'Abril','05'=>'Maio','06'=>'Junho','07'=>'Julho','08'=>'Agosto','09'=>'Setembro','10'=>'Outubro','11'=>'Novembro','12'=>'Dezembro');
$mesDisplay = $mesesPt[date('m', strtotime($ultimaData))] . ' de ' . date('Y', strtotime($ultimaData));

switch ($currentView) {
    case 'tu':
        $modalMatricula = dashboard_demo_text('modal_mat', '');
        $modalNome = '';
        $detalhesModal = array();
        foreach ($filteredPeople as $person) {
            if ($person['matricula'] !== $modalMatricula) continue;
            $modalNome = $person['nome'];
            for ($i = 0; $i < $person['atendidas']; $i++) {
                $status = $i < $person['retido'] ? 'RETIDO' : ($i < $person['retido'] + $person['cancelados'] ? 'CANCELADO' : 'ORIENTAÇÃO');
                $detalhesModal[] = array('data' => date('d/m/Y', strtotime($ultimaData)),
                    'n2' => 'ATENDIMENTO DEMONSTRATIVO', 'n4' => $status,
                    'id_atendimento' => 'DEMO-' . $person['matricula'] . '-' . sprintf('%04d', $i + 1));
            }
        }
        if (isset($_GET['export'])) {
            $rows = array();
            foreach ($ranking as $rank) $rows[] = array($rank['matricula'], $rank['nome'], $rank['supervisor'], $rank['atendidas'], $rank['retido'], $rank['cancelados']);
            dashboard_demo_csv('dashboard360-atendimentos-demo', array('Matrícula demo', 'Operador', 'Supervisor', 'Atendidas', 'Retidos', 'Cancelados'), $rows);
        }
        break;
    case 'cubo_tu':
        $tabela = array(); $kpiOficial = 0; $kpiTu = 0; $kpiGap = 0; $kpiAlertas = 0;
        foreach ($filteredPeople as $i => $person) {
            $gap = $i % 3 === 0 ? 0 : 5 + $i;
            $oficial = $person['atendidas'] + $gap;
            $status = $gap === 0 ? 'Ok' : ($gap > 8 ? 'Crítico' : 'Atenção');
            $tabela[] = array('data' => date('d/m/Y', strtotime($ultimaData)), 'matricula' => $person['matricula'],
                'operador' => $person['nome'], 'supervisor' => $person['supervisor'],
                'oficial' => $oficial, 'tu' => $person['atendidas'], 'gap' => $gap, 'status' => $status);
            $kpiOficial += $oficial; $kpiTu += $person['atendidas']; $kpiGap += $gap;
            if ($gap) $kpiAlertas++;
        }
        $aderenciaPct = $kpiOficial ? round(100 * $kpiTu / $kpiOficial, 1) : 0;
        if (isset($_GET['export'])) {
            $rows = array(); foreach ($tabela as $row) $rows[] = array_values($row);
            dashboard_demo_csv('dashboard360-aderencia-demo', array('Data','Matrícula','Operador','Supervisor','Oficial','TU','Diferença','Status'), $rows);
        }
        break;
    case 'pausas':
        $isBotOnline = !isset($_GET['cenario']) || $_GET['cenario'] !== 'offline';
        $lastUpdateStr = $isBotOnline ? date('H:i:s') : date('H:i:s', strtotime('-10 minutes'));
        $limitesPausa = array('LANCHE' => $limits['limite_lanche'], 'BANHEIRO' => $limits['limite_banheiro'],
            'DESCANSO' => $limits['limite_descanso'], 'REFEICAO' => $limits['limite_refeicao'], 'FEEDBACK' => 30);
        $pausasData = array(); $kpisPausas = array(); $seconds = 0; $exceeded = 0;
        $statuses = array('LANCHE', 'BANHEIRO', 'DESCANSO', 'REFEICAO', 'FEEDBACK', 'BANHEIRO');
        $durations = array(1500, 700, 300, 1200, 600, 960);
        if ($isBotOnline) foreach ($people as $i => $person) {
            $status = $statuses[$i]; $duration = $durations[$i];
            $isExceeded = $duration >= $limitesPausa[$status] * 60;
            $pausasData[] = array('matricula' => $person['matricula'], 'nome' => $person['nome'],
                'supervisor' => $person['supervisor'], 'status' => $status,
                'tempo' => gmdate('H:i:s', $duration), 'estourou' => $isExceeded, 'foto' => '');
            if (!isset($kpisPausas[$status])) $kpisPausas[$status] = 0;
            $kpisPausas[$status]++; $seconds += $duration;
            if ($isExceeded) $exceeded++;
        }
        $resumoPausas = array('total' => count($pausasData), 'excedidas' => $exceeded, 'tempo_total' => gmdate('H:i:s', $seconds));
        break;
    case 'banheiro':
        $limiteMinutos = $limits['limite_banheiro']; $limiteSegundos = $limiteMinutos * 60;
        $dados = array(); $seconds = 0; $alerts = 0;
        foreach (array_slice($filteredPeople, 0, 3) as $i => $person) {
            $duration = 400 + $i * 330;
            $isExceeded = $duration >= $limiteSegundos;
            $dados[] = array('data' => date('d/m/Y'), 'matricula' => $person['matricula'], 'agente' => $person['nome'],
                'supervisor' => $person['supervisor'], 'estado' => 'Operação Demo', 'uf' => 'SP',
                'segundos' => $duration, 'tempo_fmt' => gmdate('H:i:s', $duration),
                'status_ref' => $isExceeded ? 'FORA DO NORMAL' : 'DENTRO DO NORMAL', 'foto' => '');
            $seconds += $duration; if ($isExceeded) $alerts++;
        }
        $kpis = array('quantidade' => count($dados), 'tempo_total' => gmdate('H:i:s', $seconds), 'alertas' => $alerts);
        break;
    case 'tempo_logado':
        $dadosAgentes = $filteredPeople; $totalOperadores = count($dadosAgentes);
        $sumLogado = 0; $sumPago = 0;
        foreach ($dadosAgentes as $person) { $sumLogado += $person['logado']; $sumPago += $person['pago']; }
        $mediaLogado = $totalOperadores ? round($sumLogado / $totalOperadores, 1) : 0;
        $mediaPago = $totalOperadores ? round($sumPago / $totalOperadores, 1) : 0;
        break;
    case 'pausas_consolidadas':
        $viewModeDefault = dashboard_demo_text('visao', 'supervisor');
        $listaTiposPausa = array('LANCHE', 'BANHEIRO', 'DESCANSO', 'REFEICAO');
        $filtroTipoPausa = dashboard_demo_text('tipo_pausa', '');
        $filtroStatus = dashboard_demo_text('status_pausa', 'TODAS');
        $filtroJust = dashboard_demo_text('justificativa', 'TODAS');
        $pausasConsolidadas = array();
        $kpis = array('total_pausas' => 0, 'tempo_total_segundos' => 0, 'estouros_totais' => 0, 'pendentes' => 0, 'justificadas' => 0);
        foreach ($filteredPeople as $i => $person) {
            $activity = $listaTiposPausa[$i % 4];
            if ($filtroTipoPausa !== '' && $filtroTipoPausa !== $activity) continue;
            $limit = $activity === 'BANHEIRO' ? $limits['limite_banheiro'] : ($activity === 'LANCHE' ? $limits['limite_lanche'] : ($activity === 'DESCANSO' ? $limits['limite_descanso'] : $limits['limite_refeicao']));
            $duration = $limit * 60 + ($i % 3 === 2 ? -120 : 300 + $i * 40);
            $excess = max(0, $duration - $limit * 60);
            $key = $filtroData . '_' . $person['matricula'] . '_' . $activity;
            $just = isset($_SESSION['demo_justifications'][$key]) ? $_SESSION['demo_justifications'][$key] : null;
            $kpis['total_pausas']++; $kpis['tempo_total_segundos'] += $duration;
            if ($excess) { $kpis['estouros_totais']++; if ($just) $kpis['justificadas']++; else $kpis['pendentes']++; }
            if ($filtroStatus === 'EXCEDIDAS' && !$excess) continue;
            if ($filtroStatus === 'NORMAL' && $excess) continue;
            if ($filtroJust === 'PENDENTES' && (!$excess || $just)) continue;
            if ($filtroJust === 'JUSTIFICADAS' && (!$excess || !$just)) continue;
            $pausasConsolidadas[] = array('matricula' => $person['matricula'], 'nome' => $person['nome'],
                'supervisor' => $person['supervisor'], 'atividade' => $activity, 'segundos' => $duration,
                'limite_segundos' => $limit * 60, 'is_estouro' => $excess > 0, 'excesso_segundos' => $excess, 'justificativa' => $just);
        }
        $tempoTotalFmt = sprintf('%02dh %02dm', floor($kpis['tempo_total_segundos'] / 3600), floor(($kpis['tempo_total_segundos'] % 3600) / 60));
        break;
    case 'ajustes_pausa':
        $limitesPausa = array();
        $labels = array('limite_refeicao' => 'Refeição', 'limite_banheiro' => 'Banheiro', 'limite_lanche' => 'Lanche', 'limite_descanso' => 'Descanso');
        $icons = array('limite_refeicao' => '🍽️', 'limite_banheiro' => '🚽', 'limite_lanche' => '🥪', 'limite_descanso' => '💤');
        foreach ($limits as $key => $value) $limitesPausa[$key] = array('label' => $labels[$key], 'icon' => $icons[$key], 'valor' => $value);
        break;
    case 'banco_horas':
        $ultimaDataImport = $ultimaData; $dadosTabela = array();
        $kpiHorasTotais = 0; $kpiHorasAtivos = 0; $kpiHorasInativos = 0; $qtdAtivos = 0; $qtdInativos = 0;
        foreach ($filteredPeople as $i => $person) {
            $hours = 12 - $i; $active = $i < 4;
            $row = $person; $row['coord'] = $person['coordenador']; $row['obs'] = $active ? 'ATIVO' : 'DESLIGADO';
            $row['is_active'] = $active; $row['tipo_saldo'] = $i === 5 ? 'NEG' : 'POS';
            $row['tempo_str'] = $hours . 'h 30m'; $row['limite_m2'] = '30/11/2026'; $row['horas_sort'] = $hours + .5;
            $row['data_queima'] = isset($_SESSION['demo_schedules'][$person['matricula']]) ? $_SESSION['demo_schedules'][$person['matricula']] : '';
            $dadosTabela[] = $row; $kpiHorasTotais += $hours + .5;
            if ($active) { $kpiHorasAtivos += $hours + .5; $qtdAtivos++; } else { $kpiHorasInativos += $hours + .5; $qtdInativos++; }
        }
        break;
    case 'absenteismo':
        $dadosGerais = array(); $kpiZerolog = 0; $kpiOfensores = 0; $kpiJustificados = 0;
        foreach (array_slice($filteredPeople, 0, 3) as $i => $person) {
            $row = $person; $row['categoria'] = $i === 0 ? 'ofensor' : ($i === 1 ? 'zerolog' : 'justificado');
            $row['historico_dias'] = array(date('d/m/Y', strtotime($ultimaData)));
            if ($i === 0) { $row['historico_dias'][] = date('d/m/Y', strtotime('-2 days')); $row['historico_dias'][] = date('d/m/Y', strtotime('-3 days')); $kpiOfensores++; }
            elseif ($i === 1) $kpiZerolog++; else $kpiJustificados++;
            $row['dias_falta'] = count($row['historico_dias']); $row['status_atual'] = 'DESLOGADO';
            $row['status_planejado'] = $i === 2 ? 'ATESTADO' : 'DISPONÍVEL'; $dadosGerais[] = $row;
        }
        if (isset($_GET['export_advanced'])) {
            $rows = array();
            foreach ($dadosGerais as $row) {
                if ($row['categoria'] === 'zerolog' && !isset($_GET['exp_zerolog'])) continue;
                if ($row['categoria'] === 'ofensor' && !isset($_GET['exp_ofensores'])) continue;
                if ($row['categoria'] === 'justificado' && !isset($_GET['exp_justificados'])) continue;
                $rows[] = array($row['matricula'], $row['nome'], $row['supervisor'], $row['categoria'], $row['dias_falta']);
            }
            dashboard_demo_csv('dashboard360-absenteismo-demo', array('Matrícula','Operador','Supervisor','Categoria','Dias'), $rows);
        }
        break;
    case 'comunidade':
        $usuarioAtual = $user;
        $listaPosts = array(array('id' => 1, 'autor_nome' => 'Equipe Demo', 'autor_cargo' => 'Coordenação',
            'autor_foto' => '', 'conteudo' => 'Bem-vindo ao Dashboard360! Este mural usa exclusivamente conteúdo fictício para apresentar a comunicação da equipe.',
            'tipo' => 'informativo', 'likes' => 8, 'dislikes' => 0, 'data' => $ultimaData . ' 09:00:00', 'imagem_path' => ''));
        if (isset($_SESSION['demo_posts'])) $listaPosts = array_merge(array_reverse($_SESSION['demo_posts']), $listaPosts);
        break;
    case 'usuarios':
        $listaUsuarios = array();
        foreach ($people as $i => $person) $listaUsuarios[] = array('id' => $i + 2, 'nome' => $person['nome'],
            'matricula' => $person['matricula'], 'email' => 'operador' . ($i + 1) . '@example.com', 'cargo' => 'operador', 'foto' => '');
        if (isset($_SESSION['demo_users'])) $listaUsuarios = array_merge($listaUsuarios, $_SESSION['demo_users']);
        break;
    case 'perfil':
        $dadosAtuais = array('nome' => $user['nome'], 'email' => $user['email'], 'cargo' => $user['cargo'],
            'mat' => $user['matricula'], 'foto' => '', 'senha' => '', 'hash' => '', 'salt' => '');
        break;
}
include 'shared/ui/header.php';
