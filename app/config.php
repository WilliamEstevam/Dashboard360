<?php
date_default_timezone_set('America/Sao_Paulo');
// A cópia pública inicia sempre com dados sintéticos, sem conexão externa.
$dashboardLocalConfig = dirname(dirname(__FILE__)) . '/config.local.php';
if (file_exists($dashboardLocalConfig)) {
    require_once $dashboardLocalConfig;
}
if (!defined('DASHBOARD_DEMO')) {
    define('DASHBOARD_DEMO', true);
}
