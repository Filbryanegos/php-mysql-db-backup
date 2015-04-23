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
$backupPath = $config['backup.path'];

/**
 * The foldername to be created
 */
$foldername = $config['servername'] . '-' . $config['host'] . '-' . date('M-d-Y-H-i-s-A');

/**
 * Path to put all the dump sql files
 */
$path = $backupPath . DIRECTORY_SEPARATOR . $foldername;
mkdir($path);

/**
 * Create a connector to MySQL Database
 */
$connector = mysql_connect($host, $username, $password);

/**
 * Grab all the databases from the connector
 */
$databases = mysql_list_dbs($connector);

/**
 * List of excluded database that are not needed to backup
 */
$excluded = array('information_schema', 'mysql', 'phpmyadmin', 'performance_schema');

/**
 * Itirate
 */
while ($database = mysql_fetch_object($databases)) {
	$db = NULL;
	if(isset($database->Database)) {
		$db = $database->Database;
	} else {
		$db = $database['Database'];
	}

	/**
	 * Remove the system database that not needed
	 */
	if(in_array($db, $excluded)) continue;

	/**
	 * Shell command
	 */
	$command = 'mysqldump -u' . $username . ' -p' . $password . ' -h' . $host . ' ' . $db . ' > ' . $path . DIRECTORY_SEPARATOR . $db . '.sql';

	/**
	 * Execute the command
	 */
	$result = shell_exec($command);

	/**
	 * Display the result and command
	 */
	echo $command; echo "\n"; print_r($result); echo "\n";
}