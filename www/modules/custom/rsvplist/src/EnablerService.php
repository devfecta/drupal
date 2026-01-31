<?php

/**
 * @file
 * Contains \Drupal\rsvplist\EnablerService
 * https://www.drupal.org/docs/drupal-apis/services-and-dependency-injection/structure-of-a-service-file
 * https://api.drupal.org/api/drupal/servcies
 */

namespace Drupal\rsvplist;

use Drupal\Core\Database\Connection;
use Drupal\node\Entity\Node;

class EnablerService {
  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database_connection;
  /**
   * Constructs a EnablerService object.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection.
   */
  public function __construct(Connection $connection) {
    $this->database_connection = $connection;
  }

  /**
   * Check if RSVP is enabled for a given node.
   *
   * @param \Drupal\node\Entity\Node $node
   *   The node to check.
   *
   * @return bool
   *   TRUE if RSVP is enabled, FALSE otherwise.
   */
  public function isEnabled(Node $node) {
    if ($node->isNew()) {
      return FALSE;
    }
    try {
        $select_query = $this->database_connection->select('rsvplist_enabled', 'r');
        $select_query->fields('r', ['nid']);
        $select_query->condition('r.nid', $node->id());
        $results = $select_query->execute();

        return !(empty($results->fetchCol()));

    } catch (\Exception $e) {
        \Drupal::messenger()->addError(t('Unable to determine RSVP settings: @message', ['@message' => $e->getMessage()]));
        return null;
    }
  }

/**
 * Enable or disable RSVP for a given node.
 *
 * @param \Drupal\node\Entity\Node $node
 *   The node to update.
 * @param bool $enabled
 *   TRUE to enable RSVP, FALSE to disable.
 */
  public function setEnabled(Node $node) {
    try {
      if (!($this->isEnabled($node))) {
        // 
        $insert_query = $this->database_connection->insert('rsvplist_enabled');
        $insert_query->fields(['nid']);
        $insert_query->values([$node->id()]);
        $insert_query->execute();
      }
    } catch (\Exception $e) {
      \Drupal::messenger()->addError(t('Unable to update RSVP settings: @message', ['@message' => $e->getMessage()]));
    }
  }

public function deleteEnabled(Node $node) {
    try {
        // Disable RSVP for the node.
        $delete_query = $this->database_connection->delete('rsvplist_enabled');
        $delete_query->condition('nid', $node->id());
        $delete_query->execute();
    } catch (\Exception $e) {
      \Drupal::messenger()->addError(t('Unable to delete RSVP settings: @message', ['@message' => $e->getMessage()]));
    }
  }
}