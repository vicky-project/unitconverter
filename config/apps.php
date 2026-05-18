<?php

return [
  'id' => 'unitconverter',
  'name' => 'Unit Converter',
  'description' => 'Konversi satuan presisi tinggi untuk berbagai sistem satuan',
  'icon_emoji' => '🔄',
  'render_type' => 'iframe',
  'render_config' => [
    'url' => env('APP_URL') . '/apps/unit-converter'
  ]
];