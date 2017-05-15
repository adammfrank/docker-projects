<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

sleep(10000)
$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();
$channel->queue_declare('task_queue', false, true, false, false);


$data = implode(' ', array_slice($argv, 1));
if(empty($data)) $data = "Hello, World!";
//Message won't be lost if worker shuts down after receiving. Message will be requeued and redelivered
$msg = new AMQPMessage($data, array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));

$channel->basic_publish($msg, '', 'task_queue');

echo " [x] Sent", $data, "\n";

$channel->close();
$connection->close();
?>
