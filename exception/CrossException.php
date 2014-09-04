<?php
/**
 * Cross - a micro PHP 5 framework
 *
 * @link        http://www.crossphp.com
 * @license     http://www.crossphp.com/license
 * @version     1.0.2
 */
namespace cross\exception;

use exception;
use SplFileObject;

/**
 * @Auth: wonli <wonli@live.com>
 * Class CrossException
 * @package cross\exception
 */
abstract class CrossException extends Exception
{
    function __construct($message = 'Crossphp Framework Exception', $code = null)
    {
        parent::__construct($message, $code);
        set_exception_handler(array($this, "errorHandler"));
    }

    /**
     * 提取错误文件源代码
     *
     * @param exception $e
     * @return array
     */
    function cpExceptionSource(exception $e)
    {
        $trace = $e->getTrace();

        $result = array();
        $result["main"] = array("file" => $e->getFile(), "line" => $e->getLine(), "message" => $e->getMessage());

        foreach ($result as &$_i) {
            $file = new SplFileObject($_i["file"]);

            foreach ($file as $line => $code) {
                if ($line < $_i["line"] + 6 && $line > $_i["line"] - 7) {
                    $h_string = highlight_string("<?php{$code}", true);
                    $_i["source"][$line] = str_replace("&lt;?php", "", $h_string);
                }
            }
        }

        if (!empty($trace)) {
            foreach ($trace as $tn => & $t) {
                if (isset($t['file'])) {
                    $trace_fileinfo = new SplFileObject($t["file"]);
                    foreach ($trace_fileinfo as $t_line => $t_code) {
                        if ($t_line < $t["line"] + 6 && $t_line > $t["line"] - 7) {
                            $h_string = highlight_string("<?php{$t_code}", true);
                            $t["source"][$t_line] = str_replace("&lt;?php", "", $h_string);
                        }
                    }
                    $result ['trace'] [$tn] = $t;
                }
            }
        }


        return $result;
    }

    /**
     * 错误处理抽象方法
     *
     * @param exception $e
     * @return mixed
     */
    abstract protected function errorHandler(exception $e);
}
