<?php defined('_JEXEC') or die;

/**
 * File       default.php
 * Created    12/12/13 3:31 PM
 * Author     Matt Thomas | matt@betweenbrain.com | http://betweenbrain.com
 * Support    https://github.com/betweenbrain/
 * Copyright  Copyright (C) 2013 betweenbrain llc. All Rights Reserved.
 * License    GNU GPL v3 or later
 */

// Get the current model so we can call functions within it
$model = $this->getModel();
?>
<div class="container">
	<?php if (count($model->getTagList())) :
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		$doc->addScript('http://cdn.guggenheim.org/lib/js/jquery.isotope.min.js');
		$doc->addScript(JURI::base(true) . '/templates/' . $app->getTemplate() . '/js/jquery.video-filter-init.min.js');
		?>
		<div class="showing">
			<h3></h3>
			<?php echo $this->results['results']->count ?> results for "<?php echo $this->results['results']->term ?>"
			<select class="filters">
				<option data-filter="date" class="selected">Recently Added</option>
				<option data-filter="views">Most Viewed</option>
				<option data-filter="title">Alphabetical</option>
			</select>

			<div class="tag-lists">
				<ul class="tag-list">
					<?php
					usort($this->tags, function ($a, $b)
					{
						return strcmp(strtolower($a['compare']), strtolower($b['compare']));
					});
					$index = 0;
					$break = null;
					$count = count($this->tags);
					$divisor = ceil($count / 3);
					foreach ($this->tags as $tag)
					{
						if ($index > 0)
						{
							// Check for a 3-3-1 layout type condition
							if (substr($count / 3, -3) == 333)
							{
								if (fmod($index, $divisor) == 0 && is_null($break))
								{
									echo ' </ul ><ul class="tag-list one-third" > ';
									$break = 1;
									// Shift one off of second column
								}
								elseif (fmod($index + 1, $divisor) == 0 && !is_null($break))
								{
									echo ' </ul ><ul class="tag-list one-third" > ';
									$break = 1;
								}
							}
							elseif (fmod($index, $divisor) == 0)
							{
								echo ' </ul ><ul class="tag-list one-third" > ';
							}
						}
						?>
						<li>
							<a class="cloud <?php echo $tag['alias'] ?>"
								href="#<?php echo $tag['alias'] ?>"
								title="<?php echo $tag['name'] ?>"
								data-filter=".<?php echo $tag['alias'] ?>"
								onClick="_gaq.push(['_trackEvent', 'Interaction', 'Video', 'Search Showing Filter: <?php echo $tag['nickname'] ?>']);">
								<?php echo $tag['nickname'] ?>
								<span class="count"><?php echo $tag['count'] ?></span>
							</a>
						</li>
						<?php
						$index++;
					} ?>
				</ul>
			</div>
		</div>
	<?php endif ?>
	<div id="k2Container" class="itemListView isotope" style="position: relative; overflow: visible; height: 1344px;">

		<div class="itemList clearfix">
			<?php foreach ($this->results as $result) : ?>
				<?php $result = $model->formatResult($result); ?>
				<div class="<?php echo $result->tags ?> itemContainer">
					<div class="catItemView">
						<a class="blockContainer" href="<?php echo $result->link; ?>"
							title="<?php echo $model->formatTitle($result->title); ?>">
							<div class="itemDescription">
								<p class="itemTitle"><?php echo $model->formatTitle($result->title); ?>
									<span class="duration"><?php echo $result->videoDuration ?></span></p>
							</div>
							<img src="<?php echo $result->videoImage; ?>" />
						</a>

						<div class="details">
							<p class="views">
								<?php echo number_format($result->hits); ?> <?php echo JText::_('K2_TIMES'); ?>
							</p>
							<h1 class="title"><?php echo $model->formatTitle($result->title); ?></h1>
							<p class="subtitle">
								<span class="duration"><?php echo $result->videoDuration ?></span> |
								<span
									class="date"><?php echo JHTML::_('date', $result->created, JText::_('K2_DATE_FORMAT_LC2')); ?></span>
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
