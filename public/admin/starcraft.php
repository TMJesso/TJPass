<?php 
	require_once '../../includes/initialize.php';
	if (only_run_once() <= 0) {
		for ($x = 0; $x < 21; $x++) {
			$star = start_star();
			$this_code = get_next_code($x);
			$star->code = join(' ', array_keys($this_code));
			$star->effect = join(' ', array_values($this_code));
			save_star($star);
			unset($star);
		}
	}
	$cheats = Star::find_all_sc_by_code();
	
	
?>
<?php include_layout_template('admin_header.php'); ?>
<div class="row">
	<div class="large-2 medium-2 columns">
		&nbsp;
	</div>
	<div class="large-8 medium-8 columns">
		<table>
			<tr>
				<th>Cheat Code</th>
				<th>Effect</th>
			</tr>
			<?php foreach ($cheats as $star) { ?>
				<tr>
					<td class="strong"><?php echo $star->code; ?></td>
					<td><?php echo $star->effect; ?></td>
				</tr>
			<?php } ?>
		</table>
	</div>
	<div class="large-2 medium-2 columns">
		&nbsp;
	</div>
</div>
<?php include_layout_template('admin_footer.php'); ?>

<?php 
	function start_star() {
		$star = new Star();
		$star->sc_id = generate_random_id();
		return $star;
	}
	
	function get_next_code($x) {
		$this_code = generate_array_codes();
		return $this_code[$x];
	}
		
	function generate_array_codes() {
		$codes = array(array());
		$codes[0]["show me the money"] = hent("10,000 Minerals and Gas");
		$codes[1]["whats mine is mine"] = hent("500 Crystals");
		$codes[2]["breathe deep"] = hent("500 Vespene Gas");
		$codes[3]["medieval man"] = hent("All Research Abilities");
		$codes[4]["modify the phase variance"] = hent("Build Anything");
		$codes[5]["staying alive"] = hent("Continue Playing After Win");
		$codes[6]["ophelia"] = hent("Enable Mission Select");
		$codes[7]["noglues"] = hent("Enemy Can't Use Psionic Storm");
		$codes[8]["operation cwal"] = hent("Faster Building (also affects enemy A.I. players so be careful using)");
		$codes[9]["the gathering"] = hent("Free Unit Spells/Abilities");
		$codes[10]["something for nothing"] = hent("Free Upgrades");
		$codes[11]["radio free zerg"] = hent("hidden zerg song, sung by the Overmind (you NEED the Brood War expansion and only works when playing as Zerg)");
		$codes[12]["game over man"] = hent("Instant Loss");
		$codes[13]["there is no cow level"] = hent("Instant Win");
		$codes[14]["power overwhelming"] = hent("Invincible Units");
		$codes[15]["war aint what it used to be"] = hent("No Fog of War");
		$codes[16]["food for thought"] = hent("No Supply Limit");
		$codes[17]["protoss# (Replace # with number of mission.)"] = hent("Protoss Level Skip");
		$codes[18]["black sheep wall"] = hent("Reveal Entire Map");
		$codes[19]["terran# (Replace # with number of mission.)"] = hent("Terran Level Skip");
		$codes[20]["zerg# (Replace # with number of mission.)"] = hent("Zerg Level Skip");
		return $codes;
	}
	
	function only_run_once() {
		$num = Star::count_sc();
		return $num;
	}
	
	function save_star($obj) {
		$obj->save();
	}
?>
