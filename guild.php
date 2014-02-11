<?Php
$realm="The Maelstrom";
$guild="Revenga";
$guild=json_decode(file_get_contents("http://eu.battle.net/api/wow/guild/The%20Maelstrom/Revenga?fields=members"),true);
/*$db=new pdo("sqlite:/tmp/$guild-$realm.db");
$q="CREATE TABLE `members` (
  `id` INT NOT NULL,
  `realm` VARCHAR) NULL,
  `battlegroup` VARCHAR NULL,
  `class` INT NULL,
  `race` INT NULL,
  `gender` VARCHAR(45) NULL,
  PRIMARY KEY (`id`));";*/
foreach($guild['members'] as $member)
{
	$sortedmembers[$member['character']['race']][$member['character']['class']][]=$member;
	
}
//print_r($sortedmembers);