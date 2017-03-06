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
  $action = (isset($_GET['action']) && ($_GET['action'] =="move" OR $_GET['action'] == "remove")) ? $_GET['action'] : "";
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


// inizializzo una funzione remove per far fare il percorso inverso agli elementi
function remove($id){

  $src=$_SESSION['valigia'];
  $dest=$_SESSION['armadio'];
  if (isset($src[$id])){

  $dest[]=$src[$id];
  unset ($src[$id]);

  }

  $_SESSION['valigia']= $src ;
  $_SESSION['armadio'] = $dest;

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

	foreach($data as $id=>$item){


//inserisco qui il link per spostare l'elemento cambiando però move in remove!
		echo "<li>" . $item  . "<a href=\"?action=remove&id=$id\">Sposta</a></li>";

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
