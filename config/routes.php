<?php
/**
 * Expressive routed middleware
 */

/** @var \Zend\Expressive\Application $app */
$app->get('/book/{id}/check-out', \App\Action\CheckOutAction::class, 'check-out');
$app->get('/book/{id}/check-in', \App\Action\CheckInAction::class, 'check-in');
