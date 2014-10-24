<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/functions2.php');

$currentLetter = $_GET['letter'];

$type = $_GET['type'];

?>

<!DOCTYPE html>
<!--[if IE 7 ]>    <html lang="en-gb" class="isie ie7 oldie no-js"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en-gb" class="isie ie8 oldie no-js"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en-gb" class="isie ie9 no-js"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en-gb" class="no-js">
	<!--<![endif]-->
	<head>
		<title><?php echo ($origin == "" || $origin == "All Origins" ? "All " : explode(" ", $origin)[0] . " ") . ($type == "" || $type  == "All Types" ? "Literature" : $type); ?> - Hear a Tale</title>
		<?php
		include ($_SERVER['DOCUMENT_ROOT'] . '/globalHeader.php');
		?>

	</head>

	<body>

		<?php
		include ($_SERVER['DOCUMENT_ROOT'] . '/globalBody.php');
		?>


		<div class="span9" style="margin-left:5px; margin-right:5px;">
			<div style="clear: both;"></div>

			<?php

			$thisPage = "SOUTHERN_home.php" . ($type == "" ? "" : "?type=" . $type);
			$title = "Southern " . ($type == "" || $type  == "All Types" ? "Literature" : $type);
			$allData = getAllInCategory("Southern Literature");
			$showAllAll = ($type == "" || $type == "All Types");
			$catData = array();
			if($showAllAll) $catData = $allData;
			else{
				if($type == "All Types" || $type == "") $type = "/";
				foreach($allData as $work){
					if(strpos($work['Category'], $type) != 0){
						array_push($catData, $work);
					}
				}
			}
			?>

			<fieldset>
				<legend>
					<div style="display:inline-block;"> <a href="<?php echo $thisPage; ?>"><?php echo $title; ?></a> </div>
					<h6 style="display:inline-block;"><div class="letterPicker">
						<?php
							$letters = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
							foreach($letters as $letter){
								if($currentLetter == $letter){
									echo $letter;
								} else{
									echo "<a href='" . $thisPage . (strpos($thisPage, "?") == 0 ? "?" : "&") . "letter=" . $letter . "'>" . $letter . "</a>";
								}
								if($letter != "Z") echo " · ";
							}
						?>
					</div></h6>
				</legend>

			</fieldset>

			<div style="margin-top:-20px; border-top-style: solid; border-color: gray;">
				<?php southernTypeHeader($type == "" ? "All Types" : $type); ?>
			</div>


			<?php
			$workData = array();
			$authors = array();
			foreach($catData as $work){
				if($work['Author'] == "" || is_null($work['Author'])) continue;
				if(!in_array($work['Author'], $authors)){
					if($currentLetter == "" || strpos($work['Author'], $currentLetter) === 0){
						array_push($workData, $work);
						array_push($authors, $work['Author']);
					}

				}
			}
			if(count($workData) === 0){
				if($currentLetter != "") echo "There are no authors under <i>" . $currentLetter . "</i>.";
				else echo "There are no works or authors in this category.";
			}
			foreach($workData as $authorWork){

				?>

				<div class="authorCard">
					<a href="SOUTHERN_author.php?author=<?php echo $authorWork['Author']; ?>">
						<img src="Thumbnails/<?php echo $authorWork['ThumbnailImage']; ?>">
					</a>
					<div class="authorInfo">
					<h4 class="authorName">
						<a href="SOUTHERN_author.php?author=<?php echo $authorWork['Author']; ?>">
							<?php echo convertAuthorName($authorWork['Author']); ?>
						</a>
					</h4>
					<div class="authorWorks" dir="rtl" style="max-height:105px; width:115%; overflow:auto;">
					<div dir="ltr">
					<?php
						$works = getAllByAuthorOutOfPool($authorWork['Author'], $catData);
						$showAllAll = 0;
						if($showAllAll) shuffle($works);
						for($i = 0; $i <= ($showAllAll == 1 ? min(2, count($works) - 1) : count($works) - 1); $i++){
							$work = $works[$i];
							if($work['FileLocation'] == ""){
								echo "<p class='authorWork'><i>" . $work['Title'] . "</i> (Coming Soon)</p>";
							}else{
								echo "<p class='authorWork'><a href='SOUTHERN_viewer.php?url=" . $work['FileLocation'] . "'><i>" . $work['Title'] . "</i></a></p>";
							}
						}
					?>
					</div>
					</div>

				</div>
				</div>
				<div style="clear: both;"></div>

			<?php } ?>


		<?php
		include ($_SERVER['DOCUMENT_ROOT'] . '/globalFooter.php');
		?>