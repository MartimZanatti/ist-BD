<html>
    <body>


<?php
    
	
	$modo = $_REQUEST['modo'];
	$table = $_REQUEST['table'];
	$morada = $_REQUEST['morada'];
	$codigo = $_REQUEST['codigo'];
	$codigo_espaco =$_REQUEST['codigo_espaco'];
	$data_inicio = $_REQUEST['data_inicio'];
	$data_fim = $_REQUEST['data_fim'];
	$tarifa = $_REQUEST['tarifa'];
	$nif=$_REQUEST['nif'];
	$numero=$_REQUEST['numero'];
	$metodo=$_REQUEST['metodo'];
	$foto=$_REQUEST['foto'];





	try
    {
        $host = "db.ist.utl.pt";
        $user ="ist181543";
        $password = "12345";
        $dbname = $user;
        $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->query("start transaction;");
	
         if ($table==Edificio){
                    if (empty($_POST["morada"])) {
                            print "Morada is required\n";
                        echo"<br>";
                    }else{
                        if($modo==Adicionar){
                            $sql = "INSERT INTO edificio(morada) VALUES ('$morada');";}
                        if($modo==Remover){
                            $stmt = $db->query("SELECT * FROM espaco WHERE morada ='$morada';");
                            $row_count = $stmt->rowCount();
                            $stmt1 = $db->query("SELECT * FROM edificio WHERE morada ='$morada';");
                            $row_count1 = $stmt1->rowCount();


                            if($row_count==0 and $row_count1==1){$sql = "DELETE FROM edificio WHERE morada='$morada'";}
                            else {print "Nao e possivel remover o Edificio.";}
                        }else if($modo==Total){
                            $stmt2 = $db->query("SELECT * FROM edificio WHERE morada ='$morada';");
                            $row_count2 = $stmt2->rowCount();
                            if($row_count2==1){
                            $sql = "select codigo, sum(Montante) as Montante from (
                                select *
                                from                    
                                (select morada, codigo, 0 as Montante
                                from espaco) as otherespaco 
                                union
                                (select morada, codigo, sum(total) as Montante 
                                    from (select morada, codigo, total 
                                        from (select morada, codigo_espaco as codigo, sum(total) as total 
                                            from (select * 
                                                from posto natural join (select tarifa * datediff(data_fim,data_inicio) as total, morada, codigo 
                                                       from oferta natural join aluga natural join paga
                                                       ) as T
                                                ) as P
                                            group by morada, codigo_espaco
                                            ) as POSTO 
                                        union (select * 
                                            from espaco natural join (select tarifa * datediff(data_fim,data_inicio) as total, morada, codigo 
                                                   from oferta natural join aluga natural join paga	
                                                   ) as E
                                            )
                                        ) as ESPACO 
                                    group by morada, codigo)) as NOVO
                                where morada = '$morada'
                                group by morada, codigo;";
                            $result = $db->query($sql);

                                echo("<table border=\"1\">\n");
                                echo("<tr><td>Codigo</td><td>Montante</td></tr>\n");
                                foreach($result as $row)
                                {
                                        echo("<tr><td>");
                                        echo($row['codigo']);
                                echo("</td><td>");
                                        echo($row['Montante']);
                                        echo("</td></tr>\n");
                                }
                                echo("</table>\n");
                            }else{print "O Edificio nao existe";}


                        }
                    }
            }
	if($table==Espaco){
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if (empty($_POST["morada"])) {
    				print "Morada is required\n";
				echo"<br>";
  			} 
		
			if (empty($_POST["codigo"])) {
    				print "Codigo is required";
				echo"<br>";
			}
			if (empty($_POST["foto"]) and $modo==Adicionar) {
    				print "Foto is required";
				echo"<br>";
			}
			
			
			else if(!empty($_POST["morada"]) and !empty($_POST["codigo"]) ){
				
				if($modo==Adicionar and !empty($_POST["foto"])){
					
					$sql = "INSERT INTO alugavel(morada,codigo,foto) VALUES ('$morada','$codigo','$foto');INSERT INTO espaco(morada,codigo) VALUES ('$morada','$codigo');";
				}
				else if($modo==Remover){
					$stmt = $db->query("SELECT * FROM posto WHERE morada ='$morada' AND codigo_espaco ='$codigo';");
					$row_count = $stmt->rowCount();
					$stmt1 = $db->query("SELECT * FROM oferta WHERE morada ='$morada' AND codigo ='$codigo';");
					$row_count1 = $stmt1->rowCount();
					$stmt2 = $db->query("SELECT * FROM fiscaliza WHERE morada ='$morada' AND codigo ='$codigo';");
					$row_count2 = $stmt2->rowCount();
					$stmt3 = $db->query("SELECT * FROM espaco WHERE morada ='$morada' and codigo ='$codigo';");
					$row_count3 = $stmt3->rowCount();
	
					if(($row_count+$row_count1+$row_count2)==0 and $row_count3==1){$sql = "DELETE FROM espaco WHERE morada='$morada' and codigo='$codigo';DELETE FROM alugavel WHERE morada='$morada' and codigo='$codigo'";}
					else {print "Nao e possivel remover o Espaco.";}
					
				}
				
			}

		}
		
	}
	if($table==Posto){
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if (empty($_POST["morada"])) {
    				print "Morada is required\n";
				echo"<br>";
  			} 
		
			if (empty($_POST["codigo"])) {
    				print "Codigo is required";
				echo"<br>";
			}
			if (empty($_POST["codigo_espaco"]) and $modo==Adicionar) {
    				print "Codigo Espaco is required";
				echo"<br>";
			}
			if (empty($_POST["foto"]) and $modo==Adicionar) {
    				print "Foto is required";
				echo"<br>";
			}
			
			else if(!empty($_POST["morada"]) and !empty($_POST["codigo"]) ){
				
				if($modo==Adicionar and !empty($_POST["codigo_espaco"]) and !empty($_POST["foto"])){
					$sql = "INSERT INTO alugavel(morada,codigo,foto) VALUES ('$morada','$codigo','$foto');INSERT INTO posto(morada,codigo,codigo_espaco) VALUES ('$morada','$codigo','$codigo_espaco');";
				}
				else if($modo==Remover){
					
					$stmt1 = $db->query("SELECT * FROM oferta WHERE morada ='$morada' AND codigo ='$codigo';");
					$row_count1 = $stmt1->rowCount();
					$stmt2 = $db->query("SELECT * FROM fiscaliza WHERE morada ='$morada' AND codigo ='$codigo';");
					$row_count2 = $stmt2->rowCount();
					$stmt3 = $db->query("SELECT * FROM posto WHERE morada ='$morada' and codigo ='$codigo';");
					$row_count3 = $stmt3->rowCount();
					
	
					if(($row_count1+$row_count2)==0 and $row_count3==1){$sql = "DELETE FROM posto WHERE morada='$morada' and codigo='$codigo';DELETE FROM alugavel WHERE morada='$morada' and codigo='$codigo'";}
					else {print "Nao e possivel remover o Posto.";}
				}
				
			}

		}
	
	}
	if($table==Oferta){
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if (empty($_POST["morada"])) {
    				print "Morada is required\n";
				echo"<br>";
  			} 
		
			if (empty($_POST["codigo"])) {
    				print "Codigo is required";
				echo"<br>";
			}
			if (empty($_POST["data_inicio"])) {
    				print "Data de Inicio is required";
				echo"<br>";
			}

			if (empty($_POST["data_fim"]) and $modo==Criar) {
    				print "Data de Fim is required";
				echo"<br>";
			}
	
			if (empty($_POST["tarifa"])and $modo==Criar) {
    				print "Tarifa is required";
				echo"<br>";
			}
			else if(!empty($_POST["morada"]) and !empty($_POST["codigo"]) and !empty($_POST["data_inicio"])){
				
				if($modo==Criar and !empty($_POST["data_fim"]) and !empty($_POST["tarifa"])){
					$sql = "INSERT INTO oferta(morada,codigo,data_inicio,data_fim,tarifa) VALUES ('$morada','$codigo','$data_inicio','$data_fim','$tarifa');";
				}
				else if($modo==Remover){
					$stmt = $db->query("SELECT * FROM aluga WHERE morada ='$morada' AND codigo ='$codigo' AND data_inicio = '$data_inicio';");
					$row_count = $stmt->rowCount();
					$stmt1 = $db->query("SELECT * FROM oferta WHERE morada ='$morada' and codigo ='$codigo' and data_inicio ='$data_inicio';");
					$row_count1 = $stmt1->rowCount();
	
					if($row_count==0 and $row_count1==1){$sql = "DELETE FROM oferta WHERE morada='$morada' AND codigo='$codigo' AND data_inicio='$data_inicio';";}
					else {print "Nao e possivel remover a Oferta.";}
					
				}
				
			}

		}
	
	}
	if($table==Reserva and $modo==Criar){
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if (empty($_POST["morada"])) {
    				print "Morada is required\n";
				echo"<br>";
  			} 
		
			if (empty($_POST["codigo"])) {
    				print "Codigo is required";
				echo"<br>";
			}
			if (empty($_POST["data_inicio"])) {
    				print "Data de Inicio is required";
				echo"<br>";
			}
	
			if (empty($_POST["nif"])) {
    				print "NIF is required";
				echo"<br>";
			}
			else if(!empty($_POST["morada"]) and !empty($_POST["codigo"]) and !empty($_POST["data_inicio"]) and !empty($_POST["nif"])){
				
					$stmt = $db->query('SELECT * FROM reserva');
					$row_count = $stmt->rowCount()+1;
					
					$stmt1 = $db->query("SELECT * FROM user WHERE nif='$nif'");
					$row_count1 = $stmt1->rowCount();
				
					
					$result=substr($data_inicio,0,4)."-".$row_count;
					if($row_count1==1){
						$sql = "INSERT INTO reserva(numero) VALUES ('$result');INSERT INTO estado(numero,time_stamp,estado) VALUES ('$result',CURRENT_TIMESTAMP,'Pendente');INSERT INTO aluga(morada,codigo,data_inicio,nif,numero) VALUES ('$morada','$codigo','$data_inicio','$nif','$result');";
					}else{print "Nao e possivel inserir a Reserva porque o NIF nao existe.";}
				
			}

		}
	
	}
	
	if($table==Reserva and $modo==Pagar){
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if (empty($_POST["numero"])) {
    				print "Numero is required\n";
				echo"<br>";
  			} 
		
			if (empty($_POST["metodo"])) {
    				print "Metodo is required";
				echo"<br>";
			}
			
			else if(!empty($_POST["numero"]) and !empty($_POST["metodo"])){
				
					$stmt = $db->query("SELECT * FROM estado WHERE numero='$numero' and estado = 'Aceite';");
					$row_count = $stmt->rowCount();		
					if($row_count==1){
						$sql = "INSERT INTO paga(numero,data,metodo) VALUES ('$numero', CURRENT_TIMESTAMP, '$metodo');";
					}else{print "Nao pode pagar esta Reserva.";}
				
			}

		}
	
	}

	
        echo("<p>$sql</p>");
	
        

	
	
	if ($sql!=""){$db->query($sql); print "SUCCESSO!";echo"<br>";}


        $db->query("commit;");
        $db = null;
    }
    catch (PDOException $e)
    {
        $db->query("rollback;");
        echo("<p>ERROR: {$e->getMessage()}</p>");
    }

