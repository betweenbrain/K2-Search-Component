<?php defined('_JEXEC') or die;

/**
 * File       default.php
 * Created    12/12/13 3:31 PM
 * Author     Matt Thomas | matt@betweenbrain.com | http://betweenbrain.com
 * Support    https://github.com/betweenbrain/
 * Copyright  Copyright (C) 2013 betweenbrain llc. All Rights Reserved.
 * License    GNU GPL v3 or later
 */

$tags = array();
$tags[$this->category->name]['hash'] = '#';
$tags[$this->category->name]['class'] = 'selected';
$tags[$this->category->name]['compare'] = '';
$tags[$this->category->name]['count'] = count($this->leading);
$tags[$this->category->name]['data-filter'] = '*';
$tags[$this->category->name]['name'] = 'All in ' . $this->category->name;
$tags[$this->category->name]['nickname'] = 'All in ' . $this->category->name;
foreach ($this->leading as $item)
{
	if (isset($item->tags) && count($item->tags))
	{

		foreach ($item->tags as $tag)
		{
			if (isset($tag->name, $tags))
			{
				$tags[$tag->name]['count']++;
			}
			else
			{
				$tags[$tag->name]['count'] = 1;
			}
			$tags[$tag->name]['name']        = $tag->name;
			$tags[$tag->name]['class']       = '';
			$tags[$tag->name]['compare']     = $tag->name;
			$tags[$tag->name]['alias']       = JFilterOutput::stringURLSafe($tag->name);
			$tags[$tag->name]['hash']        = '#' . JFilterOutput::stringURLSafe($tag->name);
			$tags[$tag->name]['data-filter'] = '.' . JFilterOutput::stringURLSafe($tag->name);

			// Truncate tag name at colon if it contains one
			$tag->nickname = strpos($tag->name, ':') ? strstr($tag->name, ':', true) : $tag->name;
			// Shorten remaining text to three words if it has more than 4
			if (substr_count($tag->nickname, ' ') > 3)
			{
				$split = explode(' ', $tag->nickname, 4);
				unset($split[3]);
				$tag->nickname = implode(' ', $split);
			}
			$tags[$tag->name]['nickname'] = $tag->nickname;
			$tags[$tag->name]['link']     = $tag->link;
		}
	}
}
$break = null;
$count = count($tags);
$divisor = ceil($count / 3);
?>
<div class="container">
	<?php if (count($tags) > 1) :
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		$doc->addScript('http://cdn.guggenheim.org/lib/js/jquery.isotope.min.js');
		$doc->addScript(JURI::base(true) . '/templates/' . $app->getTemplate() . '/js/jquery.video-filter-init.min.js');
		?>
		<div class="showing">
			<h3>Showing</h3>
			<select class="filters">
				<option data-filter="date" class="selected">Recently Added</option>
				<option data-filter="views">Most Viewed</option>
				<option data-filter="title">Alphabetical</option>
			</select>

			<div class="tag-lists">
				<ul class="tag-list">
					<?php
					usort($tags, function ($a, $b)
					{
						return strcmp(strtolower($a['compare']), strtolower($b['compare']));
					});
					$index = 0;
					foreach ($tags as $tag)
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
							<a class="cloud <?php echo $tag['class'] ?>" href="<?php echo $tag['hash'] ?>" title="<?php echo $tag['name'] ?>" data-filter="<?php echo $tag['data-filter'] ?>" onClick="_gaq.push(['_trackEvent', 'Interaction', 'Video', 'Showing Filter: <?php echo $tag['nickname'] ?>']);"><?php echo $tag['nickname'] ?>
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

				$result->tags          = str_replace(',', ' ', $plugins['tags']);
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
