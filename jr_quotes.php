<?php
/*
Plugin Name: JR Quotes
Plugin URI: http://www.jakeruston.co.uk/2009/10/wordpress-plugin-jr-quotes/
Description: This plugin allows you to display geek quotes, pop quotes, general quotes, religious quotes and sci-fi quotes, all with a simple widget.
Version: 1.6.9
Author: Jake Ruston
Author URI: http://www.jakeruston.co.uk
*/

/*  Copyright 2010 Jake Ruston - the.escapist22@gmail.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

$pluginname="quotes";

// Hook for adding admin menus
add_action('admin_menu', 'jr_quotes_add_pages');

// action function for above hook
function jr_quotes_add_pages() {
    add_options_page('JR Quotes', 'JR Quotes', 'administrator', 'jr_quotes', 'jr_quotes_options_page');
}

if (!function_exists("_iscurlinstalled")) {
function _iscurlinstalled() {
if (in_array ('curl', get_loaded_extensions())) {
return true;
} else {
return false;
}
}
}

if (!function_exists("jr_show_notices")) {
function jr_show_notices() {
echo "<div id='warning' class='updated fade'><b>Ouch! You currently do not have cURL enabled on your server. This will affect the operations of your plugins.</b></div>";
}
}

if (!_iscurlinstalled()) {
add_action("admin_notices", "jr_show_notices");

} else {
if (!defined("ch"))
{
function setupch()
{
$ch = curl_init();
$c = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
return($ch);
}
define("ch", setupch());
}

if (!function_exists("curl_get_contents")) {
function curl_get_contents($url)
{
$c = curl_setopt(ch, CURLOPT_URL, $url);
return(curl_exec(ch));
}
}
}

if (!function_exists("jr_quotes_refresh")) {
function jr_quotes_refresh() {
update_option("jr_submitted_quotes", "0");
}
}

register_activation_hook(__FILE__,'quotes_choice');

function quotes_choice () {
if (get_option("jr_quotes_links_choice")=="") {

if (_iscurlinstalled()) {
$pname="jr_quotes";
$url=get_bloginfo('url');
$content = curl_get_contents("http://www.jakeruston.co.uk/plugins/links.php?url=".$url."&pname=".$pname);
update_option("jr_submitted_quotes", "1");
wp_schedule_single_event(time()+172800, 'jr_quotes_refresh'); 
} else {
$content = "Powered by <a href='http://arcade.xeromi.com'>Free Online Games</a> and <a href='http://directory.xeromi.com'>General Web Directory</a>.";
}

if ($content!="") {
$content=utf8_encode($content);
update_option("jr_quotes_links_choice", $content);
}
}

if (get_option("jr_quotes_link_personal")=="") {
$content = curl_get_contents("http://www.jakeruston.co.uk/p_pluginslink4.php");

update_option("jr_quotes_link_personal", $content);
}
}

// jr_quotes_options_page() displays the page content for the Test Options submenu
function jr_quotes_options_page() {

    // variables for the field and option names 
    $opt_name = 'mt_quotes_header';
	$opt_name_2 = 'mt_quotes_color';
    $opt_name_3 = 'mt_quotes_type';
	$opt_name_4 = 'mt_quotes_lines';
    $opt_name_6 = 'mt_quotes_plugin_support';
	$opt_name_7 = 'mt_quotes_number';
    $hidden_field_name = 'mt_quotes_submit_hidden';
    $data_field_name = 'mt_quotes_header';
	$data_field_name_2 = 'mt_quotes_color';
    $data_field_name_3 = 'mt_quotes_type';
	$data_field_name_4 = 'mt_quotes_lines';
    $data_field_name_6 = 'mt_quotes_plugin_support';
	$data_field_name_7 = 'mt_quotes_number';

    // Read in existing option value from database
    $opt_val = get_option( $opt_name );
	$opt_val_2 = get_option( $opt_name_2 );
    $opt_val_3 = get_option( $opt_name_3 );
	$opt_val_4 = get_option($opt_name_4);
    $opt_val_6 = get_option($opt_name_6);
	$opt_val_7 = get_option($opt_name_7);
    
if (!$_POST['feedback']=='') {
$my_email1="the.escapist22@gmail.com";
$plugin_name="JR Quotes";
$blog_url_feedback=get_bloginfo('url');
$user_email=$_POST['email'];
$user_email=stripslashes($user_email);
$subject=$_POST['subject'];
$subject=stripslashes($subject);
$name=$_POST['name'];
$name=stripslashes($name);
$response=$_POST['response'];
$response=stripslashes($response);
$category=$_POST['category'];
$category=stripslashes($category);
if ($response=="Yes") {
$response="REQUIRED: ";
}
$feedback_feedback=$_POST['feedback'];
$feedback_feedback=stripslashes($feedback_feedback);
if ($user_email=="") {
$headers1 = "From: feedback@jakeruston.co.uk";
} else {
$headers1 = "From: $user_email";
}
$emailsubject1=$response.$plugin_name." - ".$category." - ".$subject;
$emailmessage1="Blog: $blog_url_feedback\n\nUser Name: $name\n\nUser E-Mail: $user_email\n\nMessage: $feedback_feedback";
mail($my_email1,$emailsubject1,$emailmessage1,$headers1);
?>
<div class="updated"><p><strong><?php _e('Feedback Sent!', 'mt_trans_domain' ); ?></strong></p></div>
<?php
}

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $opt_val = $_POST[ $data_field_name ];
		$opt_val_2 = $_POST[ $data_field_name_2 ];
        $opt_val_3 = $_POST[ $data_field_name_3 ];
		$opt_val_4 = $_POST[$data_field_name_4];
        $opt_val_6 = $_POST[$data_field_name_6];
		$opt_val_7 = $_POST[$data_field_name_7];

        // Save the posted value in the database
        update_option( $opt_name, $opt_val );
		update_option( $opt_name_2, $opt_val_2 );
        update_option( $opt_name_3, $opt_val_3 );
		update_option($opt_name_4, $opt_val_4);
        update_option( $opt_name_6, $opt_val_6 );  
		update_option( $opt_name_7, $opt_val_7 );

        // Put an options updated message on the screen

?>
<div class="updated"><p><strong><?php _e('Quote options saved.', 'mt_trans_domain' ); ?></strong></p></div>
<?php

    }

    // Now display the options editing screen

    echo '<div class="wrap">';

    // header

    echo "<h2>" . __( 'JR Quotes Plugin Options', 'mt_trans_domain' ) . "</h2>";
$blog_url_feedback=get_bloginfo('url');
	$donated=curl_get_contents("http://www.jakeruston.co.uk/p-donation/index.php?url=".$blog_url_feedback);
	if ($donated=="1") {
	?>
		<div class="updated"><p><strong><?php _e('Thank you for donating!', 'mt_trans_domain' ); ?></strong></p></div>
	<?php
	} else {
	if ($_POST['mtdonationjr']!="") {
	update_option("mtdonationjr", "444");
	}
	
	if (get_option("mtdonationjr")=="") {
	?>
	<div class="updated"><p><strong><?php _e('Please consider donating to help support the development of my plugins!', 'mt_trans_domain' ); ?></strong><br /><br /><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="ULRRFEPGZ6PSJ">
<input type="image" src="https://www.paypal.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form></p><br /><form action="" method="post"><input type="hidden" name="mtdonationjr" value="444" /><input type="submit" value="Don't Show This Again" /></form></div>
<?php
}
}

    // options form
    
    $change4 = get_option("mt_quotes_plugin_support");

if ($change4=="Yes" || $change4=="") {
$change4="checked";
$change41="";
} else {
$change4="";
$change41="checked";
}
    ?>
	<iframe src="http://www.jakeruston.co.uk/plugins/index.php" width="100%" height="20%">iframe support is required to see this.</iframe>
<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<p><?php _e("Quote Widget Title", 'mt_trans_domain' ); ?> 
<input type="text" name="<?php echo $data_field_name; ?>" value="<?php echo $opt_val; ?>" size="50">
</p><hr />

<p><?php _e("Number of Quotes:", 'mt_trans_domain' ); ?> 
<input type="text" name="<?php echo $data_field_name_7; ?>" value="<?php echo $opt_val_7; ?>" size="3">
</p><hr />

<p><?php _e("Types of Quotes:", 'mt_trans_domain' ); ?> 
<select name="<?php echo $data_field_name_3; ?>">
<optgroup label="Geek">
<option value="geek">All</option>
<option value="joel_on_software">joel_on_software</option>
<option value="macintosh">macintosh</option>
<option value="math">math</option>
<option value="paul_graham">paul_graham</option>
<option value="subversion">subversion</option>
</optgroup>
<optgroup label="General">
<option value="general">All</option>
<option value="1811_dictionary_of_the_vulgar_tongue">1811_dictionary_of_the_vulgar_tongue</option>
<option value="codehappy">codehappy</option>
<option value="fortune">fortune</option>
<option value="liberty">liberty</option>
<option value="misc">misc</option>
<option value="oneliners">oneliners</option>
<option value="riddles">riddles</option>
</optgroup>
<optgroup label="Pop">
<option value="pop">All</option>
<option value="calvin">calvin</option>
<option value="forrestgump">forrestgump</option>
<option value="friends">friends</option>
<option value="futurama">futurama</option>
<option value="holygrail">holygrail</option>
<option value="powerpuff">powerpuff</option>
<option value="simpsons_homer">simpsons_homer</option>
<option value="starwars">starwars</option>
<option value="xfiles">xfiles</option>
</optgroup>
<optgroup label="Religious">
<option value="religious">All</option>
<option value="bible">bible</option>
<option value="contentions">contentions</option>
<option value="osho">osho</option>
</optgroup>
<optgroup label="Sci-Fi">
<option value="scifi">All</option>
<option value="cryptonomicon">cryptonomicon</option>
<option value="discworld">discworld</option>
<option value="hitchhiker">hitchhiker</option>
</optgroup>
</select>
</p><hr />

<p><?php _e("Maximum Number of Characters in Quote:", 'mt_trans_domain' ); ?> 
<input type="text" name="<?php echo $data_field_name_4; ?>" value="<?php echo $opt_val_4; ?>" size="3">
</p><hr />

<p><?php _e("Hex Colour Code:", 'mt_trans_domain' ); ?> 
#<input size="7" name="<?php echo $data_field_name_2; ?>" value="<?php echo $opt_val_2; ?>">
(For help, go to <a href="http://html-color-codes.com/">HTML Color Codes</a>).
</p><hr />

<p><?php _e("Show Plugin Support?", 'mt_trans_domain' ); ?> 
<input type="radio" name="<?php echo $data_field_name_6; ?>" value="Yes" <?php echo $change4; ?>>Yes
<input type="radio" name="<?php echo $data_field_name_6; ?>" value="No" <?php echo $change41; ?> id="Please do not disable plugin support - This is the only thing I get from creating this free plugin!" onClick="alert(id)">No
</p><hr />

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options', 'mt_trans_domain' ) ?>" />
</p><hr />

</form>
<script type="text/javascript">
function validate_required(field,alerttxt)
{
with (field)
  {
  if (value==null||value=="")
    {
    alert(alerttxt);return false;
    }
  else
    {
    return true;
    }
  }
}

function validateEmail(ctrl){

var strMail = ctrl.value
        var regMail =  /^\w+([-.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;

        if (regMail.test(strMail))
        {
            return true;
        }
        else
        {

            return false;

        }
					
	}

function validate_form(thisform)
{
with (thisform)
  {
  if (validate_required(subject,"Subject must be filled out!")==false)
  {email.focus();return false;}
  if (validate_required(email,"E-Mail must be filled out!")==false)
  {email.focus();return false;}
  if (validate_required(feedback,"Feedback must be filled out!")==false)
  {email.focus();return false;}
  if (validateEmail(email)==false)
  {
  alert("E-Mail Address not valid!");
  email.focus();
  return false;
  }
 }
}
</script>
<h3>Submit Feedback about my Plugin!</h3>
<p><b>Note: Only send feedback in english, I cannot understand other languages!</b><br /><b>Please do not send spam messages!</b></p>
<form name="form2" method="post" action="" onsubmit="return validate_form(this)">
<p><?php _e("Your Name:", 'mt_trans_domain' ); ?> 
<input type="text" name="name" /></p>
<p><?php _e("E-Mail Address (Required):", 'mt_trans_domain' ); ?> 
<input type="text" name="email" /></p>
<p><?php _e("Message Category:", 'mt_trans_domain'); ?>
<select name="category">
<option value="General">General</option>
<option value="Feedback">Feedback</option>
<option value="Bug Report">Bug Report</option>
<option value="Feature Request">Feature Request</option>
<option value="Other">Other</option>
</select>
<p><?php _e("Message Subject (Required):", 'mt_trans_domain' ); ?>
<input type="text" name="subject" /></p>
<input type="checkbox" name="response" value="Yes" /> I want e-mailing back about this feedback</p>
<p><?php _e("Message Comment (Required):", 'mt_trans_domain' ); ?> 
<textarea name="feedback"></textarea>
</p>
<p class="submit">
<input type="submit" name="Send" value="<?php _e('Send', 'mt_trans_domain' ); ?>" />
</p><hr /></form>
</div>
<?php
}

if (get_option("jr_quotes_links_choice")=="") {
quotes_choice();
}

function show_quotes($args) {

extract($args);

  $quotes_header = get_option("mt_quotes_header"); 
  $plugin_support2 = get_option("mt_quotes_plugin_support");
  $quote_type = get_option("mt_quotes_type");
  $max_lines = get_option("mt_quotes_lines");
  $option_number = get_option("mt_quotes_number");
  $quotescolor = get_option("mt_quotes_color");

if ($quotes_header=="") {
$quotes_header="Popular Quotes";
}

if ($option_number=="" || $option_number=="0") {
$option_number=1;
}

$i=0;

echo $before_title.$quotes_header.$after_title."<br />".$before_widget; 

while ($i<$option_number) {
$handle=@fopen("http://www.iheartquotes.com/api/v1/random?sources=".$quote_type."&show_source=0&show_permalink=0&max_characters=".$max_lines, "rt");
$current_line=fread($handle, 9000);
$regs = array();

if(ereg("http://iheartquotes.com/fortune/show/(.)(.)(.)(.)(.)",$current_line,$regs)) {
$ending="{$regs[1]}{$regs[2]}{$regs[3]}{$regs[4]}{$regs[5]}";
$current_line = str_replace("http://iheartquotes.com/fortune/show/".$ending, "", $current_line);
}

echo "<li style='color:#".$quotescolour."'>".$current_line."</li>";
$i ++;
}

if ($plugin_support2=="Yes" || $plugin_support2=="") {
$linkper=utf8_decode(get_option('jr_quotes_link_personal'));

if (get_option("jr_quotes_link_newcheck")=="") {
$pieces=explode("</a>", get_option('jr_quotes_links_choice'));
$pieces[0]=str_replace(" ", "%20", $pieces[0]);
$pieces[0]=curl_get_contents("http://www.jakeruston.co.uk/newcheck.php?q=".$pieces[0]."");
$new=implode("</a>", $pieces);
update_option("jr_quotes_links_choice", $new);
update_option("jr_quotes_link_newcheck", "444");
}

if (get_option("jr_submitted_quotes")=="0") {
$pname="jr_quotes";
$url=get_bloginfo('url');
$content = curl_get_contents("http://www.jakeruston.co.uk/plugins/links.php?url=".$url."&pname=".$pname);
update_option("jr_submitted_quotes", "1");
update_option("jr_quotes_links_choice", $content);

wp_schedule_single_event(time()+172800, 'jr_quotes_refresh'); 
} else if (get_option("jr_submitted_quotes")=="") {
$pname="jr_quotes";
$url=get_bloginfo('url');
$current=get_option("jr_quotes_links_choice");
$content = curl_get_contents("http://www.jakeruston.co.uk/plugins/links.php?url=".$url."&pname=".$pname."&override=".$current);
update_option("jr_submitted_quotes", "1");
update_option("jr_quotes_links_choice", $content);

wp_schedule_single_event(time()+172800, 'jr_quotes_refresh'); 
}

echo "<br /><p style='color:#".$quotescolour.";font-size:x-small'>Quotes Plugin created by ".$linkper." - ".stripslashes(get_option('jr_quotes_links_choice'))."</p>";
}

echo $after_widget;

fclose($handle);
}

function init_quotes_widget() {
register_sidebar_widget("JR Quotes", "show_quotes");
}

add_action("plugins_loaded", "init_quotes_widget");

?>