if($table==Espaco and $modo==Adicionar){echo "<a href=\"addEsp.php?table=$table&modo=$modo\"> Voltar a anterior</a>\n";
}else if($table==Espaco and $modo==Remover){echo "<a href=\"remEsp.php?table=$table&modo=$modo\"> Voltar a anterior</a>\n";}

if($table==Edificio){echo "<a href=\"addEd.php?table=$table&modo=$modo\"> Voltar a anterior</a>\n";
}
if($table==Posto and $modo==Adicionar){echo "<a href=\"addPosto.php?table=$table&modo=$modo\"> Voltar a anterior</a>\n";
}if($table==Posto and $modo==Remover){echo "<a href=\"remEsp.php?table=$table&modo=$modo\"> Voltar a anterior</a>\n";}

if($table==Oferta and $modo==Remover){echo "<a href=\"remOferta.php?table=$table&modo=$modo\">Voltar a anterior</a>\n";
}else if($table==Oferta and $modo==Criar){echo "<a href=\"addOferta.php?table=$table&modo=$modo\"> Voltar a anterior</a>\n";}

if($table==Reserva and $modo==Criar){echo "<a href=\"addReserva.php?table=$table&modo=$modo\"> Voltar a anterior</a>\n";
}else if($table==Reserva and $modo==Pagar){echo "<a href=\"payReserva.php?table=$table&modo=$modo\"> Voltar a anterior</a>\n";}

echo "<br><br>";
echo "<a href=\"main.php\"> Voltar a principal</a>\n";

?>
 </body>
</html>
