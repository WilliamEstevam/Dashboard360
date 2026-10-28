<?php
/**
 * Arquivo: index.php
 * Camada: App (Front Controller / Roteador)
 * Descrição: Entrada da versão de portfólio, autenticação e seleção de módulos.
 */

require_once dirname(__FILE__) . '/shared/config/app.php';
require_once dirname(__FILE__) . '/shared/config/helpers.php';
dashboard_secure_session_start();
dashboard_security_headers();

$GLOBALS['error'] = '';
$GLOBALS['success'] = '';
$GLOBALS['show_register'] = false;
if (DASHBOARD_DEMO) {
    require_once dirname(__FILE__) . '/shared/demo/bootstrap.php';
    dashboard_demo_auth();
} 

if (!DASHBOARD_DEMO && isset($_POST['register_submit'])) {
    require_once 'shared/config/database.php';

    if (!dashboard_csrf_validate()) {
        $GLOBALS['error'] = 'Token de segurança inválido. Recarregue a página e tente novamente.';
        $GLOBALS['show_register'] = true;
    } else {

    $nome      = trim($_POST['nome']);
    $email     = trim($_POST['email']);
    $matricula = trim($_POST['matricula']);
    $password  = trim($_POST['password']);
    $token     = trim($_POST['token_convite']);
    $cargo     = trim($_POST['cargo']);

    $stmtToken = odbc_prepare($db_conn,
        "SELECT id, nome_completo FROM portfolio.usuarios WHERE token_convite = ?");
    $resToken = odbc_execute($stmtToken, array($token));

    if ($resToken && odbc_fetch_row($stmtToken)) {
        $liderNome = odbc_result($stmtToken, 'nome_completo');

        $stmtCheck = odbc_prepare($db_conn,
            "SELECT id FROM portfolio.usuarios WHERE email = ? OR matricula = ?");
        $resCheck = odbc_execute($stmtCheck, array($email, $matricula));

        if ($resCheck && odbc_fetch_row($stmtCheck)) {
            $GLOBALS['error']         = 'Já existe uma conta com este E-mail ou Matrícula.';
            $GLOBALS['show_register'] = true;
        } else {
            $novoSalt = dashboard_gerar_salt();
            $novoHash = dashboard_hash($novoSalt, $password);

            $stmtInsert = odbc_prepare($db_conn,
                "INSERT INTO portfolio.usuarios
                    (email, nome_completo, senha, senha_hash, salt, cargo, matricula, precisa_mudar_senha)
                 VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
            $resInsert = odbc_execute($stmtInsert,
                array($email, $nome, '', $novoHash, $novoSalt, $cargo, $matricula));

            if ($resInsert) {
                $GLOBALS['success'] = 'Conta criada com sucesso! Validada por: <b>'
                                    . h($liderNome) . '</b>. Já pode fazer login.';
            } else {
                $GLOBALS['error']         = log_erro('Register INSERT', odbc_errormsg($db_conn));
                $GLOBALS['show_register'] = true;
            }
        }
    } else {
        $GLOBALS['error']         = 'Token de Convite inválido ou não reconhecido. Solicite o token correto à sua Liderança.';
        $GLOBALS['show_register'] = true;
    }
    } // fecha o else do CSRF
}

if (!DASHBOARD_DEMO && isset($_POST['login_submit'])) {

    if (!dashboard_csrf_validate()) {
        $GLOBALS['error'] = 'Token de segurança inválido. Recarregue a página e tente novamente.';
    } else {

    $identificador = trim($_POST['identificador']);
    $password      = trim($_POST['password']);

    require_once 'shared/config/database.php';

    $stmtLogin = odbc_prepare($db_conn,
        "SELECT id, email, nome_completo, senha, senha_hash, salt, cargo,
                precisa_mudar_senha, foto_path, matricula
         FROM portfolio.usuarios
         WHERE email = ? OR matricula = ?");
    $resLogin = odbc_execute($stmtLogin, array($identificador, $identificador));

    if ($resLogin && odbc_fetch_row($stmtLogin)) {
        $pass_db   = trim(odbc_result($stmtLogin, 'senha'));
        $hash_db   = trim(odbc_result($stmtLogin, 'senha_hash'));
        $salt_db   = trim(odbc_result($stmtLogin, 'salt'));
        $userId_db = (int)odbc_result($stmtLogin, 'id');

        $autenticado = false;

        if (!empty($salt_db) && !empty($hash_db)) {
            $autenticado = dashboard_verify($password, $hash_db, $salt_db);
        } else {
            if ($password === $pass_db) {
                $autenticado = true;
                $novoSalt = dashboard_gerar_salt();
                $novoHash = dashboard_hash($novoSalt, $password);
                $stmtMig  = odbc_prepare($db_conn,
                    "UPDATE portfolio.usuarios
                     SET salt = ?, senha_hash = ?, senha = ''
                     WHERE id = ?");
                odbc_execute($stmtMig, array($novoSalt, $novoHash, $userId_db));
            }
        }

        if ($autenticado) {
            $cargoUser = trim(odbc_result($stmtLogin, 'cargo'));
            $matUser   = trim(odbc_result($stmtLogin, 'matricula'));
            $nomeDb    = utf8_encode(odbc_result($stmtLogin, 'nome_completo'));
            $nomeOficial = $nomeDb;

            if (strtolower($cargoUser) === 'supervisor' && !empty($matUser)) {
                $stmtNome = odbc_prepare($db_conn,
                    "SELECT TOP 1 supervisor FROM portfolio.estado_atual
                     WHERE mat_supervisor = ?");
                $resNome = odbc_execute($stmtNome, array($matUser));
                if ($resNome && odbc_fetch_row($stmtNome)) {
                    $nomeEncontrado = utf8_encode(trim(odbc_result($stmtNome, 'supervisor')));
                    if (!empty($nomeEncontrado)) {
                        $nomeOficial = $nomeEncontrado;
                    }
                }
            }

            session_regenerate_id(true);

            $_SESSION['user_logado'] = true;
            $_SESSION['user'] = array(
                'id'           => $userId_db,
                'nome'         => $nomeDb,
                'nome_oficial' => $nomeOficial,
                'cargo'        => $cargoUser,
                'foto'         => trim(odbc_result($stmtLogin, 'foto_path')),
                'matricula'    => $matUser,
                'mudar_senha'  => (int)odbc_result($stmtLogin, 'precisa_mudar_senha')
            );

            header('Location: index.php?route=dashboard');
            exit;

        } else {
            $GLOBALS['error'] = 'Senha incorreta. Tente novamente.';
        }
    } else {
        $GLOBALS['error'] = 'Não encontrámos nenhum utilizador com este E-mail ou Matrícula.';
    }
    } // fecha o else do CSRF
}

if (isset($_GET['route']) && $_GET['route'] == 'logout') {
    dashboard_secure_logout();
    header("Location: index.php");
    exit;
}

$route = isset($_GET['route']) ? $_GET['route'] : 'login';

if (isset($_SESSION['user_logado']) && $route === 'login') {
    $route = 'dashboard';
}

if (!isset($_SESSION['user_logado']) && $route !== 'login') {
    $route = 'login';
}

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
}

switch ($route) {
    case 'dashboard':
        if (file_exists('pages/dashboard/index.php')) require_once 'pages/dashboard/index.php'; break;
    case 'tu':
        if (file_exists('pages/tu/index.php')) require_once 'pages/tu/index.php'; break;
    case 'cubo_tu': 
        if (file_exists('pages/cubo_tu/index.php')) require_once 'pages/cubo_tu/index.php'; break;
    case 'banheiro':
        if (file_exists('pages/banheiro/index.php')) require_once 'pages/banheiro/index.php'; break;
    case 'pausas':
        if (file_exists('pages/pausas/index.php')) require_once 'pages/pausas/index.php'; break;
    case 'metas':
        if (file_exists('pages/metas/index.php')) require_once 'pages/metas/index.php'; break;
    case 'comunidade':
        if (file_exists('pages/comunidade/index.php')) require_once 'pages/comunidade/index.php'; break;
    case 'usuarios':
        if (file_exists('pages/usuarios/index.php')) require_once 'pages/usuarios/index.php'; break;
    case 'ajustes_pausa':
        if (file_exists('pages/ajustes_pausa/index.php')) require_once 'pages/ajustes_pausa/index.php'; break;
    case 'links_uteis':
        if (file_exists('pages/links_uteis/index.php')) require_once 'pages/links_uteis/index.php'; break;
    case 'perfil':
        if (file_exists('pages/perfil/index.php')) require_once 'pages/perfil/index.php'; break;
    case 'tempo_logado':
        if (file_exists('pages/tempo_logado/index.php')) require_once 'pages/tempo_logado/index.php'; break;
    case 'pausas_consolidadas': 
        if (file_exists('pages/pausas_consolidadas/index.php')) require_once 'pages/pausas_consolidadas/index.php'; break;
    case 'banco_horas': 
        if (file_exists('pages/banco_horas/index.php')) require_once 'pages/banco_horas/index.php'; break;
    case 'absenteismo':
        if (file_exists('pages/absenteismo/index.php')) require_once 'pages/absenteismo/index.php'; break;
    case 'login':
    default:
        if (file_exists('pages/auth/login.php')) require_once 'pages/auth/login.php'; break;
}
?>