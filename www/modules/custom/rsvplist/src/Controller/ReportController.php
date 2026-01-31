<?php

/**
 * @file
 * Contains \Drupal\rsvplist\Controller\ReportController
 * https://www.drupal.org/docs/drupal-apis/routing-system/introductory-drupal-routes-and-controllers-example
 */

namespace Drupal\rsvplist\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

class ReportController extends ControllerBase {
    /**
     * Returns all of the RSVP nodes from the database.
     */
    protected function load() {
        try {
            $database = Database::getConnection();
            $select_query = $database->select('rsvplist', 'r');
            // Join the users, so we can get the entry creator's unsername.
            $select_query->join('users_field_data', 'u', 'r.uid = u.uid');
            // Join the node table, so we can get the event's name.
            $select_query->join('node_field_data', 'n', 'r.nid = n.nid');

            $select_query->fields('u', ['name']);
            $select_query->fields('n', ['title']);
            $select_query->fields('r', ['mail']);
            //$select_query->orderBy('created', 'DESC');
            $results = $select_query->execute()->fetchAll(\PDO::FETCH_ASSOC);
            return $results;
        }
        catch (\Exception $e) {
            \Drupal::messenger()->addStatus(t('Error loading RSVP entries: @message', ['@message' => $e->getMessage()]));
            return null;
        }
    }
  /**
   * Creates the RSVP List report page.
   * @return array
   * Render array for the RSVP report output.
   */
  public function report() {
    $content = [];

    $content['message'] = [
      '#type' => 'markup',
      '#markup' => t('Below is a list of all Event RSVPs including usernames, email addresses and event names.'),
    ];

    $headers = [
      t('Username'),
      t('Event Name'),
      t('Email Address'),
    ];

    $table_rows = $this->load();

    $content['table'] = [
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $table_rows,
      '#empty' => t('No RSVPs found.'),
    ];
    // Disable caching for this page.
    $content['#cache']['max-age'] = 0;

    return $content;
  }
}