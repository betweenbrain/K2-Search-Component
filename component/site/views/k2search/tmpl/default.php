<?php defined('_JEXEC') or die;

/**
 * File       default.php
 * Created    12/12/13 3:31 PM
 * Author     Matt Thomas | matt@betweenbrain.com | http://betweenbrain.com
 * Support    https://github.com/betweenbrain/
 * Copyright  Copyright (C) 2013 betweenbrain llc. All Rights Reserved.
 * License    GNU GPL v3 or later
 */
?>
	<form action="index.php" method="post">
		<input type="text" name="term" size="32" />
		<input type="hidden" name="option" value="com_k2search" />
		<input type="hidden" name="view" value="k2search" />
	</form>

<h2>Results for "<?php echo $this->results['results']->term ?>"</h2>
<p>Displaying <?php echo $this->results['results']->count ?> results</p>
<?php unset($this->results['results']) ?>
<ol>
<?php foreach ($this->results as $result) : ?>
	<li>
	<h2><?php echo $result->title ?></h2>
	<p><?php echo $result->introtext ?></p>
	<p><?php echo print_r(json_decode($result->extra_fields), TRUE) ?></p>
	</li>
<?php endforeach; ?>
</ol>