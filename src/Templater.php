<?php

namespace Websix\Templater;

class Templater
{
    /**
     * Resource of subprocess
     */
    private $process;

    /**
     * Pipes to write/read from subprocess
     */
    private $pipes = [];

    /**
     * Defines if is to run with debug
     */
    private $isDebug = false;

    /**
     * Creates the child process
     *
     * @param string $template Path to the template
     */
    private function open($template)
    {
        $cmd = realpath(__DIR__ . '/../node/templater');
        $cmd = sprintf('%s -s - -t %s', $cmd, $template);

        if($this->isDebug) {
            $cmd .= ' -d';
        }

        $std = array(
            0 => array('pipe', 'r'),
            1 => array('pipe', 'w'),
            2 => array('pipe', 'w')
        );

        $this->process = proc_open($cmd, $std, $this->pipes);

        if(!is_resource($this->process)) {
            throw new \RuntimeException('Erro ao inciar o subprocesso');
        }

    }

    /**
     * Close the process
     */
    private function close()
    {
        if(is_resource($this->process)) {
           return proc_close($this->process);
        }
    }

    /**
     * Sends the JSON in format {"Name of the sheet": [{"A": "Value"}]} to the
     * subprocess throught the opened socket
     * @param  string $json JSON string in the upper format
     * @return string       if the file was created with success, returns it
     *                      path
     */
    public function compileJson($template, $json) {
        if(empty($json)) {
            throw new \InvalidArgumentException('$json não pode ser vazia');
        }

        if(!is_readable($template)) {
            throw new \InvalidArgumentException('$template deve poder ser lido');
        }

        $this->open($template);

        // Escreve o json
        fwrite($this->pipes[0], $json);
        fclose($this->pipes[0]);

        $result = stream_get_contents($this->pipes[1]);
        fclose($this->pipes[1]);
        $error  = stream_get_contents($this->pipes[2]);
        fclose($this->pipes[2]);

        $res = $this->close();

        if($res != 0) {
            throw new \RuntimeException($error);
        }

        return $result;

    }

    /**
     * Defines if the subprocess must run under debug mode
     *
     * @param bool $isDebug if is to run with debug or not
     */
    public function setIsDebug($isDebug = true)
    {
        $this->isDebug = is_bool($isDebug) and $isDebug;
    }
}