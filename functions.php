<?php 

function applybck(){
		
		$dir="../backup";
	 	$fitxer=$dir."/".$_SESSION["applybackup"];
		$sql=file_get_contents($fitxer);
		$querys=explode(";", "$sql");
		
		/*foreach ($querys as $query ) {
			$query.=";";
			echo $query."<br/>";
		}*/
                $query = qsql($sql);
		$res=doBackup($querys,$conexion);
		
		if($res == "ok"){
			$_SESSION['success']="DataBase restored correctly";
		}else{
			$_SESSION['success']="DataBase doesn't restored";
		}
		header("location: ../admin_backup.php");
	}
	
function creabackup(){
// 	include("./config.php");
// 	$conexion=connect();
// 	$sql="";
// 	$sql.="DROP DATABASE IF EXISTS $db;";
// 	$sql.="CREATE DATABASE IF NOT EXISTS $db;";	
// 	$sql.="USE $db;\n";
// 	$tbnoms=array();
// 	$tbnoms=tbList();
	
	
// 	foreach ($tbnoms as $tb) {
		
// 		$sql.="DROP TABLE IF EXISTS $tb;\n ";
// 		$sql.=showCreateTable($conexion, $tb);
// 		$sql.=";\n";
// 		$inserts=array();
// 		$inserts=showInserts($conexion, $tb);
// 		$camps=pillacampos($tb);
		
		
		
// 		foreach ($inserts as $insert) {
// 			$sql.=" INSERT INTO $tb ("; 
// 			foreach ($camps as $camp ) {
// 				$sql.=$camp[0].",";
// 			}
// 			$sql=substr($sql, 0,-1);
// 			$sql.=") VALUES (";
// 			foreach ($insert as $valor) {
// 				if (!is_numeric($valor)){
// 					$sql.='"'.$valor.'",';
// 				}else{
// 					$sql.=''.$valor.',';
// 				}
// 			}
// 			$sql=substr($sql, 0,-1);
// 			$sql.="); \n";
// 		}
		
// 	}
	include ('config.php');
	$comando = "mysqldump -p --user=$user --password=$password --host=$host --add-drop-table $db &gt; $temp$dbName.sql";
	$retorno = ejecutar($comando);
	return $sql;
}

function deletebackup(){
	$dir="../backup";
	$fitxer=$_SESSION["removebackup"];
	$opendir=opendir($dir) or die("El directori no s'ha obert.");
	unlink("$dir/$fitxer")or die("El fitxer $fitxer no s'ha pogut eliminar");
	closedir($opendir);
	header("location: ../admin_backup.php");
}
function hazbackup($res){
	
//  	$dir="../backup";
	
// // 	//obrim el directori per llegir i llistar:
// 	$opendir=opendir($dir) or die("El directori no s'ha obert.");
// 	//Obrim directori
// 	echo "dir obert<br/>";
	
// 	//Tanquem directori
	
// 	closedir($opendir);
// 	$dia=date('dmY');
// 	$hora=date('His');
// 	$datos= "db";
// 	$datos.="_$dia";
// 	$datos.="_$hora";
// 	$fitxerbackup="../backup/$datos.sql";
// 	//obrim el fitxer de contador per lectura i escriptura
// 	$opencont = fopen($fitxerbackup, "w") or die("El fitxer no s'ha obert.");
// 	fwrite($opencont, $res);
// 	fclose($opencont);
// 	$conexion=connect();
// 	$_SESSION["error"]="El backup s'ha realitzat correctament amb el nom de $fitxerbackup";
// 	header("location: ../admin_backup.php");
// 	$conexion->close();

// 	return $sql;

	include 'config.php';
		$dia=date('dmY');
		$hora=date('His');
		$datos= "db";
		$datos.="_$dia";
		$datos.="_$hora";
	$dir="../backup";
	$ruta = $dir.'/'.$datos.'.sql';
	//si vamos a comprimirlo	
	
	//comando
	$command = "mysqldump --opt -h ".$host." ".$db." -u ".$user." -p".$password.
	" -r \"".$ruta."\" 2>&1";
	 
	//ejecutamos
	system($command);
	$_SESSION['success']="DataBase backup executed correctly";
}

