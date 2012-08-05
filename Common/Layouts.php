
<?php
function displayHeader()
{
	echo'<title>Checkargos.com - An Irish Stock Checker</title>';

	echo'<center>';//end is in footer.php
	echo '	<a href="http://www.checkargos.com">
				<img src="images/Titlethin.png" alt="www.Checkargos.com" />
			</a>';
	echo "<br /><br />";
}

function displayFooter()
{
	//Footer
	echo "<br /><br />";
	echo '	<table cellspacing="15" id="footer">
				<tr>
					<td>
						<a href="index.php">Home</a>
					</td>
					<td>
						<a href="clearance.php">Clearance</a>
					</td>
					<td>
						<a href="about.php">About</a>
					</td>
					<td>
						<a href="contact.php">Contact</a>
					</td>
				</tr>
			</table>
			
			<br />&copy;   checkargos.com';  

	echo'</center>';//start is in header.php
}

?>