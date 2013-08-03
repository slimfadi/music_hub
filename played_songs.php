<?php
	$connection= mysql_connect("localhost","slimfadi_music","music");
	if(!$connection){die("Database connection failed:" .mysql_error());}
	$db_select=mysql_select_db("slimfadi_music",$connection);
	if(!$db_select){die("Database selection failed".mysql_error());}
	$sql= 'select * from songs_played order by time';
	$result=mysql_query($sql,$connection);
echo "<table border='1' cellpadding='3'>";
	$count=0;
	while($row=mysql_fetch_array($result)){
		echo "<tr>";
		echo "<td>".$row[0]."</td>";
		echo "<td>".$row[1]."</td>";
		echo "<td>".$row[2]."</td>";
		echo "<td>".$row[3]."</td>";
		echo "</tr>";
		$count++;
	}
	echo "</table>";
	echo $count;
?>
