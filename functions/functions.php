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
  $id= isset($_GET['id'])  ? trim($_GET['id']) : "-1";
//$out=(isset($_GET['out'])  ? "1" : "-1";
 return (["action"=>$action, "id"=> $id]);



}



function move($id)
{
// sposta l'elemento identificato dall'indice $id dall'array sorgente al destinatario

$src=$_SESSION['armadio'];
$dest=$_SESSION['valigia'];

$max_vol = get_max_vol();

// $max=get_max();

if(is_valigia_full($id, $dest, $max_vol)){
  return ("la valigia è piena");
}

// controlla che ci sia l'elemento origine
if (isset($src[$id])){

  //se non esiste l'elemento nell'array di destinazione lo creo e lo metto a qta 1
  if (!isset($dest[$id])){
    $dest[$id]=1;
  }else{
    $dest[$id]++;
  }

  //adesso decremento l'elemento che ho spostato

  $src[$id]--;

  //se ho finito gli elementi cancello il valore
  if ($src[$id]==0){
    unset($src[$id]);
  }
}

$_SESSION['armadio']= $src ;
$_SESSION['valigia'] = $dest;

}


// inizializzo una funzione remove per far fare il percorso inverso agli elementi
function remove($id){

  $src=$_SESSION['valigia'];
  $dest=$_SESSION['armadio'];


  // controlla che ci sia l'elemento origine
  if (isset($src[$id])){

    //se non esiste l'elemento nell'array di destinazione lo creo e lo metto a qta 1
    if (!isset($dest[$id])){
      $dest[$id]=1;
    }else{
      $dest[$id]++;
    }

    //adesso decremento l'elemento che ho spostato

    $src[$id]--;

    //se ho finito gli elementi cancello il valore
    if ($src[$id]==0){
      unset($src[$id]);
    }


  }

  $_SESSION['valigia']= $src ;
  $_SESSION['armadio'] = $dest;

}






function display()
{

  $clothes=get_abiti();

	echo "armadio";
	$data=$_SESSION['armadio'];

	echo "<ul>";
	foreach($data as $abito=>$qta){

		echo "<li>" . $clothes[$abito]['name'] . " [$qta] <a href=\"?action=move&id=$abito\">Sposta</a> </li>";

	}

	echo "</ul>";


	echo "valigia";
	$data= $_SESSION['valigia'];

	echo "<ul>";

  $count = 0;
	foreach($data as $abito=>$qta){


//inserisco qui il link per spostare l'elemento cambiando però move in remove!
		echo "<li> $abito  $qta <a href=\"?action=remove&id=$abito\">Sposta</a></li>";

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

function get_max(){
  global $config;
  return $config['max'];
}

function get_size($data){
  $tot=0;
  foreach ($data as $qta){
    $tot+=$qta;
  }
  return $tot;
}

function get_abiti(){
global $config;
return $config['clothes'];
}

function get_max_vol(){
global $config;
return $config['max_vol'];
}


function is_valigia_full($abito, $valigia, $max_vol){
//somma i volumi di tutti gli abiti nella valigia e il nuovo
//abito che vado ad inserire e confronta il totale con il val max

$clothes = get_abiti();
$tot_vol = $clothes[$abito]['vol'];
foreach($valigia as $key=>$value){
  $tot_vol += ($clothes[$key]['vol'] * $value);
}
if ($tot_vol>$max_vol){
  return 1;
}

return 0;
}