function hazbackup_datos(){
	$ruta_relativa = '../../'; //establece la ruta relativa al directorio desde el que partir para los directorios listados abajo
	$directorio[] = $ruta_relativa;
	$dir="../backup";
	$dia=date('dmY');
	$hora=date('His');
	$datos= "datos";
	$datos.="_$dia";
	$datos.="_$hora";
	if ( !empty($directorio) && is_array($directorio) )
	{
		$directorios_ok = '';
		foreach ( $directorio as $dire )
		{
			if ( !empty($dire) && file_exists($dire) )
			{
				$directorios_ok .= $dire.' ';
			}
		}

		$ruta_con_nombre = $dir."/".$datos.'.tar.gz';
		$comando = 'tar czf '.$ruta_con_nombre." $directorios_ok";
		system($comando);
		$_SESSION['success']="Data backup executed correctly";
	}
	
}

//
function ejecutar($comando)
{
	//shell_exec($comando, $salida);
	exec($comando, $salida);
	//passthru($comando, $salida);
	//system($comando, $salida);
	return $salida;
}

function downbku($id){
// $dir = "../backup/";
// $file = $enlace;
// $path = $dir.$file;
// $type = '';
 
// if (is_file($path)) {
//  $size = filesize($path);
//  if (function_exists('mime_content_type')) {
//  $type = mime_content_type($path);
//  } else if (function_exists('finfo_file')) {
//  $info = finfo_open(FILEINFO_MIME);
//  $type = finfo_file($info, $path);
//  finfo_close($info);
//  }
//  if ($type == '') {
//  $type = "application/force-download";
//  }
//  // Definir headers
//  header("Content-Type: $type");
//  header("Content-Disposition: attachment; filename=$file");
//  header("Content-Transfer-Encoding: binary");
//  header("Content-Length: " . $size);
//  // Descargar archivo
//  readfile($path);
// } else {
//  die("El archivo no existe.");
// }
	$enlace = "../backup/".$id;
	header ("Content-Disposition: attachment; filename=".$id." ");
	header ("Content-Type: application/octet-stream");
	header ("Content-Length: ".filesize($enlace));
	readfile($enlace);
} 

//##########################################################3
function showInserts($conexion,$tb){
	$inserts=array();//me creo un array para ir guardando los inserts que vaia leiendo.

	$consulta=$conexion->query("SELECT * FROM $tb");

	if ($consulta){
		while ($linea=$consulta->fetch_array(MYSQLI_NUM)){
			$inserts[]=$linea;
				
		}
		//$linea=$consulta->fetch_array(MYSQLI_NUM);
		//$inserts[]=$linea;
	}else{
		echo $conexion->error;
	}
	return $inserts;
}

function showCreateTable($conexion, $table){
	$consulta=$conexion->query("show create table $table");
	$res=array();
	if ($consulta){

		while ($linea=$consulta->fetch_array(MYSQLI_NUM)){
			$res[]=$linea;
		}

	}

	return $res[0][1];
}
function doBackup($querys,$conexion){
	$x=0;
	while ($x < count($querys)-1) {
		$consulta=$conexion->query($querys[$x]);
		if ($consulta){
			$res="ok";
		}else{
			$res=$conexion->error;
		}
		$x++;
	}

	return $res;

}
function tbList(){
	include("./config.php");
	$conexion = connect();
	$consulta=$conexion->query("SHOW TABLES FROM $db");
	$tbls=array();
	if ($consulta){
		while ($linea=$consulta->fetch_array(MYSQLI_NUM)){
			$tbls[]=$linea[0];
				
		}
	}else{
		echo $conexion->error;
	}
	return $tbls;
}
function pillacampos($taula){
	$conexion=connect();
	//cojo toda la sentencia SQL que ha generado la tabla
	$consulta=$conexion->query("describe $taula");
	//$consulta=$conexion->query("SELECT COLUMN_NAME, DATA_TYPE, COLUMN_DEFAULT, COLUMN_KEY, CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$taula';");
	$infocamps=array();
	if ($consulta){
		while ($camp=$consulta->fetch_array(MYSQLI_NUM)){
			$infocamps[]=$camp;
		}
	}
	$conexion->close();
	return $infocamps;
}

function pepe($a,$b){
    return $a+$b;
}
?>
