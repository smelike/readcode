<?php

    class WS {
    
        var $master;
        vasr $sockets = array(); // 不同状态的 socket 管理
        var $handshake = false; // 判断是否握手
        
        public function __construct($address, $port)
        {
            $this->master = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("socket_create() failed");
            
            socket_set_option($this->master, SOL_SOCKET, SO_REUSEADDR, 1) or die("socket_set_option() failed");
            
            socket_bind($this->master, $address, $port) or die("socket_bind() failed");
            
            socket_listen($this->master, 2) or die("socket_listen() failed");
            
            $this->sockets[] = $this->master;
            
            while (True) {
                $write = null;
                $except = null;
                
                socket_select($this->sockets, $write, $except, null);
                
                foreach($this->sockets as $socket) {
                    if ($socket == $this->master) {
                        $client = socket_accept($this->master);
                        if ($client < 0) {
                            continue;
                        } else {
                            array_push($this->sockets, $client)
;
                        }
                    } else {
                        $bytes = @socket_recv($socket, $buffer, 2048, 0);
                        
                        if ($bytes == 0) return;
                        if ($this->handshake) {
                            $buffer = decode($buffer);
                        }
                    }
                }
            }
        }
    }
