<?Php
require 'guild.php';
$races=json_decode(file_get_contents("http://eu.battle.net/api/wow/data/character/races"),true);
$classes=json_decode(file_get_contents("http://eu.battle.net/api/wow/data/character/classes"),true);
$combos=json_decode(file_get_contents('combos.json'),true);

$title="{$guild['name']} - {$guild['realm']} ({$guild['level']})";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?Php echo $title; ?></title>
<style type="text/css">
.invalidcombo {
	background-color: #666;
}
.missing {
	background-color: #F00;
}
.issue {
	background-color: #FF0;
}
.good {
	background-color: #0C0;
}
</style>
</head>

<body>
<?Php
echo "<table border=\"1\">\n";

array_unshift($classes['classes'],'header');

foreach($classes['classes'] as $classkey=>$class) //Class columns
{
	echo "\t<tr>\n";
	foreach($races['races'] as $racekey=>$race) //Race rows
	{
		if($race['side']!='alliance')
			continue;

		if($racekey==3 && is_array($class)) //For the first race (the first column) add an extra column with the class
			echo "\t   <th>{$class['name']}</th>\n";
		elseif($racekey==3)
			echo "\t   <th>&nbsp;</th>\n";

		if(!is_array($class))
		{
			$text=$race['name'];
			//print_r($race);
		}
		elseif(isset($combos[$race['id']][$class['id']]))
		{
			if(isset($sortedmembers[$race['id']][$class['id']]))
			{
				$count=count($sortedmembers[$race['id']][$class['id']]); //Count the members
				unset($valid,$allmembers);
				$validcount=0;
				foreach($sortedmembers[$race['id']][$class['id']] as $key=>$member)
				{
					$text="{$member['character']['name']} ({$member['character']['level']})<br />\n\t\t";
					$allmembers[]=$text;
					if($member['character']['level']>=85)
					{
						$valid[]=$text;
						$validcount++;
					}
					if(!isset($valid) && $key==$count-1)
					{
						$celltext=implode("",$allmembers);
						$cellclass='issue';
					}
					else
					{
						$celltext=implode("",$valid);
						$cellclass='good';
						if($validcount>=10) //Do not display more than 10 lv 90
							break;
					}

				}
				echo "\t   <td class=\"$cellclass\">".trim($celltext)."</td>\n";
			}
			else
				echo "\t   <td class=\"missing\">No members</td>";

		}
		else
			echo "\t   <td class=\"invalidcombo\">&nbsp;</td>\n"; //Invalid combination

		unset($text);
	}
	echo "\t</tr>\n";
}
echo "</table>\n";
?>
</body>
</html>