<?php return array(
  'name' => '超胜海量采集系统',
  'components' => array(
    'db' => array(
      'class' => 'CDbConnection',
      'connectionString' => 'mysql:host=localhost;dbname=chaosheng',
      'emulatePrepare' => true,
      'username' => 'root',
      'password' => '821225',
      'charset' => 'utf8',
      'schemaCachingDuration' => '3600',
      'enableProfiling' => true,
    ),
    /*
    'cache' => array(
      'class' => 'CFileCache',
    ),
     */
  ),
  'params' => array(
    'yiiPath' => 'framework/',
    'cyiiPath' => '../../../yii/framework/',
    'encryptionKey' => '6ad45ec3b4d92a23f8ae4156002047a2bf7d5c1a20917b53efba65aa64c75d0957602e46ec8056dbaf66eaaf2655a1e4ba4d289f382f58c000f90935',
    'debug' => true,
    'trace' => 3
  )

);
