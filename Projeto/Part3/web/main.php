<html><head><h1 style="margin-left:20px;">Aplicação</h1><style>
table{width:25%}
table, td {
    margin:20px;
	border:2px solid black;
	
	
    border-collapse: collapse;
}
td {
	background-color: #f5f5f5;
    padding: 8px;
    text-align: center;    
}



input[type="submit"] {
margin-bottom: -16px;
    width: 100px; 
    height: 50px; 
    border: 1;
}

</style></head>
<body>
<?php

echo("<table >\n");
        echo("<td><big><b>Edificio</b></big></td>\n");
    echo("<tr>\n");
        echo("<td><a href=\"addEd.php?table=Edificio&modo=Adicionar\">Adicionar</a></td>\n");
	echo("<td><a href=\"addEd.php?table=Edificio&modo=Remover\">Remover</a></td>\n");   
    echo("</tr>\n");
echo("</table>\n");

echo("<table >\n");
        echo("<td><b><big>Espaco</big></b></td>\n");
    echo("<tr>\n");
        echo("<td><a href=\"addEsp.php?table=Espaco&modo=Adicionar\">Adicionar</a></td>\n");
	echo("<td><a href=\"remEsp.php?table=Espaco&modo=Remover\">Remover</a></td>\n");   
    echo("</tr>\n");
echo("</table>\n");

echo("<table >\n");
        echo("<tr><td><b><big>Posto</big></b></td></tr>\n");
    echo("<tr>\n");
        echo("<td><a href=\"addPosto.php?table=Posto&modo=Adicionar\">Adicionar</a></td>\n");
	echo("<td><a href=\"remEsp.php?table=Posto&modo=Remover\">Remover</a></td>\n");   
    echo("</tr>\n");
echo("</table>\n");
echo("<br>");
echo("<table >\n");
        echo("<tr><td><b><big>Oferta</big></b></td></tr>\n");
    echo("<tr>\n");
        echo("<td><a href=\"addOferta.php?table=Oferta&modo=Criar\">Criar</a></td>\n");
	echo("<td><a href=\"remOferta.php?table=Oferta&modo=Remover\">Remover</a></td>\n");   
    echo("</tr>\n");
echo("</table>\n");
echo("<table >\n");
        echo("<tr><td><b><big>Reserva</big></b></td></tr>\n");
    echo("<tr>\n");
        echo("<td><a href=\"addReserva.php?table=Reserva&modo=Criar\">Criar</a></td>\n");
	echo("<td><a href=\"payReserva.php?table=Reserva&modo=Pagar\">Pagar</a></td>\n");   
    echo("</tr>\n");
echo("</table>\n");
echo("<br>");
echo("<table >\n");
        echo("<tr><td><b><big>Informação sobre os espacos num dado Edificio</big></b></td></tr>\n");
    echo("<tr>\n");
        echo("<td><a href=\"addEd.php?table=Edificio&modo=Total\">Definir Edificio</a></td>\n");
	  
    echo("</tr>\n");
echo("</table>\n");

?>
    </body>
</html>