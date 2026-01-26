<?php

/**
 * @file
 * Create a block to display the RSVP form from the RSVPForm.php file.
 * https://www.drupal.org/docs/drupal-apis/block-api/block-api-overview
 */

namespace Drupal\rsvplist\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
/**
 * Provides an 'RSVP' main block.
 *
 * @Block(
 *   id = "rsvp_block",
 *   admin_label = @Translation("The RSVP Block"),
 * )
 */
class RSVPBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    // return [
    //   '#type' => 'markup',
    //   '#markup' => t('This is the RSVP block.'),
    // ];
    return \Drupal::formBuilder()->getForm('Drupal\rsvplist\Form\RSVPForm');
  }
  /**
   * {@inheritdoc}
   */
  public function blockAccess(AccountInterface $account) {
    $node = \Drupal::routeMatch()->getParameter('node');
    
    if (!(is_null($node))) {
      return AccessResult::allowedIfHasPermission($account, 'view rsvplist');
    }
    return AccessResult::forbidden();
  }
}
