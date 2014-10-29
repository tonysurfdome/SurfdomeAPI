<?php

class stock {
	
	public $sku;
	public $datetimestamp;
	public $qty;
	public function getFields() {
		return array(
			'sku' => $this->sku,
			'datetimestamp' => $this->datetimestamp,
			'qty' => $this->qty
		);
	}
}
?>