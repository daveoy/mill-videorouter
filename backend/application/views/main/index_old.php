<?php
// echo "<pre>";
// print_r($response['output']);
// echo "</pre>";
$parsedArray = array();

$inputs = array();

foreach($response['output'] as $outputKey => $output)
{

	$inputs[$output->Source][$outputKey] = $output;
	
}
// print_r($inputs);
// print_r("<br>");
// die();
?>
<html>

<div id="input">
	<form action="/main" method="POST">
		<span>Input List</span>
		<input type="submit" value="Save"/>
		<br/>
		<?php
			foreach($response['input'] as $inputKey => $input)
			{
			?>
				<span><?php echo $input->Label; ?> - <?php echo $input->Hardware; ?></span>
				
				<select name="<?php echo $inputKey; ?>">
				<?php
					foreach($response['output'] as $outputKey => $output)
					{
				?>
					<option <?php 
					if($output->Source == $inputKey) echo "selected"; ?> value ="<?php echo $outputKey?>"><?php echo $output->Label; ?> - <?php echo $output->Hardware; ?></option>
				<?php
					}
				?>
				</select>
				<br/>
			<?php
			}
		?>
		<input type="submit" value="Save"/>
	</form>
</div>
