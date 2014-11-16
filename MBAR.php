<?php
error_reporting(E_ALL); 
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

$timeStart=microtime(true);

function queryCommonsAPI($query) {
                $url = 'https://commons.wikimedia.org/w/api.php?'. $query;

                $data = json_decode(file_get_contents($url));

		// Error checking
                if (isset($data->warning->main)) {
                        throw new Exception($data->warning->main);
                }
                return $data;

		if (isset($data->error)) {
			throw new Exception($data->error);
		}
		//*/
        }

$queryResult=queryCommonsAPI("action=query&list=embeddedin&eititle=Template:MBA_Rennes&einamespace=6&eilimit=50&format=json"); 

if (isset($queryResult->query)) {
	$queryEmbed=$queryResult->query->embeddedin;

	$itemIds = "";

	foreach ($queryEmbed as $result){
		$itemId = $result->pageid;
		echo $itemId."<br />";

		$itemIds.=$itemId."|";
	}
}

substr($itemIds, 0, -1);
$itemData = queryCommonsAPI("action=query&prop=revisions&pageids=$itemIds&format=json&rvprop=content&rvgeneratexml&rvexpandtemplates&continue");
//*/
echo "<pre>";
print_r($queryResult);
echo "</pre>";

$timeEnd=microtime(true);
$totalTime=$timeEnd-$timeStart;

echo "Total time = $totalTime seconds";

?>
