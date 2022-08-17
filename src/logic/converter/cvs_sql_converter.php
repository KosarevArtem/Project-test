<?php

namespace taskforce\logic\converter;

use DirectoryIterator;
use SplFileObject;
use SplFileInfo;
use taskforce\exceptions\converter_exception;

class cvs_sql_converter
{

    protected array $files_to_convert = [];

    /**
     * cvs_sql_converter constructor
     *
     * @param string $directory
     * @throws converter_exception
     */
    public function __construct(string $directory)
    {
        if (!is_dir($directory)) {
            throw new converter_exception('Указанная директория не найдена');
        }
        
        $this->load_csv_files($directory);
    }

    public function convert_files(string $output_directory): array
    {
        $result = [];

        foreach ($this->files_to_convert as $file) {
            $result[] = $this->convert_file($file, $output_directory);
        }

        return $result;
    }


    protected function convert_file(SplFileInfo $file, string $output_directory): string
    {
        $file_object = new SplFileObject($file->getRealPath());
        $file_object->setFlags(SplFileObject::READ_CSV);

        $colums = $file_object->fgetcsv();
        $values = [];

        while (!$file_object->eof()) {
            $values[] = $file_object->fgetcsv();
        }

        $table_name = $file->getBasename('.csv');
        $sql_content = $this->get_sql_content($table_name, $colums, $values);

        return $this->save_sql_content($table_name, $output_directory, $sql_content);
    }

    protected function get_sql_content(string $table_name, array $colums, array $values): string
    {
        $colums_string = implode(', ', $colums);
        $sql = "INSERT INTO $table_name ($colums_string) VALUES ";

        foreach ($values as $row) {
            array_walk($row, function (&$value) {
                $value = addslashes($value);
                $value = "'$value'";
            });

            $sql .= "( " . implode(', ', $row) . "), ";
        }

        $sql = substr($sql, 0, -2);

        return $sql;
    }

    protected function save_sql_content(string $table_name, string $directory, string $content): string
    {
        if (!is_dir($directory)) {
            throw new converter_exception('Директория для выходных файлов не существует');
        }

        $filename = $directory . DIRECTORY_SEPARATOR . $table_name . '.sql';
        file_put_contents($filename, $content);

        return $filename;
    }

    /**
     * get splFileInfo object
     * @param string $directory
     * @throws DirectoryIterator
     * @return void
     */
    protected function load_csv_files(string $directory)
    {
        foreach (new DirectoryIterator($directory) as $file) {
            if ($file->getExtension() == 'csv') {
                $this->files_to_convert[] = $file->getFileInfo();
            }
        }
    }
}