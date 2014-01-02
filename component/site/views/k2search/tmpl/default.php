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
<div class="container">
	<div class="showing">
		<?php if (isset($this->results['results']->term)) : ?>
			<h3><?php echo $this->results['results']->count ?> results for "<?php echo $this->results['results']->term ?>"</h3>
		<?php endif ?>

		<select class="filters">
			<option data-filter="date" class="selected">Recently Added</option>
			<option data-filter="views">Most Viewed</option>
			<option data-filter="title">Alphabetical</option>
		</select>

		<div class="tag-lists">
			<ul class="tag-list">

			</ul>
		</div>
	</div>
	<div id="k2Container" class="itemListView isotope" style="position: relative; overflow: visible; height: 1344px;">

		<div class="itemList clearfix">
			<?php if (isset($this->results['results']->message)) : ?>
				<p><?php echo $this->results['results']->message ?></p>
			<?php endif ?>
			<?php unset($this->results['results']) ?>

			<?php foreach ($this->results as $result) : ?>
				<?php
				// Trim title if longer than 50
				if (strlen($result->title) >= 60)
				{
					// Trim to 35 chars
					$result->shortTitle = substr($result->title, 0, 52);
					// Trim non-alphanumeric and spaces off end
					$result->shortTitle = preg_replace('/[^a-z0-9]+$/i', '', $result->shortTitle);
					// Trim string back to nearest space
					$result->shortTitle = preg_replace('/[^\s]+$/i', '', $result->shortTitle);
					// Trim off space left over and add elipses
					$result->shortTitle = preg_replace('/[^a-z0-9]+$/i', '', $result->shortTitle) . '&hellip;';
				}
				else
				{
					$result->shortTitle = $result->title;
				}

				$plugins = parse_ini_string($result->plugins, false, INI_SCANNER_RAW);

				$result->tags          = $plugins['tags'];
				$result->link          = '';
				$result->videoDuration = $plugins['video_datavideoDuration'];
				$result->videoImage    = $plugins['video_datavideoImageUrl'];
				// Change image file name to use the smaller version
				$result->videoImage = str_replace('_902', '_280', $result->videoImage);
				?>

				<div class="<?php echo $result->tags ?> itemContainer">
					<div class="catItemView">

						<a class="blockContainer" href="<?php echo $result->link; ?>" title="<?php echo $result->title ?>">
							<div class="itemDescription">
								<p class="itemTitle"><?php echo $result->shortTitle; ?>
									<span class="duration"><?php echo $result->videoDuration ?></span></p>
							</div>
							<img src="<?php echo $result->videoImage; ?>" />
						</a>

						<div class="details">
							<p class="views">
								<?php echo number_format($result->hits); ?> <?php echo JText::_('K2_TIMES'); ?>
							</p>
							<h1 class="title"><?php echo $result->title; ?></h1>
							<p class="subtitle">
								<span class="duration"><?php echo $result->videoDuration ?></span> |
								<span class="date"><?php echo JHTML::_('date', $result->created, JText::_('K2_DATE_FORMAT_LC2')); ?></span>
							</p>

							<div class="catItemIntroText">
								<?php echo $result->introtext; ?>
							</div>
							<p>
								<a class="k2ReadMore" href="<?php echo $result->link; ?>">
									<?php echo JText::_('See Details'); ?>
								</a>
							</p>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
