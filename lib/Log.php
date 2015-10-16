<?php

namespace PatrolSdk;

class Log {

    // @var string The log path
    private static $path = null;

    /**
     * @return string The path of the file, if no file is present, create one
     */
    private static function file() {
        $path = (is_null(self::$path)) ?
            dirname(__FILE__) . "/../log.txt" :
            self::$path;

        if (!file_exists($path)) {
            $fp = fopen($path, "wb");
            fwrite($fp, '');
            fclose($fp);
        }

        return $path;
    }

    /**
     * @param string $path
     */
    public static function setPath($path) {
        self::$path = $path;
    }

    /**
     * Log an info message in the file set as $path
     *
     * @param string $data
     */
    public static function info($data) {
        if (!Patrol::$enableLog) {
            return;
        }

        $print = print_r($data, true) . "\n";
        $file = self::file();

        file_put_contents($file, $print, FILE_APPEND);
    }

}
