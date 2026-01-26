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
     * Building Form
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
            '#type' => 'textfield',
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
     * https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Form%21FormBase.php/function/FormBase::validateForm/10
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        $email = $form_state->getValue('email');
        if (!\Drupal::service('email.validator')->isValid($email)) {
            // https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Form%21FormState.php/function/FormState::setErrorByName/10
            $form_state->setErrorByName(
                'email', 
                t('The email address @mail is not valid.', 
                ['@mail' => $email]
                )
            );
        }
    }
    /**
     * {@inheritdoc}
     * https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Form%21FormInterface.php/function/FormInterface%3A%3AsubmitForm/10
     * Database Connection
     * https://www.drupal.org/docs/drupal-apis/database-api/instantiating-a-database-connection-object
     * Query Types
     * https://www.drupal.org/docs/develop/drupal-apis/database-api/static-queries
     * https://www.drupal.org/docs/7/api/database-api/dynamic-queries/introduction-to-dynamic-queries
     * INSERT
     * https://www.drupal.org/docs/drupal-apis/database-api/insert-queries
     * UPDATE
     * https://www.drupal.org/docs/drupal-apis/database-api/update-queries
     * MERGE
     * https://www.drupal.org/docs/8/api/database-api/merge-queries
     * DELETE
     * https://www.drupal.org/docs/drupal-apis/database-api/delete-queries
     * Result Interation
     * https://www.drupal.org/docs/develop/drupal-apis/database-api/result-sets
     * Error Handling
     * https://www.drupal.org/docs/drupal-apis/database-api/error-handling
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        // $submitted_email = $form_state->getValue('email');
        // $this->messenger()->addMessage(
        //     t('Thank you for your RSVP. We have recorded your email address as @email.', 
        //     ['@email' => $submitted_email]
        //     )
        // );
        try {
          // Get current user ID
          $uid = \Drupal::currentUser()->id();
          // Get the full user object.
          $full_user = \Drupal\user\Entity\User::load($uid);
          // Get values from the form.
          $nid = $form_state->getValue('nid');
          $email = $form_state->getValue('email');
          $current_time = \Drupal::time()->getRequestTime();

          // Insert RSVP record into the database.
          $query = \Drupal::database()->insert('rsvplist');
          // Set the fields and values.
          // Specify the fields.
          $query->fields([
              'uid',
              'nid',
              'mail',
              'created',
          ]);
          // Set the values.
          $query->values([
              $uid,
              $nid,
              $email,
              $current_time,
          ]);
          // Execute the query.
          $query->execute();
          // Display a confirmation message to the user.
          $this->messenger()->addMessage(
            t('Thank you for your RSVP. We have recorded your email address as @email.', 
              ['@email' => $email]
            )
          );

        } catch (\Exception $e) {
          // Handle any database errors.
          $this->messenger()->addError(
            t('Unable to save RSVP due to a database error.')
          );
        }
    }
}