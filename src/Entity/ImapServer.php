<?php

/**
 * @file
 * Contains \Drupal\imapadmin\Entity\ImapServer.
 */

namespace Drupal\imapadmin\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\imapadmin\ImapServerInterface;

/**
 * Defines the imapserver entity.
 *
 * @ConfigEntityType(
 *   id = "imapserver",
 *   label = @Translation("IMAP server"),
 *   handlers = {
 *     "list_builder" = "Drupal\imapadmin\Controller\ImapServerListBuilder",
 *     "form" = {
 *       "add" = "Drupal\imapadmin\Form\ImapServerForm",
 *       "edit" = "Drupal\imapadmin\Form\ImapServerForm",
 *       "delete" = "Drupal\imapadmin\Form\ImapServerDeleteForm"
 *     }
 *   },
 *   config_prefix = "imapserver",
 *   admin_permission = "administer imap servers",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *   },
 *   links = {
 *     "edit-form" = "/admin/imap/server/{imapserver}/edit",
 *     "delete-form" = "/admin/imap/server/{imapserver}/delete"
 *   }
 * )
 */
class ImapServer extends ConfigEntityBase implements ImapServerInterface {

  /**
   * The imapserver ID.
   *
   * @var string
   */
  public $id;

  /**
   * The imapserver label.
   *
   * @var string
   */
  public $label;

  /**
   * The imapserver hostname.
   *
   * @var string
   */
  protected $hostname;

  /**
   * The imapserver connection encryption.
   *
   * @var string
   */
  protected $encryption = 'tls';

  /**
   * Validate the imapserver certificate.
   *
   * @var boolean
   */
  protected $validate_cert = TRUE;

  /**
   * The imapserver connection tcp port.
   *
   * @var integer
   */
  protected $port = 143;

  /**
   * The imapserver admin username.
   *
   * @var string
   */
  protected $username;

  /**
   * The imapserver admin password.
   *
   * @var string
   */
  protected $password;

}