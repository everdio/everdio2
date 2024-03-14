<?php
if ($this instanceof Component\Core\Controller\Model\Cli) {
    $this->echo("BaseX Curl " . $this->request->basex->host . \DIRECTORY_SEPARATOR . $this->request->basex->database, ["bold", "white"]);
    $this->echo(" posting ", ["bold", "green"]);
    $this->echo(\sprintf("%s .. ", $this->request->basex->command), ["bold", "yellow"]);

    $file = new \Component\Caller\File\Fopen(__DIR__ . \DIRECTORY_SEPARATOR . $this->request->basex->command, "r");

    $curl = new \Component\Caller\Curl;
    $curl->setopt_array([
        \CURLOPT_HTTPAUTH => \CURLAUTH_BASIC,
        \CURLOPT_USERPWD => $this->request->basex->username . ":" . $this->request->basex->password,
        \CURLOPT_URL => $this->request->basex->host]);            
    $curl->post($this->request->basex->replace($file->read(\filesize($file->file))));
    $curl->execute();
    $this->echo("done", ["green", "bold"]);
    $this->break();
}