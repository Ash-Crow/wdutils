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

//$queryResult=queryCommonsAPI("action=query&list=embeddedin&eititle=Template:MBA_Rennes&einamespace=6&eilimit=50&format=json"); 

$template="MBA_Rennes";
$queryResult="action=query&prop=imageinfo&format=json&iiprop=comment%7Cparsedcomment%7Ccanonicaltitle%7Curl%7Cdimensions%7Cmime%7Cthumbmime%7Cmediatype%7Cmetadata%7Ccommonmetadata%7Cextmetadata%7Carchivename&generator=embeddedin&geititle=Template%3A".$template."&geinamespace=6&geilimit=100";

if (isset($queryResult->query)) {
	$queryPages=$queryResult->query->pages;


	foreach ($queryPages as $key => $value){
		$itemInfo=$value->imageinfo;

		$itemId = $key;
		$item=array();
		$item["title"]=$value->title;
		$item["width"]=$itemInfo->width;
		$item["height"]=$itemInfo->height;
		$item["fileUrl"]=$itemInfo->url;
		$item["descriptionUrl"]=$itemInfo->url;
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
