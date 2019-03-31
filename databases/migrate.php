<?php

Defined("BASE_PATH") or die(ACCESS_DENIED);

class Migrate
{
    
    private $success = false;
    private static $directory = array(
        'procedure_view' => 'databases/dev/migrations/procedure_view/*',
        'seeder' => 'databases/dev/seeder/*',
        'table' => 'databases/dev/migrations/table.sql'
    );
    private $directory_migrate;
    private $sql = "";
    private $type;
    private $environment;
    private $filename;

    /**
     * Method __construct
     * First load when access the class
     */
    public function __construct() {
        $this->type = isset($_GET['type']) && !empty($_GET['type']) ? 
            $_GET['type'] : 'full';
        $this->environment = isset($_GET['environment']) && !empty($_GET['environment']) ? 
            $_GET['environment'] : 'dev';
        $filename = isset($_GET['filename']) && !empty($_GET['filename']) ? 
            $_GET['filename'] : false;

        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            echo "Migrate running...</br></br>";
            $this->run($this->type, $this->environment, $filename);
        }
        else {
            http_response_code(403);
            die(ACCESS_DENIED);
        }
    }

    /**
     * Method run
     * Running process migrate to get all snytax/code sql and merge all to one file 
     * @param {string} $type full || procedure || view
     * @param {string} $environment dev || prod
     * @param {string} $filename free up to you :D, but the default is $environment + _ + $filename + .sql
     */
    private function run($type, $environment, $filename) {
        $this->filename = $this->setFilename($filename);

        if($type !== 'full') { $this->getByType(); }
        else { $this->getFull(); }
    }

    /**
     * Method setFileName
     * Process set property filename
     * @param {string} $filename default is false
     * @return {string}
     */
    private function setFilename($filename) {
        return !$filename ? $this->environment.'_'.$this->type.'.sql' : 
                            $this->environment.'_'.$filename.'.sql';
    }

    /**
     * Method getByType
     * Process get all sql by type, the type is procedure or view
     */
    private function getByType() {
        $get_type = array_filter(glob(Migrate::$directory['procedure_view']), 'is_dir');

        foreach($get_type as $path_folder) {
            echo 'open folder '.$path_folder.'</br>';
            echo 'searching '.$this->type.'.sql</br>';
            $path_file = array_filter(glob($path_folder.'/*'), function($value) {
                $temp = explode('/', $value);
                return $temp[5] === $this->type.'.sql';
            });

            if(!empty($path_file)) {
                $file = explode('/', $path_file[0]);
                $file = array_key_exists(5, $file) ? $file[5] : false;

                if($file && (explode('.', $file)[1] !== 'html')) {
                    echo 'get all syntax</br>';
                    $filename = fopen($path_file[0], 'r') or die("Unable to open file!");
                    while(!feof($filename)) {
                        $this->sql .= fgets($filename);
                    }
                    $this->sql .= "\n\n";
                    fclose($filename);
                    echo 'success get all syntax</br>';
                }
                else { echo $this->type.'.sql not found!</br>'; }
            }
            else { echo $this->type.'.sql not found!</br>'; }

            echo '</br>';
        }

        $this->directory_migrate = ROOT.DS.'databases'.DS.$this->environment.DS.$this->filename;
        $file_migrate = fopen($this->directory_migrate, "wb") or die();
        fwrite($file_migrate, $this->sql);
        fclose($file_migrate);
        $this->success = true;
    }

    /**
     * Method getFull
     * Process get all sql. Get table, procedure, view, and seeder
     */
    private function getFull() {
        $checkTable = true;

        // get table.sql
        echo 'open and searching '.Migrate::$directory['table'].'</br>';
        if(file_exists(Migrate::$directory['table'])) {
            echo 'get all table.sql syntax</br>';
            $getTable = fopen(Migrate::$directory['table'], 'r') or die("Unable to open file!");
            while(!feof($getTable)) {
                $this->sql .= fgets($getTable);
            }
            $this->sql .= "\n\n";
            fclose($getTable);
            echo 'success get all syntax</br>';
            echo '</br>';
        }
        else { 
            echo 'table.sql not found!</br>';
            $checkTable = false; 
        }

        // get all procedure.sql, view.sql, and seeder
        if($checkTable) {
            // get procedure and view
            $getProcedureView = array_filter(glob(Migrate::$directory['procedure_view']), 'is_dir');

            foreach($getProcedureView as $path_folder) {
                echo 'open folder '.$path_folder.'</br>';
                echo 'searching procedure.sql and view.sql</br>';
                $path_file = glob($path_folder.'/*');

                if(!empty($path_file)) {
                    foreach($path_file as $file) {
                        $getFile = explode('/', $file);
                        $getFile = array_key_exists(5, $getFile) ? $getFile[5] : false;

                        if($getFile && (explode('.', $getFile)[1] !== 'html')) {
                            echo 'get all '.$getFile.' syntax</br>';
                            $filename = fopen($file, 'r') or die("Unable to open file!");
                            while(!feof($filename)) {
                                $this->sql .= fgets($filename);
                            }
                            $this->sql .= "\n\n";
                            fclose($filename);
                            echo 'success get all syntax</br>';
                        }
                    }
                }
                else { echo 'procedure.sql and view.sql not found!</br>'; }

                echo '</br>';
            }

            // get seeder
            $getSeeder = array_filter(glob(Migrate::$directory['seeder']), function($value) {
                $temp = explode('.', $value);
                return $temp[1] !== 'html';
            });

            if(!empty($getSeeder)) {
                // sorting seeder
                $newSeeder = array();
                foreach($getSeeder as $seeder) {
                    $tempPath = explode('/', $seeder);
                    $tempFile = explode('_', $tempPath[3]);
                    $newSeeder[(int)$tempFile[0]] = $seeder;
                }

                ksort($newSeeder, 1);
                foreach($newSeeder as $path_seeder) {
                    echo 'open file '.$path_seeder.'</br>';
                    $getFileSeeder = explode('/', $path_seeder);
                    $getFileSeeder = array_key_exists(3, $getFileSeeder) ? $getFileSeeder[3] : false;

                    if($getFileSeeder) {
                        echo 'get all '.$getFileSeeder.' syntax</br>';
                        $filenameSeeder = fopen($path_seeder, 'r') or die("Unable to open file!");
                        while(!feof($filenameSeeder)) {
                            $this->sql .= fgets($filenameSeeder);
                        }
                        $this->sql .= "\n\n";
                        fclose($filenameSeeder);
                        echo 'success get all syntax</br>';
                    }
                    echo '</br>';
                }
            }
            else { echo 'seeder not found!</br>'; }

            $this->directory_migrate = ROOT.DS.'databases'.DS.$this->environment.DS.$this->filename;
            $file_migrate = fopen($this->directory_migrate, "wb") or die();
            fwrite($file_migrate, $this->sql);
            fclose($file_migrate);
            $this->success = true;
        }
    }
    
    /**
     * Method __destruct
     * Process when finish access this class, and give information about the process migrate
     */
    public function __destruct(){
        if($this->success) {
            echo 'Finish migrate all file '.$this->type.'.sql to '.$this->directory_migrate;
        }
        else {
            echo 'Something wrong happen, please check your request..';
        }
    }
}