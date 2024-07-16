<?php

namespace Blade;

use ErrorException;

class ViewErrorHandler
{
    public function __construct(protected Blade $blade)
    {
    }

    public function handler(
        int $errno,
        string $errstr,
        string $errfile,
        int $errline
    ) {

        /**
         * Clear the previous output
         * to avoid any interference
         * with the error message.
         */
        while (ob_get_level()) {
            ob_get_clean();
            // echo 'clear the output buffer';
        }

        // dump($errfile, $errline, $errstr, $errno);

        $file = $this->getBladeFile(file_get_contents($errfile));

        $file = $file ? $file : $errfile;

        $code = $this->getTemplateFileLines($file, $errline);

        echo $this->blade->render('error', [
            'stackTrace' => debug_backtrace(),
            'message' => $errstr,
            'file' => $file,
            'errorLine' => $errline,
            'lines' => $code
        ]);


        // throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);

        // throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }


    protected function getBladeFile(string $template): string
    {
        $matches = [];

        preg_match('/##PATH (.*?) ##/', $template, $matches);

        return $matches[1] ?? '';
    }

    public function shutdownHandler()
    {
        $error = error_get_last();

        $errline = $error['line'];
        $errfile = $error['file'];
        $errstr = $error['message'];


        if (str_starts_with($errfile, Blade::$cacheDir)) {

            $compiled = file_get_contents($errfile);
            $bladeFile = $this->getBladeFile($compiled);

            $lines = explode("\n", \file_get_contents($bladeFile));

            echo $this->outputError($errline, $lines, $bladeFile, $errstr);

            return true;
        }
    }

    protected function getTemplateFileLines(string $file, int $errorLine): array
    {
        $lines = array_slice(
            explode("\n", file_get_contents($file)),
            $errorLine - 5,
            10,
            true
        );

        return $lines;
        // return ;
    }
}
