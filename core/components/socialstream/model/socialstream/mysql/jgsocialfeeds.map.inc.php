<?php
$xpdo_meta_map['jgSocialFeeds']= array (
  'package' => 'socialstream',
  'table' => 'social_feeds',
  'aggregates' => 
  array (
    'Account' => 
    array (
      'class' => 'jgSocialAccounts',
      'local' => 'social_account_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
  'fields' => 
  array (
    'social_account_id' => 0,
    'username' => '',
    'service' => '',
    'post_date' => NULL,
    'feed' => '',
    'html_feed' => '',
    'status' => 'pending',
    'post_url' => '',
    'author' => '',
    'email' => '',
    'copyright' => '',
    'likes' => 0,
    'dislikes' => 0,
    'followers' => 0,
    'post_id' => '0',
    'source' => '',
  ),
  'fieldMeta' => 
  array (
    'social_account_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
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
    'post_date' => 
    array (
      'dbtype' => 'int',
      'phptype' => 'int',
      'null' => true,
    ),
    'feed' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'html_feed' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'status' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '16',
      'phptype' => 'string',
      'null' => false,
      'default' => 'pending',
    ),
    'post_url' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'author' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'email' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'copyright' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '64',
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
    'post_id' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '32',
      'phptype' => 'string',
      'null' => true,
      'default' => '0',
    ),
    'source' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
  ),
);
