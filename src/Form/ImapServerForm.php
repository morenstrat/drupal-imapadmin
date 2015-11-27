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

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $imapserver = $this->entity;
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