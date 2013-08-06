<?php
/**
 * @package WordPress
 * @subpackage CW
 * @since CW 1.0
 */
?>

	<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
		<div class="row">
			<div class="large-12 columns">
				<label for="s">Search:</label>
				<input type="text" value="" name="s" id="s">
			</div>
		</div>

		<div class="row">
			<div class="large-3 large-offset-7 columns">
				<input type="submit" id="searchsubmit" value="Search" class="button small secondary">
			</div>
		</div>
	</form>