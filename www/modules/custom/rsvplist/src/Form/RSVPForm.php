<?php
/**
 * @file
 * Contains \Drupal\rsvplist\Form\RSVPForm.php
 * A form to collect RSVP information from users.
 */

namespace Drupal\rsvplist\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class RSVPForm extends FormBase {
    /**
     * {@inheritdoc}
     * https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Form%21FormInterface.php/function/FormInterface%3A%3AgetFormId/10
     */
    public function getFormId() {
        return 'rsvp_form';
    }
    /**
     * {@inheritdoc}
     * https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Form%21FormInterface.php/function/FormInterface%3A%3AbuildForm/10
     */
    public function buildForm(array $form, FormStateInterface $form_state) { 
        // Attempt to get the fully loaded node object of the viewed page.
        $node = \Drupal::routeMatch()->getParameter('node');

        if (!(is_null($node))) {
            $nid = $node->id();
        }
        else {
            $nid = 0;
        }

        $form['email'] = [
            '#type' => 'email',
            '#title' => t('Email Address'),
            '#size' => 25,
            '#description' => t('We will send you updates to your email address.'),
            '#required' => TRUE,
        ];
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => t('RSVP'),
        ];
        $form['nid'] = [
            '#type' => 'hidden',
            '#value' => $nid,
        ];
        return $form;
    }
    /**
     * {@inheritdoc}
     * https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Form%21FormInterface.php/function/FormInterface%3A%3AsubmitForm/10
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $submitted_email = $form_state->getValue('email');
        $this->messenger()->addMessage(
            t('Thank you for your RSVP. We have recorded your email address as @email.', 
            ['@email' => $submitted_email]
            )
        );
    }
}