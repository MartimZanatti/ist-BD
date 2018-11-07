<html>
    <body>
        <h3><?=$_REQUEST['modo']?> <?=$_REQUEST['table']?></h3>
        <form method="post" action="update.php"> 
            <input type="hidden" name="table" value="<?=$_REQUEST['table']?>"/>
	    <input type="hidden" name="modo" value="<?=$_REQUEST['modo']?>"/>

            Numero: <input type="text" name="numero"/>
	       <br><br>
             Metodo: <input type="text" name="metodo"/>
	       <br><br>
           <input type="submit" name="submit" value="Submeter"/>
        </form>
  <?php
echo "<a href=\"main.php\"> Back </a>\n";
?>

    </body>
</html>
