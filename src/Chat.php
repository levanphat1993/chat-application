<?php

namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require dirname(__DIR__) . "/databases/ChatUser.php";
require dirname(__DIR__) . "/databases/ChatRooms.php";

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        $data = json_decode($msg, true);

        $user = new \ChatUser;
        $chat = new \ChatRooms;

        $chat->setUserId($data['userId']);
        $chat->setMessage($data['msg']);
        $chat->setCreatedOn(date('Y-m-d H:i:s'));
        $chat->save();

        $user->setId($data['userId']);
        $user_data = $user->getUserDatabyId();
        $user_name = $user_data['name'];
        $data['dt'] = date("d-m-Y h:i:s");


        foreach ($this->clients as $client) {
            // if ($from !== $client) {
            //     // The sender is not the receiver, send to each client connected
            //     $client->send($msg);
            // }


            if ($from == $client) {
                $data['from'] = 'Me';
            } else {
                $data['from'] = $user_name;
            }

            $client->send(json_encode($data));
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}