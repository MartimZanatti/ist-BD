<html>
    <body>
        <h3>Mostrar o <?=$_REQUEST['modo']?> realizado para cada espaco num <?=$_REQUEST['table']?></h3>
        <form method="post" action="update.php"> 
            <input type="hidden" name="table" value="<?=$_REQUEST['table']?>"/>
	       <input type="hidden" name="modo" value="<?=$_REQUEST['modo']?>"/>

            Morada: <input type="text" name="morada"/>
	       <br><br>
           <input type="submit" name="submit" value="Submeter"/>
        </form>
  <?php
echo "<a href=\"main.php\"> Back </a>\n";
?>

    </body>
</html>
