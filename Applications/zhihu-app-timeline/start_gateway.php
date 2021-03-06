<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

use \Workerman\Worker;
use \Workerman\WebServer;
use \GatewayWorker\Gateway;
use \GatewayWorker\BusinessWorker;
use \Workerman\Autoloader;

$config = require('config.php');

$context = array(
    'ssl' => array(
        'local_cert'  => $config['ssl']['local_cert'],
        'local_pk'    => $config['ssl']['local_pk'],
        'verify_peer' => false,
    )
);

$gateway = new Gateway("websocket://0.0.0.0:11130", $context);

$gateway->transport = 'ssl';

$gateway->name = 'zhihu-app-timeline-gateway';

$gateway->count = 2;

$gateway->lanIp = '127.0.0.1';

$gateway->startPort = 11120;

$gateway->registerAddress = '127.0.0.1:11110';

$gateway->pingInterval = 10;

$gateway->pingData = '{"type":"ping"}';

$gateway->pingNotResponseLimit = 0;


/* 
// 当客户端连接上来时，设置连接的onWebSocketConnect，即在websocket握手时的回调
$gateway->onConnect = function($connection)
{
    $connection->onWebSocketConnect = function($connection , $http_header)
    {
        // 可以在这里判断连接来源是否合法，不合法就关掉连接
        // $_SERVER['HTTP_ORIGIN']标识来自哪个站点的页面发起的websocket链接
        if($_SERVER['HTTP_ORIGIN'] != 'http://kedou.workerman.net')
        {
            $connection->close();
        }
        // onWebSocketConnect 里面$_GET $_SERVER是可用的
        // var_dump($_GET, $_SERVER);
    };
}; 
*/

if (!defined('GLOBAL_START')) {
    Worker::runAll();
}

