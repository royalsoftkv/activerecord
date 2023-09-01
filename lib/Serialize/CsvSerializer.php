<?php

namespace ActiveRecord\Serialize;

/**
 * CSV serializer.
 */
class CsvSerializer extends Serialization
{
    public static string $delimiter = ',';
    public static string $enclosure = '"';

    public function to_s(): string
    {
        if (true == @$this->options['only_header']) {
            return $this->header();
        }

        return $this->row();
    }

    private function header(): string
    {
        return $this->to_csv(array_keys($this->to_a()));
    }

    private function row(): string
    {
        return $this->to_csv($this->to_a());
    }

    /**
     * @param array<mixed> $arr
     */
    private function to_csv(array $arr): string
    {
        $outstream = fopen('php://temp', 'w');
        fputcsv($outstream, $arr, self::$delimiter, self::$enclosure);
        rewind($outstream);
        $buffer = trim(stream_get_contents($outstream));
        fclose($outstream);

        return $buffer;
    }
}
