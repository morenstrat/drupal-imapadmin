<?php

/**
 * @file
 * Contains \Drupal\imapadmin\Form\ImapServerForm.
 */

namespace Drupal\imapadmin\Form;

use Drupal\Core\Entity\EntityForm;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Form\FormStateInterface;

/**
 * Builds the form to add and edit imapserver entities.
 */
class ImapServerForm extends EntityForm {
  
  /**
   * @param \Drupal\Core\Entity\Query\QueryFactory $entity_query
   *   The entity query.
   */
  public function __construct(QueryFactory $entity_query) {
    $this->entityQuery = $entity_query;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.query')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $imapserver = $this->entity;

    $form['label'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $imapserver->label(),
      '#description' => $this->t('Label for the IMAP server.'),
      '#required' => TRUE,
    );
    $form['id'] = array(
      '#type' => 'machine_name',
      '#default_value' => $imapserver->id(),
      '#machine_name' => array(
        'exists' => array($this, 'exist'),
      ),
      '#disabled' => !$imapserver->isNew(),
    );
    $form['hostname'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Hostname'),
      '#maxlength' => 255,
      '#default_value' => $imapserver->get('hostname'),
      '#description' => $this->t('The IMAP server hostname.'),
      '#required' => TRUE,
    );
    $form['encryption'] = array(
      '#type' => 'select',
      '#title' => $this->t('Encryption'),
      '#options' => array(
        'tls' => 'TLS',
        'ssl' => 'SSL',
        'notls' => 'No-TLS',
      ),
      '#default_value' => $imapserver->get('encryption'),
      '#description' => $this->t('The IMAP server connection encryption.'),
      '#required' => TRUE,
    );
    $form['validate_cert'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Validate certificate'),
      '#default_value' => $imapserver->get('validate_cert'),
      '#description' => $this->t('Validate the IMAP server certificate.'),
    );
    $form['port'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('TCP Port'),
      '#maxlength' => 5,
      '#default_value' => $imapserver->get('port'),
      '#description' => $this->t('The IMAP server connection TCP port.'),
      '#required' => TRUE,
    );
    $form['username'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#maxlength' => 255,
      '#default_value' => $imapserver->get('username'),
      '#description' => $this->t('The IMAP server admin username.'),
      '#required' => TRUE,
    );

    $id = $imapserver>id();
    $description = $this->t('The IMAP server admin password.');

    if ($this->exist($id)) {
      $required = FALSE;
      $description .= ' ' . $this->t('Leave empty to keep stored password.');
    }
    else {
      $required = TRUE;
    }

    $form['password'] = array(
      '#type' => 'password',
      '#title' => $this->t('Password'),
      '#maxlength' => 255,
      '#description' => $description,
      '#required' => $required,
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $imapserver = $this->entity;
    $id = $imapserver->id();
    $password = $form_state->getValue('password');

    if (empty($password) && $this->exist($id)) {
      $stored_entity = $this->entityTypeManager->getStorage('imapserver')->load($id);
      $stored_password = $stored_entity->get('password');
      $imapserver->set('password', $stored_password);
    }

    $status = $imapserver->save();

    if ($status) {
      drupal_set_message($this->t('Saved the %label IMAP server.', array(
        '%label' => $imapserver->label(),
      )));
    }
    else {
      drupal_set_message($this->t('The %label IMAP server was not saved.', array(
        '%label' => $imapserver->label(),
      )));
    }

    $form_state->setRedirect('imapadmin.server.list');
  }

  public function exist($id) {
    $entity = $this->entityQuery->get('imapserver')
      ->condition('id', $id)
      ->execute();
    return (bool) $entity;
  }

}