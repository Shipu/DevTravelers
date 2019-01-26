{{-- select_from_array column --}}
<span>
	<?php
		if ($entry->{$column['name']} !== null) {
			if (is_array($entry->{$column['name']})) {
				$array_of_values = [];

				foreach ($entry->{$column['name']} as $key => $value) {
					$array_of_values[] = $column['options'][$value];
				}

				if (count($array_of_values) > 1) {
					echo implode(', ', $array_of_values);
				} else {
					echo $array_of_values;
				}
			} else {
				echo $column['options'][$entry->{$column['name']}];
			}
	    } else {
	    	echo "-";
	    }
	?>
</span>
