<?php

/**
 * @file
 * Contains the settings for administering the RSVP Form.
 */

namespace Drupal\rsvplist\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class RSVPSettingsForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'rsvplist_admin_settings';
  }
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'rsvplist.settings',
    ];
  }
  /**
   * {@inheritdoc}
   */  
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Get all the node content types. Options for the checkboxes.
    $types = node_type_get_names();
    // Get the rsvplist.settings configuration settings above, which is from the rsvplist.settings file.
    $config = $this->config('rsvplist.settings');
    // Automatically creates a Save button in this type of 
    $form['rsvplist_types'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('The content types to enable RSVP collection for'),
      '#default_value' => $config->get('allowed_types'),
      '#options' => $types,
      '#description' => $this->t('On the specified node types, an RSVP option 
        will be available and can be enabled while the noe is being edited.'),
    ];

    return parent::buildForm($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
      $selected_allowed_types = array_filter($form_state->getValue('rsvplist_types'));
      sort($selected_allowed_types);
      // Save the configuration settings.
      $this->config('rsvplist.settings')
        ->set('allowed_types', $selected_allowed_types)
        ->save();
      parent::submitForm($form, $form_state);
  }
}