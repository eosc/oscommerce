<?php
/*
  osCommerce Online Merchant $osCommerce-SIG$
  Copyright (c) 2010 osCommerce (http://www.oscommerce.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  use osCommerce\OM\Core\OSCOM;
?>

<h1><?php echo $OSCOM_Template->getIcon(32) . osc_link_object(OSCOM::getLink(), $OSCOM_Template->getPageTitle()); ?></h1>

<?php
  if ( $OSCOM_MessageStack->exists() ) {
    echo $OSCOM_MessageStack->get();
  }
?>

<div class="infoBox">
  <h3><?php echo osc_icon('trash.png') . ' ' . OSCOM::getDef('action_heading_batch_delete_currency'); ?></h3>

  <form name="aDeleteBatch" class="dataForm" action="<?php echo OSCOM::getLink(null, null, 'BatchDelete&Process'); ?>" method="post">

  <p><?php echo OSCOM::getDef('introduction_batch_delete_currencies'); ?></p>

<?php
  $check_default_flag = false;

  $Qcurrencies = $OSCOM_Database->query('select currencies_id, title, code from :table_currencies where currencies_id in (":currencies_id") order by title');
  $Qcurrencies->bindRaw(':currencies_id', implode('", "', array_unique(array_filter(array_slice($_POST['batch'], 0, MAX_DISPLAY_SEARCH_RESULTS), 'is_numeric'))));
  $Qcurrencies->execute();

  $names_string = '';

  while ( $Qcurrencies->next() ) {
    if ( $Qcurrencies->value('code') == DEFAULT_CURRENCY ) {
      $check_default_flag = true;
    }

    $names_string .= osc_draw_hidden_field('batch[]', $Qcurrencies->valueInt('currencies_id')) . '<b>' . $Qcurrencies->value('title') . ' (' . $Qcurrencies->value('code') . ')</b>, ';
  }

  if ( !empty($names_string) ) {
    $names_string = substr($names_string, 0, -2) . osc_draw_hidden_field('subaction', 'confirm');
  }

  echo '<p>' . $names_string . '</p>';

  if ( $check_default_flag === false ) {
    echo '<p>' . osc_draw_button(array('priority' => 'primary', 'icon' => 'trash', 'title' => OSCOM::getDef('button_delete'))) . ' ' . osc_draw_button(array('href' => OSCOM::getLink(), 'priority' => 'secondary', 'icon' => 'close', 'title' => OSCOM::getDef('button_cancel'))) . '</p>';
  } else {
    echo '<p><b>' . OSCOM::getDef('introduction_delete_currency_invalid') . '</b></p>';

    echo '<p>' . osc_draw_button(array('href' => OSCOM::getLink(), 'priority' => 'primary', 'icon' => 'triangle-1-w', 'title' => OSCOM::getDef('button_back'))) . '</p>';
  }
?>

  </form>
</div>
