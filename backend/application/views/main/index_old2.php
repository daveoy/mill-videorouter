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

<div id="container">
	<div id="header">
		<h1>
			Video Router
		</h1>
	</div>
	<div id="content-container">
		<div id="content">
			<h2>
				CHOOSE YOUR MACHINES FLOOR
			</h2>
			<p>
				1
			</p>
			<p>
				2
			</p>
			<p>
				3
			</p>
			<p>
				4
			</p>
			<p>
				5
			</p>
			<p>
				6
			</p>
			<p>
				7
			</p>
			<p>
				8
			</p>
		</div>
		<div id="aside">
			<h3>
				CHOOSE YOUR OUTPUT FLOOR
			</h3>
			<p>
				1
			</p>
			<p>
				2
			</p>
			<p>
				3
			</p>
			<p>
				4
			</p>
			<p>
				5
			</p>
			<p>
				6
			</p>
			<p>
				7
			</p>
			<p>
				8
			</p>
		</div>
	</div>
</div>




<div class="container theme-showcase">
	<div id="input">
		<form action="/main" method="POST">
			<div class="row content-head">
				<span class="head-text">Want to connect to an Output? Simply choose your machine and its output, then you're done!</span>
			</div>
			<div class="row content-center">
				<!-- <div class="col-sm-4"> -->
					<div class="input box">
						<h2>Input List</h2>
						<?php
						foreach($response['input'] as $inputKey => $input)
						{
							// echo $input->Label . " : " ;echo $input->Hardware; echo " - Source: " . $input->Source; echo "<br/>";
						?>
						<div class="span-button">
							<div class='span-content'>
								<div>
									<span><?php echo $input->Label; ?></span>
								</div>
							</div>
						</div>
						<?php
						}
						?>
					</div>
					<div class="output box">
						<h2>Output List</h2>
						<?php
						foreach($response['output'] as $outputKey => $output)
						{
						?>
						<div class="span-button">
							<div class='span-content'>
								<div>
									<span><?php echo $output->Label; ?></span>
								</div>
							</div>
						</div>
						<?php
						}
						?>
					</div>
				<!-- </div> -->
			</div>	
		</form>
	</div>
</div>

