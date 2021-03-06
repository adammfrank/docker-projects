<?php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;


$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();


// true parameter makes queue "durable" --> it will persist messages if server shutsdown
$channel->queue_declare('task_queue', false, true, false, false);

echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

$callback = function($msg){
  echo " [x] Received ", $msg->body, "\n";
  sleep(substr_count($msg->body, '.'));
  echo " [x] Done", "\n";
  $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
};

// The 1 means the worker will not receive more than 1 message at a time
$channel->basic_qos(null, 1, null);

// 4th false turns on ack
$channel->basic_consume('task_queue', '', false,false,false,false,$callback);

while(count($channel->callbacks)) {
    $channel->wait();
}

?>
