<?php include(ROOT_DIR.'/views/layouts/header.php') ?>

<div class="container">
	<h1>Google SEO Ranker</h1>
	<form id="seo-ranker-search" method="POST">
		<label for="keyword">Keyword</label><input type="text" id="keyword" name="keyword" value="creditorwatch" />
		<label for="url">URL</label><input type="text" id="url" name="url" value="creditorwatch.com.au" />
		<input type="submit" name="submit" value="Submit">
	</form>
	<?php if (isset($messages['error']) && count($messages['error'])): ?>
			<div class="error-messages"><?php echo implode('<br />', $messages['error']); ?></div>
	<?php else: ?> 

		<?php if (!empty($ranks)): ?>
			<div class="rank-info">Google ranking positions for keyword "<?php echo htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8'); ?>" and url "<?php echo htmlspecialchars($url, ENT_QUOTES, 'UTF-8'); ?>"</div>
			<div class="rank-positions">
				<table border="1">
					<th>Date</th><th>Rank Positions</th>
				<?php foreach ($ranks as $date => $rank): ?>
					<tr><td><?php echo $date; ?></td><td><?php echo implode(', ', $rank); ?></td></tr>
				<?php endforeach; ?>
				</table>
			</div>
		<?php endif; ?>

	<?php endif; ?>

</div><!-- .container -->

<?php include(ROOT_DIR.'/views/layouts/footer.php') ?>
