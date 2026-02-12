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
    // If viewing a node, get the fully loaded node object.
    $node = \Drupal::routeMatch()->getParameter('node');
    
    if (!(is_null($node))) {
      // Get the enabler service to check if the node is RSVP enabled.
      $enabler = \Drupal::service('rsvplist.enabler');
      if ($enabler->isEnabled($node)) {
        return AccessResult::allowedIfHasPermission($account, 'view rsvplist');
      }
    }
    return AccessResult::forbidden();
  }
}
