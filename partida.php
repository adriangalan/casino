<?php
/* creas una sesion y pones una cantidad de
 *  diniro que tienes luego pones la apuesta sin superar tu cantidad.
 *  el juego es si es par o inpar si acierta se te duplica si no lo pierdes.
 *  hasta que el usuario decidad dejar el juego o te quedes sin dinero  
 */
session_start();
if (!isset($_COOKIE['sesiones'])) {
    setcookie("sesiones", 0, time() + 30*24*3600);    
    header("refresh: 0;"); 
}
//Operacion para indicar el ganador
function sacarResultado($selecionado) {
    $resultado=false;
    $numero= rand(1,2);    
    if($numero%2==0 && $selecionado=='par'){
        $resultado=true;
    }
    if($numero%2!=0 && $selecionado=='inpar'){
        $resultado=true;
    }   
    return $resultado;
}


?>
<html>
<head>
<meta charset="UTF-8">
<title>Casino</title>
</head>
<body>
<form method="get">
<?php 
//operacion de salida
if (isset($_REQUEST['salir'])) {
    echo "Muchas gracias por jugar con nosotros.<br>
            Su resultado final es de ".$_SESSION['cantidad']." € <br>";  
    $sesion=$_COOKIE["sesiones"];
    $sesion++;
    setcookie("sesiones", $sesion, time() + 30*24*3600); 
    session_destroy();
    exit();
}



if(isset($_REQUEST['entrar'])){
    $_SESSION['cantidad']=$_REQUEST['cantidad'];
}



//selecion de la cantidad a apostar y apuesta

if (isset($_SESSION['cantidad'])) {
    if (isset($_REQUEST['apostar'])) {
        //comprobacion de que tengas todos los valores
        if (isset($_REQUEST['selecionado']) && $_REQUEST['apuesta']>0) {
            //comprobacion de si tienes el saldo suficiente
            if ( $_SESSION['cantidad']>$_REQUEST['apuesta']  ) {
                //Resultado de la apuesta
                if (sacarResultado($_REQUEST['selecionado'])) {
                    $ganas=0;
                    $ganas+=$_REQUEST['apuesta']*2;
                    echo "RESULTADO DE LA APUESTA :".$_REQUEST['selecionado']."<br>
                           GANASTE<br>";
                    $_SESSION['cantidad']+=$ganas;
                    
                    echo "Dispones de ".$_SESSION['cantidad']." €  para jugar<br>";   
                }else{
                    echo "RESULTADO DE LA APUESTA : no es ".$_REQUEST['selecionado']."<br>
                           PERDISTE<br>";
                    $_SESSION['cantidad']-=$_REQUEST['apuesta'];
                    echo "Dispones de ".$_SESSION['cantidad']." €  para jugar<br>";                    
                }
            }else{
                echo  "Saldo insuficiente para realizar la apuesta .<br>";
            }
        }else {
            echo "Te falta selecionar la cantidad o la opcion a apostar.<br>";
        }
    }else{
        echo "Dispones de ".$_SESSION['cantidad']." €  para jugar<br>";        
    }
    echo "Introduce la cantidad para apostar  : <input name='apuesta' type='number' value='0' size='5' autofocus><br>";
    echo "Seleciona cual apostar:
				<input name='selecionado' value='par' type=radio>Par &nbsp;
				<input name='selecionado' value='inpar' type=radio>Inpar <br>";
    //resultado de la apuesta

    echo" <button name='apostar' value='Apostar'>Apostar cantidad</button>
          <button name='salir' value='Salir'>Abandonar el Casino</button>";   
    exit();
}

//operacion de entrada de cantidad de dinero para apostar
if (!isset($_SESSION['cantidad']) ) {
    echo "Hola has  entrada ". $_COOKIE['sesiones']." veces al casino. <br>";
    echo "Introduce la cantidad que quieres usar:";
    echo "<input name='cantidad' type='number' value='0' autofocus> <br>
        <button name='entrar' value='entrr'>Entrar</button>";
    
    
}


?>

</form>
</body>
</html>