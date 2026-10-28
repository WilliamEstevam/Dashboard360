<?php
/** Dados criados do zero para o portfólio. Nenhum registro foi extraído da operação. */
function dashboard_demo_user() {
    return array('id' => 1, 'nome' => 'Visitante Demo', 'nome_oficial' => 'Visitante Demo',
        'cargo' => 'administrador', 'email' => 'demo@example.com', 'foto' => '',
        'matricula' => 'DEMO000', 'mudar_senha' => 0);
}

function dashboard_demo_auth() {
    if (isset($_POST['demo_submit']) || isset($_POST['login_submit'])) {
        if (!dashboard_csrf_validate()) {
            $GLOBALS['error'] = 'Sessão expirada. Recarregue a página e tente novamente.';
            return;
        }
        if (isset($_POST['login_submit']) &&
            (!isset($_POST['identificador'], $_POST['password']) ||
            !in_array($_POST['identificador'], array('demo@example.com', 'DEMO000')) ||
            $_POST['password'] !== 'demo360')) {
            $GLOBALS['error'] = 'Use demo@example.com e a senha demo360 ou clique em Explorar demonstração.';
            return;
        }
        session_regenerate_id(true);
        $_SESSION['user_logado'] = true;
        $_SESSION['user'] = dashboard_demo_user();
        header('Location: index.php?route=dashboard');
        exit;
    }
    if (isset($_POST['register_submit'])) {
        $GLOBALS['error'] = 'Na demonstração, use o acesso de visitante. Não é necessário cadastrar dados pessoais.';
    }
}

function dashboard_demo_people() {
    $people = array();
    $totals = array(120, 110, 100, 90, 80, 100);
    $retained = array(90, 77, 65, 54, 48, 74);
    $canceled = array(12, 11, 15, 9, 16, 8);
    $logged = array(100, 98, 92, 85, 78, 65);
    $paid = array(100, 99, 95, 90, 82, 70);
    for ($i = 0; $i < 6; $i++) {
        $people[] = array('matricula' => 'DEMO' . sprintf('%03d', $i + 1),
            'nome' => 'Operador Demo ' . sprintf('%02d', $i + 1),
            'supervisor' => $i < 3 ? 'Supervisão Demo A' : 'Supervisão Demo B',
            'coordenador' => 'Coordenação Demo 1', 'gerente' => 'Gestão Demo 1',
            'atendidas' => $totals[$i], 'retido' => $retained[$i], 'cancelados' => $canceled[$i],
            'logado' => $logged[$i], 'pago' => $paid[$i]);
    }
    return $people;
}

function dashboard_demo_date($key, $default) {
    return isset($_GET[$key]) && validar_data($_GET[$key]) ? $_GET[$key] : $default;
}

function dashboard_demo_text($key, $default) {
    return isset($_GET[$key]) && is_string($_GET[$key]) ? $_GET[$key] : $default;
}

function dashboard_demo_filtered($people, $coord, $super, $manager) {
    $result = array();
    foreach ($people as $person) {
        if ($coord !== '' && $person['coordenador'] !== $coord) continue;
        if ($super !== '' && $person['supervisor'] !== $super) continue;
        if ($manager !== '' && $person['gerente'] !== $manager) continue;
        $result[] = $person;
    }
    return $result;
}

function dashboard_demo_csv($filename, $headings, $rows) {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
    $stream = fopen('php://output', 'w');
    fwrite($stream, "\xEF\xBB\xBF");
    fputcsv($stream, $headings, ';', '"', '');
    foreach ($rows as $row) fputcsv($stream, $row, ';', '"', '');
    fclose($stream);
    exit;
}

function dashboard_demo_rank($a, $b) {
    if ($a['retido'] === $b['retido']) return 0;
    return $a['retido'] > $b['retido'] ? -1 : 1;
}
