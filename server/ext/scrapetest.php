<?php
require_once "simple_html_dom.php";

//$html = file_get_html('https://store.steampowered.com/app/17390', false);
//$html = file_get_html('https://store.steampowered.com/app/807120', false);
//$html = file_get_html('https://store.steampowered.com/app/10110', false);
//$html = file_get_html('https://store.steampowered.com/app/812140', false);
//$html = file_get_html('https://store.steampowered.com/app/812141', false); //redirected to steam home page

$GLOBALS['rootpath'] = $GLOBALS['rootpath'] ?? "..";
$html = file_get_contents($GLOBALS['rootpath']."/tests/testdata/steam.htm");
//echo $html;
$html = str_get_html($html);

//$html = file_get_html($GLOBALS['rootpath']."/tests/testdata/steam.htm");
$html = file_get_html($GLOBALS['rootpath']."/tests/testdata/steam17390.htm");
//$html = file_get_html($GLOBALS['rootpath']."/tests/testdata/steam807120.htm");
//$html = file_get_html($GLOBALS['rootpath']."/tests/testdata/steam10110.htm");
//$html = file_get_html($GLOBALS['rootpath']."/tests/testdata/steam812140.htm");

//echo htmlspecialchars($html->outertext);
//echo $html->outertext;
//$html->save("steam17390.htm");

echo "TITLE: ";
$search_results = $html->find("title");
$title = $search_results[0]->innertext;
echo $title;
echo "<hr>\n\n";

echo "DESCRIPTION: ";
$search_results = $html->find(".game_description_snippet");
$description = trim($search_results[0]->innertext);
echo $description;
echo "<hr>\n\n";

echo "TAGS: ";
$search_results = $html->find(".glance_tags a");
foreach ($search_results as $result) {
	$tags[] = trim($result->innertext);
}
echo implode(", ",$tags);
echo "<hr>\n\n";

echo "DETAILS: ";
$search_results = $html->find(".game_area_details_specs_ctn .label");
foreach ($search_results as $result) {
	$details[] = trim($result->innertext);
}
echo implode(", ",$details);
echo "<hr>\n\n";

echo "REVIEW: ";
$search_results = $html->find(".responsive_reviewdesc");
if(isset($search_results[1])) {
	$review = $search_results[1]->innertext;
} else {
	$review = $search_results[0]->innertext;
}
echo trim(substr(trim($review),1,strpos(trim($review),"%")-1));
echo "<hr>\n\n";

echo "RELEASE DATE: ";
$search_results = $html->find(".date");
$date = $search_results[0]->innertext;
echo $date;
echo "<hr>\n\n";


echo "DEVELOPER: ";
$search_results = $html->find("#developers_list a");
foreach ($search_results as $result) {
	$developers[] = trim($result->innertext);
}
echo implode(", ",$developers);
echo "<hr>\n\n";

echo "PUBLISHER: ";
$eles = $html->find('*');
$i=0;
foreach($eles as $e) {
    if($e->innertext == 'Publisher:') {
		$search_results = $e->parent->find("a");
		foreach ($search_results as $result) {
			$publishers[] = trim($result->innertext);
		}
		break;
		echo "<br>";
    }
}
echo implode(", ",$publishers);
echo "<hr>\n\n";

echo "GENRE: ";
$eles = $html->find('*');
$i=0;
foreach($eles as $e) {
    if($e->innertext == 'Genre:') {
		$search_results = $e->parent->find("span a");
		foreach ($search_results as $result) {
			$genres[] = trim($result->innertext);
		}
		break;
		echo "<br>";
    }
}
echo implode(", ",$genres);
echo "<hr>\n\n";


?>