<?php
/**
 * Receipt Printer Service
 *
 * @author Radiant C. Juan <radiantcjuan@gmail.com>
 *
 * @copyright RCJuan Food Hub 2021
 */

namespace App\Services;

use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\PrintConnectors\CupsPrintConnector;
use Mike42\Escpos\PrintConnectors\DummyPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\RawbtPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class ReceiptPrinterService {

  /**
   * @var string the name of the printer use(lpstat -a) to check the printer name
   */
  protected $device_os;

  /**
   * @var string the name of the printer use(lpstat -a) to check the printer name
   */
  protected $printer_name;

  /**
   * @var string store name
   */
  protected $store_name;

  /**
   * @var string store address
   */
  protected $store_address;

  /**
   * @var string store phone
   */
  protected $store_phone;

  /**
   * @var string store email
   */
  protected $store_email;

  /**
   * @var string store website
   */
  protected $store_website;

  /**
   * @var string order transaction ID
   */
  protected $transaction_id;

  /**
   * @var array $ordered_items;
   */
  protected $ordered_items;

  /**
   * @var array $promo_setup;
   */
  protected $promo_setup;

  /**
   * @var array $promo_setup;
   */
  protected $order_total;

  /**
   * @var array franchise name;
   */
  protected $franchise_name;

    /**
   * @var array $order_date;
   */
  protected $order_date;

  public function __construct() {
    $this->device_os = SiteSettingsHelper::get('app.device_os');
    $this->printer_name = SiteSettingsHelper::get('app.printer_name');
    $this->store_name = SiteSettingsHelper::get('app.store_name');
    $this->store_address = SiteSettingsHelper::get('app.store_address');
    $this->store_phone = SiteSettingsHelper::get('app.store_phone');
    $this->store_email = SiteSettingsHelper::get('app.store_email');
    $this->store_website = SiteSettingsHelper::get('app.store_website');
    $this->franchise_name = '';
    $this->order_date = '';
  }

  /**
   * prints the docket
   * @return void
   */
  public function print() {
    $connector = new FilePrintConnector(public_path().'/temp.txt');
    // $connector = new FilePrintConnector('php://output');

    $printer = new Printer($connector);
    $printer->initialize();
    $printer->setJustification($printer::JUSTIFY_CENTER);
    $printer->setEmphasis(true);
    $printer->text($this->store_name . "\n");
    $printer->text($this->store_address . "\n");
    $printer->text("Contact Us: " . $this->store_phone . "\n");
    $printer->feed(2);
    $printer->setJustification($printer::JUSTIFY_LEFT);
    $printer->text($this->franchise_name . "\n");
    $printer->text('Printed Date: ' . date('Y.m.d H:i:s') . "\n");
    $printer->text('Transaction Date: ' . date('Y.m.d H:i:s', strtotime($this->order_date)) . "\n");
    $printer->text('Receipt #: ' . $this->transaction_id . "\n");
    $printer->feed();
    $printer->setEmphasis(false);
    $printer->setJustification($printer::JUSTIFY_LEFT);
    $printer->text("Item    Price    Disc.    Qty    Total");
    $printer->feed();
    $printer->text('------------------------------------');
    $printer->feed();
    //print items
    foreach ($this->ordered_items as $ordered_items) {
      $printer->setJustification($printer::JUSTIFY_LEFT);
      $printer->setEmphasis(true);
      $printer->text($ordered_items['title']);
      $printer->setEmphasis(false);
      $printer->feed();
      $printer->text("      " . number_format($ordered_items['price'], 2, '.', ',') . "    " . (isset($ordered_items['total_discount']) ? number_format($ordered_items['total_discount'], 2, '.', ',') : '0.00') . "    " . $ordered_items['quantity'] . "    " . number_format($ordered_items['total_cost'], 2, '.', ','));
      $printer->feed();
    }
    $printer->setJustification($printer::JUSTIFY_LEFT);
    $printer->setEmphasis(true);
    $printer->feed();
    $printer->text('Sub total: $' . number_format($this->order_total->total_to_be_paid, 2, '.', ','));
    $printer->setEmphasis(false);
    $printer->feed();
    if ($this->promo_setup) {
      $printer->setJustification($printer::JUSTIFY_LEFT);
      $printer->setEmphasis(true);
      $printer->text('Total Discount: -$' . number_format($this->promo_setup['promo_code_total_discount'], 2, '.', ','));
      $printer->setEmphasis(false);
    }
    $printer->feed(2);
    $printer->setEmphasis(true);
    $printer->setTextSize(2, 2);
    $total_amount = (isset($this->promo_setup['new_amount_to_be_paid'])) ? number_format($this->promo_setup['new_amount_to_be_paid'], 2, '.', ',') : number_format($this->order_total->total_to_be_paid, 2, '.', ',');
    $printer->text('Total Amount: $' . $total_amount);
    $printer->setEmphasis(false);
    $printer->setTextSize(1, 1);
    $printer->feed(2);
    $printer->setJustification($printer::JUSTIFY_CENTER);
    $printer->text('Thank you for ordering!');
    $printer->feed(2);
    $printer->cut();
    $printer->close();
  }

  /**
   * set transaction ID
   * @param int $transaction_id;
   * @return void
   */
  public function set_transaction_id($transaction_id) {
    $this->transaction_id = $transaction_id;
  }

  /**
   * set ordered items
   * @param int $ordered_items;
   * @return void
   */
  public function set_items($ordered_items) {
    $this->ordered_items = $ordered_items;
  }

  /**
   * set promo setup
   * @param int $ordered_items;
   * @return void
   */
  public function set_promo_setup($promo_setup) {
    $this->promo_setup = $promo_setup;
  }

  /**
   * set order total
   * @param int $ordered_items;
   * @return void
   */
  public function set_order_total($order_total) {
    $this->order_total = $order_total;
  }

  /**
   * set franchise
   */
  public function set_franchisee($franchise_name) {
    $this->franchise_name = $franchise_name;
  }

  /**
   * Set Order Date
   */
  public function set_order_date($order_date) {
    $this->order_date = $order_date;
  }
}