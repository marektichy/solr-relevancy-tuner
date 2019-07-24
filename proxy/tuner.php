<?php

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/solrconfig.inc';
require_once __DIR__ . '/vendor/autoload.php';

use Solarium\Plugin\BufferedAdd\Event\Events;
use Solarium\Plugin\BufferedAdd\Event\PreFlush as PreFlushEvent;

// Common setting
$debug = false;
$numeres_suggest = 8;
$numeres_recommend = 16;

// Get knobs value
@$k0 = intval($_GET['k0']) / 50;
@$k1 = intval($_GET['k1']) / 50;
@$k2 = intval($_GET['k2']) / 50;

// Get solr relevancy calculation formula
@$formula = $_GET['f'];
if (!$formula || $formula == 'undefined') {
  $formula = "sub(10,product(2,avail))^$k2 product(1,sqrt(salability))^$k1 sub(10,div(log(div(sum(1,abs(age)),10)),0.3))^$k0";
}

// Get query or an empty string
$q = @$_GET['q'] ? $_GET['q'] : '';

$solr_client = new Solarium\Client($config);

// Manually create request for the query
$query = $solr_client->createSelect();
$query->setHandler('suggest');
$query->setQuery($q);
$query->addParam('rows', 5 * $numeres_suggest);
$query->addParam('bf', 'sqrt(salability)^0.15');

$groupComponent = $query->getGrouping();
$groupComponent->addField('type');
$groupComponent->setLimit($numeres_suggest);

// Add highlighting 
$hl = $query->getHighlighting();
$hl->setFields('suggest_key, author, author_main');
$hl->setSimplePrefix('<b>');
$hl->setSimplePostfix('</b>');

// Add spellcheck
$spellcheck = $query->getSpellcheck();
$spellcheck->setQuery($q);
$spellcheck->setCount(1);
$spellcheck->setCollate(true);
$spellcheck->setExtendedResults(true);
$spellcheck->setCollateExtendedResults(true);

$start = microtime(true);
$altered = false;

try {
  $resultset = $solr_client->select($query);
  $highlighting = $resultset->getHighlighting();
  $groups = $resultset->getGrouping();
  $matches = $groups->getGroup('type')->getMatches();
  if (!$matches) {
    $spellcheckResult = $resultset->getSpellcheck();
    if (is_object($spellcheckResult) && $suggestion = $spellcheckResult->getSuggestion(0)->getWord()) {
      $query->setQuery($suggestion);
      $altered = $suggestion;
      $resultset = $solr_client->select($query);
      $highlighting = $resultset->getHighlighting();
      $groups = $resultset->getGrouping();
    }
  }
}
catch (Solarium\Exception $e) {
  var_dump($e);
}

$result = new StdClass();
$result->suggestions = array();

$j = 0;

// Extract groups 

foreach ($groups as $groupKey => $fieldGroup) {
  foreach ($fieldGroup as $valueGroup) {
    $key = $valueGroup->getValue();
    $result->{$key} = array();
    $seen = [];
    foreach ($valueGroup as $doc) {
      $foo = new StdClass();
      $label = make_book_label($doc, $highlighting);
      $value = strip_tags($label);
      // Deduplicate, TODO: Should be done in Solr
      if (!array_key_exists($value, $seen)) {
        $seen[$value] = $label;
        $foo->label = make_book_label($doc, $highlighting);
        $foo->value = strip_tags($foo->label);
        $result->{$key}[$j] = $foo;
        $j++;
      }

    }
  }
}



// A little (ugly) trick to get expanded from the highlighter
$top_suggestion = false;
$string = reset($result->book);
$string = $string->label;
$string = str_replace('</b>', '<b>', $string);
$parts = explode('<b>', $string);
foreach ($parts as $p => $part) {
  if ($p & 1) {
    $top_suggestion .= $part . " ";
  }
}
$top_suggestion = trim($top_suggestion);


// Run recommendation subquery 
if ($top_suggestion) {
  if (!$altered) {
    $new_string = $q;
  }
  else {
    $new_string = $altered;
  }
  $sub_query = $solr_client->createSelect();
  $sub_query->setHandler('recommend');
  $sub_query->setQuery($new_string);
  if ($debug)
    $sub_query->getDebug();
  $sub_query->addParam('fq', 'type:book');
  $sub_query->addParam('bf', $formula);
  $sub_query->addParam('rows', $numeres_recommend);
  try {
    $sub_resultset = $solr_client->select($sub_query);
    $debugResult = $sub_resultset->getDebug();
  }
  catch (Solarium\Exception $e) {
    var_dump($e);
  }
  $j = 0;
  $result->recommended = array();
  foreach ($sub_resultset as $doc) {
    $foo = new stdClass;
    $img_src = 'https://obalky.kosmas.cz/ArticleCovers/' . substr($doc['id'], 0, 3) . '/' . substr($doc['id'], 3, 3) . '_base.jpg';
    $foo->img = $img_src;
    $foo->id = $doc['id'];
    $foo->title = $doc['suggest_key'];
    $foo->author = $doc['author'];
    $foo->a = $doc['age'];
    $foo->p = $doc['salability'];
    $foo->d = $doc['avail'];
    $foo->score = round($doc['score'], 2);
    $result->recommended[$j] = $foo;
    $j++;
  }
}

if (!empty($result->out2)) {
  $result->out2 = implode(",", $result->out2);
}

//Debug stuff
$response = $resultset->getResponse();

$time_elapsed_secs = round(1000 * (microtime(true) - $start));

$result->debug = "Top :" . $top_suggestion . '<br />';
if ($altered) {
  $result->debug .= "Altered:" . $altered . '<br />';
}
if ($new_string) {
  $result->debug .= "Subquery:" . $new_string . '<br />';
}

$result->debug .= "QTime Total: " . $time_elapsed_secs . 'ms<br />';

// Send final response
echo json_encode($result);


// If we don't have match in the book title, it must have matched the author
function make_book_label($doc, &$highlighting) {

  $orig_title = $doc['suggest_key'];
  $title = @$highlighting->getResult($doc['id'])->getField('suggest_key')[0];
  $author = @$highlighting->getResult($doc['id'])->getField('author_main')[0];

  if ($title && $author) {
    $title .= " ( $author )";
  }
  if (!$title && $author) {
    $title = "$orig_title ( $author )";
  }
  if (!$title && !$author) {
    $title = $orig_title;
  }

  return $title;
}

?>