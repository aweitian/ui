<?php

use Aw\Ui\Base\Element;

require __DIR__ . "/../vendor/autoload.php";

/***
 * <div id='root'>
 *  <div id='header'>
 *      <ul>
 *          <li><a href='/'>home</a></li>
 *          <li><a href='/about'>about us</a></li>
 *          <li><a href='/contact'>contact us</a></li>
 *      </ul>
 *  </div>
 *  <div id='body'>
 *      <h1>test element</h1>
 *      <form action='/post' method="post">
 *          <input type="text" value="11" name="age" class="ax kf active">
 *          <br>
 *          <select name="category">
 *              <option value="1">111</option>
 *              <option value="2" selected>222</option>
 *              <option value="3">333</option>
 *          </select>
 *          <input type="submit" value="submit">
 *      </form>
 *  </div>
 *  <div id="foot">
 * @copyright 2018
 *  </div>
 * </div>
 */


$root = new Element('div');
$root->setId('root');

$header = new Element("div", array("id" => "header"));
$body = new Element("div", array("id" => "body"));
$foot = new Element("div", array("id" => "foot"));

$root->appendNode($header);
$root->appendNode($body);
$root->appendNode($foot);

$ul = new Element('ul');
$header->appendNode($ul);
$li1 = new Element('li');
$li2 = new Element('li');
$li3 = new Element('li');

$ul->appendNode($li1);
$ul->appendNode($li2);
$ul->appendNode($li3);

$a_home = new Element('a', array("href" => "/"));
$a_home->setText("home");
$a_about = new Element('a', array("href" => "/about"));
$a_about->setText("about us");
$a_contact = new Element('a', array("href" => "/contact"));
$a_contact->setText("contact us");


$li1->appendNode($a_home);
$li2->appendNode($a_about);
$li3->appendNode($a_contact);

$h1 = new Element('h1');
$h1->setText("test element");
$body->appendNode($h1);

$form = new \Aw\Ui\Base\Form();
$form->setMethod("post");
$form->setAction("/post");

//这里不设置CLASS,等下用FIND把它找出来设置
$form->appendNode(new \Aw\Ui\Base\Input\Text('age', '11'));
$form->appendNode(new \Aw\Ui\Base\LeafElement("br"));
$form->appendNode(new \Aw\Ui\Base\Input\Select("category", array(
    "1" => "111",
    "2" => "222",
    "3" => "333",
), "2"));
$body->appendNode($form);
$form->appendNode(new \Aw\Ui\Base\Input\Submit('submit'));

$foot->setText("@copyright 2018");

$age_input = $root->find("age", "input", true);
if ($age_input != null) {
    print "OK.\n";
    $age_input->setClass(array("ax", "kf", "active"));
}
var_dump($root->dumpHtml());
print "\n==================================================\n";
$v = new \Aw\Ui\Base\Input\Hidden();
$v->setName('vvv');

$span = new Element('span');
$span->appendNode($v);
$h1->appendNode($span);
var_dump($root->dumpHtml());
print "\n==================================================\n";
$c = $root->remove($v);
var_dump($c);
print "\n==================================================\n";
var_dump($root->dumpHtml());