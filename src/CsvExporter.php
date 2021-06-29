<?php

/**
 * CsvExporter
 *
 * @author    XihongKe <xihongke@foxmail.com>
 * @copyright 2021 XihongKe
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      https://github.com/XihongKe/CsvExporter
 */

namespace XihongKe\CsvExporter;


class CsvExporter
{
    protected $out;

    // Flush the output buffer after $bufferLimit lines
    protected $bufferLimit = 10000;
    protected $rowsCount = 1;

    /**
     * CsvExporter constructor.
     * @param string $filename Filename without extension
     * @param array $header
     * @param int $memoryLimit Maximum memory usage
     */
    public function __construct($filename, $header = [], $memoryLimit = 3072)
    {
        ini_set('memory_limit', "{$memoryLimit}M");
        set_time_limit(0);
        $filename .= '-' . date('Ymd-His') . '.csv';
        header("Content-Disposition: attachment;filename={$filename}");
        header('Content-Type: text/csv');
        header("Content-Encoding: binary");
        header("Pragma: no-cache");
        header("Expires: 0");

        $this->out = fopen('php://output', 'wb');

        if ($header) {
            fputcsv($this->out, $this->convertEncoding($header));
        }
    }

    /**
     * Write some rows
     * @param array $rows
     */
    public function rows($rows)
    {
        foreach ($rows as $row) {
            $this->row($row);
        }
    }

    /**
     * Write a row
     * @param array|string $row
     */
    public function row($row)
    {
        fputcsv($this->out, $this->convertEncoding($row));
        $this->checkOutputBuffer(1);
    }

    /**
     * Flush the output buffer after $bufferLimit lines
     * @param int $count
     */
    protected function checkOutputBuffer($count)
    {
        $this->rowsCount += $count;
        if ($this->rowsCount >= $this->bufferLimit) {
            $this->rowsCount = 1;
            ob_flush();
            flush();
        }
    }

    /**
     * Covert encoding,Support the string and array format
     * @param string|string[] $data
     * @param string $toEncoding
     * @param string $fromEncoding
     * @return string|string[]
     */
    protected function convertEncoding($data, $toEncoding = 'GBK', $fromEncoding = 'UTF-8')
    {
        if (is_string($data)) {
            return mb_convert_encoding($data, $toEncoding, $fromEncoding);
        }

        if (is_array($data)) {
            if (!empty($data)){
                foreach ($data as $k => $v){
                    $data[$k] = $this->convertEncoding($v, $toEncoding, $fromEncoding);
                }
            }
            return $data;
        }
        return $data;
    }


    public function __destruct()
    {
        fclose($this->out);
    }
}