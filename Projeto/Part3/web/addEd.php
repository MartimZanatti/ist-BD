<html>
    <body>
        <h3><?=$_REQUEST['modo']?> <?=$_REQUEST['table']?></h3>
        <form method="post" action="update.php"> 
            <input type="hidden" name="table" value="<?=$_REQUEST['table']?>"/>
	    <input type="hidden" name="modo" value="<?=$_REQUEST['modo']?>"/>

            Morada: <input type="text" name="morada"/>
	       <br><br>
          
           <input type="submit" name="submit" value="Submeter"/>
        </form>
  <?php
echo "<a href=\"main.php\"> Voltar</a>\n";
?>

    </body>
</html>
