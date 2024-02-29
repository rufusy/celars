<?php
/**
 * @author Rufusy Idachi
 * @email idachirufus@gmail.com
 * @create date 15-09-2021 19:54:15 
 * @modify date 15-09-2021 19:54:15 
 * @desc web app database configuration
 */

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=' . DATABASE_SERVER . ';port=' . DATABASE_PORT. ';dbname=' . DATABASE_NAME,
    'username' => DB_USER,
    'password' => DB_PASS,
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
