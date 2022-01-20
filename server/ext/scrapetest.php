<?php
require_once "simple_html_dom.php";

$html = file_get_html('https://store.steampowered.com/app/17390', false);
//$html = file_get_html('https://store.steampowered.com/app/807120', false);
//$html = file_get_html('https://store.steampowered.com/app/10110', false);
//$html = file_get_html('https://store.steampowered.com/app/812140', false);


echo "TITLE: ";
$search_results = $html->find("title");
$title = $search_results[0]->innertext;
echo $title;
echo "<hr>";

echo "DESCRIPTION: ";
$search_results = $html->find(".game_description_snippet");
$description = $search_results[0]->innertext;
echo $description;
echo "<hr>";

echo "TAGS: ";
$search_results = $html->find(".glance_tags a");
foreach ($search_results as $result) {
	$tags[] = trim($result->innertext);
}
echo implode(", ",$tags);
echo "<hr>";

echo "DETAILS: ";
$search_results = $html->find(".game_area_details_specs_ctn .label");
foreach ($search_results as $result) {
	$details[] = trim($result->innertext);
}
echo implode(", ",$details);
echo "<hr>";

echo "REVIEW: ";
$search_results = $html->find(".responsive_reviewdesc");
if(isset($search_results[1])) {
	$review = $search_results[1]->innertext;
} else {
	$review = $search_results[0]->innertext;
}
echo trim(substr(trim($review),1,strpos(trim($review),"%")-1));
echo "<hr>";

echo "DEVELOPER: ";
$search_results = $html->find("#developers_list a");
foreach ($search_results as $result) {
	$developers[] = trim($result->innertext);
}
echo implode(", ",$developers);
echo "<hr>";

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
echo "<hr>";

echo "GENRE: ";
$eles = $html->find('*');
$i=0;
foreach($eles as $e) {
    if($e->innertext == 'Genre:') {
		$search_results = $e->parent->find("a");
		foreach ($search_results as $result) {
			$genres[] = trim($result->innertext);
		}
		break;
		echo "<br>";
    }
}
echo implode(", ",$genres);
echo "<hr>";


?>