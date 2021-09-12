<?php
	if (count($footer_title)!=count($footer_config)) {echo "<p><strong>Warning</strong>:".$language["footer-number-not-same"]."</p>";}
	else
	{
		for ($i=0;$i<count($footer_title);$i++)
		{
			echo "<div class='footer-element' id='footer-element$i'>";
			echo "<h3>".$footer_title[$i]."</h3>";
			for ($j=0;$j<count($footer_config[$i]);$j++) echo "<a target='view_window' href='".$footer_config[$i][$j]["url"]."'>".$footer_config[$i][$j]["text"]."</a><br/>";
			echo "</div>";
		}
	}
?>