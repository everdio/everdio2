<?php
if ($this instanceof Component\Core\Controller\Model\Cli) {
    $this->echo("BaseX Curl " . $this->basex->host . \DIRECTORY_SEPARATOR . $this->request->database, ["bold", "white"]);
    $this->echo(" posting ", ["bold", "green"]);
    $this->echo(\sprintf("%s .. ", $this->request->command), ["bold", "yellow"]);

    $file = new \Component\Caller\File\Fopen(__DIR__ . \DIRECTORY_SEPARATOR . $this->request->command, "r");

    $curl = new \Component\Caller\Curl;
    $curl->setopt_array([
        \CURLOPT_HTTPAUTH => \CURLAUTH_BASIC,
        \CURLOPT_USERPWD => $this->basex->username . ":" . $this->basex->password,
        \CURLOPT_URL => $this->basex->host]);            
    $curl->post($this->request->replace($file->read(\filesize($file->file))));
    $curl->execute();
    $this->echo("done", ["green", "bold"]);
    $this->break();
}