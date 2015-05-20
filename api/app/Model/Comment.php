<?php

class Comment extends AppModel {
  public $belongsTo = [
      'User' => [
          'fields' => ['User.id', 'fullname', 'username']
      ],
      'Coupon' => [
          'counterCache' => true,
          'foreignKey' => 'coupon_id',
      ],
      'Deal' => [
          'counterCache' => true,
          'foreignKey' => 'deal_id',
      ]
  ];
}