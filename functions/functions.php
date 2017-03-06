<?php



function boot()
{
         global $config;


	session_start();

	if( !isset($_SESSION['armadio'])){

		$_SESSION['armadio'] = create_armadio();

	}

	if( !isset($_SESSION['valigia'])){

		$_SESSION['valigia'] = create_valigia() ;

	}



  // parse input (VALIDARE INPUT E METTERE DEFAULT!!)
  $action = (isset($_GET['action']) && $_GET['action']) =="move" ? "move" : "";
  $id= (isset($_GET['id']) && is_numeric($_GET['id'])) ? $_GET['id'] : "0";

 return (["action"=>$action, "id"=> $id]);



}



function move($id)
{


// sposta l'elemento identificato dall'indice $id dall'array sorgente al destinatario

$src=$_SESSION['armadio'];
$dest=$_SESSION['valigia'];

// controlla che ci sia l'elemento origine
if (isset($src[$id])){

$dest[]=$src[$id];
unset ($src[$id]);

}

$_SESSION['armadio']= $src ;
$_SESSION['valigia'] = $dest;

}

function display()
{


	echo "armadio";
	$data=$_SESSION['armadio'];

	echo "<ul>";
	foreach($data as $id=>$item){

		echo "<li>" . $item  . " <a href=\"?action=move&id=$id\">Sposta</a> </li>";

	}

	echo "</ul>";


	echo "valigia";
	$data= $_SESSION['valigia'];

	echo "<ul>";

	foreach($data as $item){

		echo "<li>" . $item  . "</li>";

	}
	echo "</ul>";


}

function create_valigia()
{
global $config;

return $config['valigia'];


}

function create_armadio()
{
global $config;

return $config['armadio'];


}


function debug()
{

echo "<pre>";
print_r($_SESSION);


}

function before()
{ //se è settata la var reset  -> resetta sessione

  if (isset($_GET['reset'])) {
    
    session_destroy();
    boot();
  }




}
