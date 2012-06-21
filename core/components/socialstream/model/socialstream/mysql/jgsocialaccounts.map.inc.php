<?php
$xpdo_meta_map['jgSocialAccounts']= array (
  'package' => 'socialstream',
  'table' => 'social_accounts',
  'composites' => 
  array (
    'Feed' => 
    array (
      'class' => 'jgSocialFeeds',
      'local' => 'id',
      'foreign' => 'social_account_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'fields' => 
  array (
    'username' => '',
    'service' => '',
    'auto_approve' => '0',
    'create_date' => NULL,
    'active' => '1',
    'get_feeds' => '1',
    'name' => '',
    'description' => '',
    'feed_url' => '',
    'public_url' => '',
    'likes' => 0,
    'dislikes' => 0,
    'followers' => 0,
    'get_date' => 0,
  ),
  'fieldMeta' => 
  array (
    'username' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'service' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
      'index' => 'index',
    ),
    'auto_approve' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '3',
      'phptype' => 'string',
      'null' => false,
      'default' => '0',
    ),
    'create_date' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
    ),
    'active' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '3',
      'phptype' => 'string',
      'null' => false,
      'default' => '1',
    ),
    'get_feeds' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '3',
      'phptype' => 'string',
      'null' => true,
      'default' => '1',
    ),
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'description' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'feed_url' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'public_url' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'likes' => 
    array (
      'dbtype' => 'int',
      'precision' => '12',
      'phptype' => 'int',
      'null' => true,
      'default' => 0,
    ),
    'dislikes' => 
    array (
      'dbtype' => 'int',
      'precision' => '12',
      'phptype' => 'int',
      'null' => true,
      'default' => 0,
    ),
    'followers' => 
    array (
      'dbtype' => 'int',
      'precision' => '12',
      'phptype' => 'int',
      'null' => true,
      'default' => 0,
    ),
    'get_date' => 
    array (
      'dbtype' => 'int',
      'precision' => '12',
      'phptype' => 'int',
      'null' => true,
      'default' => 0,
    ),
  ),
);
