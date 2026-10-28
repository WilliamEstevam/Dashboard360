<?php
// O modo público funciona sem banco. Copiar este arquivo é opcional.
define('DASHBOARD_DEMO', true);

// Apenas para uma adaptação própria da integração ODBC (não fornecida pronta).
// Para ativá-la, mude DASHBOARD_DEMO para false e prepare seu próprio banco.
define('DB_SERVER', 'localhost,1433');
define('DB_USER', 'SEU_USUARIO');
define('DB_PASS', 'SUA_SENHA');
define('DB_NAME', 'SEU_BANCO');
define('DASHBOARD_PEPPER', 'CONFIGURE_UM_SEGREDO_PROPRIO');
