<?php

class ChildThread extends Thread {
    public $data;

    public function run() {
      echo "Thread 2";

      $this->data = 'result';
    }
}

$thread = new ChildThread();

if ($thread->start()) {
    echo "Thread 1";
    $thread->join();

    // we can now even access $thread->data
}

?>