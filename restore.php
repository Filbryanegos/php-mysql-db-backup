<?php
/**
 * Released Under MIT License
 * 
 * @author Erson Puyos <erson.puyos@gmail.com>
 * 
 * Permission is hereby granted, free of charge, to any person obtaining 
 * a copy of this software and associated documentation files (the "Software"), 
 * to deal in the Software without restriction, including without limitation 
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, 
 * and/or sell copies of the Software, and to permit persons to 
 * whom the Software is furnished to do so, subject to the following conditions: 
 * 
 * The above copyright notice and this permission notice shall be 
 * included in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,  
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT 
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, 
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR 
 * THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
/**
 * Load the default configuration file
 */
$config = require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'config.php');

$username = $config['username'];
$password = $config['password'];
$host = $config['host'];
$backup_path = $config['backup_path'];

/**
 * Scan the backup path that will be our basis for restoring the databases
 */
$dump = scandir($backup_path);

foreach ($dump as $dump_key => $dump_value) {
	/**
	 * Load only for those .sql file extensions
	 */
	if(strpos($dump_value, '.sql') !== false) {
		/**
		 * Commands for restore the database that has been detected from the backup path
		 */
		$database = str_replace('.sql', '', $dump_value);
		$message = 'Restore database ' . $database . ' from source file ' . $backup_path . DIRECTORY_SEPARATOR . $dump_value;
		echo $message; echo "\n";

		$command = "mysql --user='$username' --password='$password' --host='$host' --execute='CREATE DATABASE $database';";
		$respone = shell_exec($command);
		echo $command; echo "\n";

		$command = "mysql --user='$username' --password='$password' --host='$host' $database < " . $backup_path . DIRECTORY_SEPARATOR . $dump_value;
		$respone = shell_exec($command);
		echo $command; echo "\n"; echo "\n";
	}
}