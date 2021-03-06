<?php

/**
 * Old import_pvx.php / PART 1 / Fetching data.
 * 
 * 
 * 
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * 
 * 
 *    AAAAAADDDDDDDD THIS WHEN GOING INTO PRODUCTION:
 * 		$filename = basename(__FILE__);
		$historyID = logscript($filename);
 * 
 * 
 * updateprogresstime();
 * UpdateScriptHistory($historyID);
 * 
 * 
 * 
 */
include('../ms-dbfunc.php');
require_once 'json/despatch.php';

class PdfDespatch{
	private $despatch = null;
	public $despatch_count = 0;

	function __construct() {
		if (null == $this->despatch) {
			$this->despatch = new Despatch();
		}
	}

	public function run() {
		return $this->getdata();
	}
	



	public function getData() {
		$ms_connect = ms_connect();
		$sql = "select 
					s.SalesOrderNumber AS carrierConsignmentNumber,
					s.CustomerPurchaseOrderReferenceNumber AS newMediaOrderNumber,
					s.TotalSale AS OrderGrossValue,
					s.Discount AS DiscountValue,
					s.Email AS PersonalEmail,
					s.ShippingCost as DeliveryGrossValue,
					 c.Reference as customerUrn,
					c.Name as shipment_Contact,
					ship.Line1 as shipment_Address1,
					ship.Line2 as shipment_Address2,
					ship.TownCity as shipment_Town,
					sc.Code as shipment_CountryCode,
					ship.PostCode as shipment_postCode,
					sc.Name as shipment_CountryName,
					s.ContactName as billing_ForeNames,
					bill.Line1 as billing_Add1,
					bill.Line1 as billing_Add2,
					bill.TownCity as billing_AddTown,
					bill.PostCode as billing_AddPostCode,
					sb.Code as billing_AddCountryCode,
					sb.Name as billing_CountryName,

					si.Line as itemNumber,
					si.SalePrice as ItemUnitPrice,
					it.ItemCode as Sku,
					si.QuantityOrdered as Quantity, 
					it.Name as itemName,
					s.ShippingCost
				from
					salesorder s
					join
					SalesOrderItem si on (s.SalesOrderId = si.SalesOrderId)
					join
					ItemType it on (si.ItemTypeId = it.ItemTypeId)
					join
					[Address] as ship on (s.ShippingAddressId = ship.AddressId)
					join 
					Country as sc on (ship.CountryId = sc.CountryId)
					join
					[Address] as bill on (s.invoiceAddressId = bill.AddressId)
					join
					Country as sb on (bill.CountryId = sb.CountryId)
					join
					Account as c on (s.CustomerId = c.AccountId)
					where
					s.SalesOrderNumber ='ST2865424'";

		$arr_data = ms_query_all_assoc($sql);

		return $this->process($arr_data);
	}

	public function process($arr_data) {

		foreach ($arr_data as $k => $v) {
			$despatch = null;
			$linecount = count($arr_data[$k]);
			$arr_line_data = $v[0];


			$this->despatch->id = $arr_line_data['carrierConsignmentNumber'];
			$this->despatch->orderId = $arr_line_data['newMediaOrderNumber'];
			//$this->despatch->OAOrderNumber = $arr_line_data['orderNumber'];
			$this->despatch->customerUrn = $arr_line_data['customerUrn'];
			$this->despatch->updatedAt = date('Y-m-d H:i:s');
			$this->despatch->shippedAt = date('Y-m-d H:i:s');
			$this->despatch->email = $arr_line_data['PersonalEmail'];
			$this->despatch->cost =  (float)$arr_line_data['DeliveryGrossValue'];
			$this->despatch->totalSale = $arr_line_data['OrderGrossValue'];
			$this->despatch->discount = $arr_line_data['DiscountValue'];
			//$this->despatch->orderDate =  $arr_line_data['OrderDate'];


			$shippingAddress = new Address();
			$shippingAddress->firstName = empty($arr_line_data['shipment_CompanyName']) ?  $arr_line_data['shipment_Contact'] : $arr_line_data['shipment_CompanyName']." C/O ".  $arr_line_data['shipment_Contact'];
			$shippingAddress->lastName = '';
			$shippingAddress->address1 = $arr_line_data['shipment_Address1'];
			$shippingAddress->address2 = $arr_line_data['shipment_Address2'];
			$shippingAddress->zipcode = $arr_line_data['shipment_postCode'];
			$shippingAddress->city = $arr_line_data['shipment_Town'];
			$shippingAddress->country = $arr_line_data['shipment_CountryCode'];
			$shippingAddress->countryName = $arr_line_data['shipment_CountryName'];


			$this->despatch->shippingAddress = $shippingAddress->getFields();

			$invoiceAddress = new Address();
			$invoiceAddress->firstName = $arr_line_data['billing_ForeNames'];
			$invoiceAddress->lastName = '';
			$invoiceAddress->address1 = $arr_line_data['billing_Add1'];
			$invoiceAddress->address2 = $arr_line_data['billing_Add2'];
			$invoiceAddress->zipcode = $arr_line_data['billing_AddPostCode'];
			$invoiceAddress->city = $arr_line_data['billing_AddTown'];
			$invoiceAddress->country = $arr_line_data['billing_AddCountryCode'];
			$invoiceAddress->countryName = $arr_line_data['billing_CountryName'];

			$this->despatch->invoiceAddress = $invoiceAddress->getFields();

			$items = array();
			$i = 0;
			while ($i < $linecount) {

				$arr_line_data = $v[$i];

				$item = new Item();
				$item->sku = $arr_line_data['Sku'];
				$item->name = $arr_line_data['itemName'];
				$item->price = (float)$arr_line_data['ItemUnitPrice'];
				$items[] = $item->getFields();
				$i++;
			}

			$this->despatch->items = $items;
			$despatch = $this->despatch->getFields();
			$this->despatch_count= count($despatch);

			return json_encode($despatch);
		}
	}
}