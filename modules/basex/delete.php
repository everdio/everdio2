<?php

if ($this instanceof Component\Core\Controller\Model\Cli) {
    $curl = new \Component\Caller\Curl;
    $curl->setopt_array([
        \CURLOPT_HTTPAUTH => \CURLAUTH_BASIC,
        \CURLOPT_USERPWD => $this->basex->username . ":" . $this->basex->password,
        \CURLOPT_URL => $this->basex->host . \DIRECTORY_SEPARATOR . $this->request->database . \DIRECTORY_SEPARATOR . $this->request->resource]);
    $curl->delete();
    $curl->execute();
}