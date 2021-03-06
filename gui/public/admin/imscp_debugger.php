<?php
/**
 * i-MSCP - internet Multi Server Control Panel
 *
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 *
 * The Original Code is "VHCS - Virtual Hosting Control System".
 *
 * The Initial Developer of the Original Code is moleSoftware GmbH.
 * Portions created by Initial Developer are Copyright (C) 2001-2006
 * by moleSoftware GmbH. All Rights Reserved.
 *
 * Portions created by the ispCP Team are Copyright (C) 2006-2010 by
 * isp Control Panel. All Rights Reserved.
 *
 * Portions created by the i-MSCP Team are Copyright (C) 2010-2013 by
 * i-MSCP - internet Multi Server Control Panel. All Rights Reserved.
 *
 * @category    i-MSCP
 * @package     iMSCP_Core
 * @subpackage  Admin
 * @copyright   2001-2006 by moleSoftware GmbH
 * @copyright   2006-2010 by ispCP | http://isp-control.net
 * @copyright   2010-2013 by i-MSCP | http://i-mscp.net
 * @author      ispCP Team
 * @author      i-MSCP Team
 * @link        http://i-mscp.net
 */

// TODO Plugin items's related errors

/***********************************************************************************************************************
 * Functions
 */

/**
 * Get user errors
 *
 * @param  iMSCP_pTemplate $tpl Template engine instance
 * @return int
 */
function debugger_getUserErrors($tpl)
{
	/** @var $cfg iMSCP_Config_Handler_File */
	$cfg = iMSCP_Registry::get('config');

	$query = "
		SELECT
			`admin_name`, `admin_status`, `admin_id`
		FROM
			`admin`
		WHERE
			`admin_type` = 'user'
		AND
			`admin_status` NOT IN (?, ?, ?, ?)
	";
	$stmt = exec_query(
		$query,
		array($cfg->ITEM_OK_STATUS, $cfg->ITEM_TOADD_STATUS, $cfg->ITEM_TOCHANGE_STATUS, $cfg->ITEM_TODELETE_STATUS)
	);

	$errors = $stmt->rowCount();

	if (!$errors) {
		$tpl->assign(array('USER_LIST' => '', 'TR_USER_MESSAGE' => tr('No errors')));
		$tpl->parse('USER_MESSAGE', 'user_message');
	} else {
		while ($row = $stmt->fetchRow(PDO::FETCH_ASSOC)) {
			$tpl->assign(
				array(
					'USER_MESSAGE' => '',
					'TR_USER_NAME' => tohtml(idn_to_utf8($row['admin_name'])),
					'TR_USER_ERROR' => tohtml($row['admin_status']),
					'CHANGE_ID' => tohtml($row['admin_id']),
					'CHANGE_TYPE' => 'user'
				)
			);

			$tpl->parse('USER_LIST', '.user_list');
		}
	}

	return $errors;
}

/**
 * Get domain errors
 *
 * @param  iMSCP_pTemplate $tpl Template engine instance
 * @return int
 */
function debugger_getDmnErrors($tpl)
{
	/** @var $cfg iMSCP_Config_Handler_File */
	$cfg = iMSCP_Registry::get('config');

	$query = "
		SELECT
			`domain_name`, `domain_status`, `domain_id`
		FROM
			`domain`
		WHERE
			`domain_status` NOT IN (?, ?, ?, ?, ?, ?, ?, ?)
	";
	$stmt = exec_query(
		$query,
		array(
			$cfg->ITEM_OK_STATUS, $cfg->ITEM_DISABLED_STATUS, $cfg->ITEM_TOADD_STATUS, $cfg->ITEM_TOCHANGE_STATUS,
			$cfg->ITEM_TORESTORE_STATUS, $cfg->ITEM_TOENABLE_STATUS, $cfg->ITEM_TODISABLE_STATUS,
			$cfg->ITEM_TODELETE_STATUS
		)
	);

	$errors = $stmt->rowCount();

	if (!$errors) {
		$tpl->assign(array('DMN_LIST' => '', 'TR_DMN_MESSAGE' => tr('No errors')));
		$tpl->parse('DMN_MESSAGE', 'dmn_message');
	} else {
		while ($row = $stmt->fetchRow(PDO::FETCH_ASSOC)) {
			$tpl->assign(
				array(
					'DMN_MESSAGE' => '',
					'TR_DMN_NAME' => tohtml(idn_to_utf8($row['domain_name'])),
					'TR_DMN_ERROR' => tohtml($row['domain_status']),
					'CHANGE_ID' => tohtml($row['domain_id']),
					'CHANGE_TYPE' => 'domain'
				)
			);

			$tpl->parse('DMN_LIST', '.dmn_list');
		}
	}

	return $errors;
}

/**
 * Get domain aliases errors
 *
 * @param  iMSCP_pTemplate $tpl Template engine instance
 * @return int
 */
function debugger_getAlsErrors($tpl)
{
	/** @var $cfg iMSCP_Config_Handler_File */
	$cfg = iMSCP_Registry::get('config');

	$query = "
		SELECT
			`alias_name`, `alias_status`, `alias_id`
		FROM
			`domain_aliasses`
		WHERE
			`alias_status` NOT IN (?, ?, ?, ?, ?, ?, ?, ?, ?)
	";
	$stmt = exec_query(
		$query,
		array(
			$cfg->ITEM_OK_STATUS, $cfg->ITEM_DISABLED_STATUS, $cfg->ITEM_TOADD_STATUS, $cfg->ITEM_TOCHANGE_STATUS,
			$cfg->ITEM_TORESTORE_STATUS, $cfg->ITEM_TOENABLE_STATUS, $cfg->ITEM_TODISABLE_STATUS,
			$cfg->ITEM_TODELETE_STATUS, $cfg->ITEM_ORDERED_STATUS
		)
	);

	$errors = $stmt->rowCount();

	if (!$errors) {
		$tpl->assign(array('ALS_LIST' => '', 'TR_ALS_MESSAGE' => tr('No errors')));
		$tpl->parse('ALS_MESSAGE', 'als_message');
	} else {
		while ($row = $stmt->fetchRow(PDO::FETCH_ASSOC)) {
			$tpl->assign(
				array(
					'ALS_MESSAGE' => '',
					'TR_ALS_NAME' => tohtml(idn_to_utf8($row['alias_name'])),
					'TR_ALS_ERROR' => tohtml($row['alias_status']),
					'CHANGE_ID' => $row['alias_id'],
					'CHANGE_TYPE' => 'alias',
				)
			);

			$tpl->parse('ALS_LIST', '.als_list');
		}
	}

	return $errors;
}

/**
 * Get subdomains errors
 *
 * @param  iMSCP_pTemplate $tpl Template engine instance
 * @return int
 */
function debugger_getSubErrors($tpl)
{
	/** @var $cfg iMSCP_Config_Handler_File */
	$cfg = iMSCP_Registry::get('config');

	$query = "
		SELECT
			`subdomain_name`, `subdomain_status`, `subdomain_id`, `domain_name`
		FROM
			`subdomain`
			LEFT JOIN `domain` ON (`subdomain`.`domain_id` = `domain`.`domain_id`)
		WHERE
			`subdomain_status`
		NOT IN
			(?, ?, ?, ?, ?, ?, ?, ?)
	";
	$stmt = exec_query(
		$query,
		array(
			$cfg->ITEM_OK_STATUS, $cfg->ITEM_DISABLED_STATUS, $cfg->ITEM_TOADD_STATUS, $cfg->ITEM_TOCHANGE_STATUS,
			$cfg->ITEM_TORESTORE_STATUS, $cfg->ITEM_TOENABLE_STATUS, $cfg->ITEM_TODISABLE_STATUS,
			$cfg->ITEM_TODELETE_STATUS
		)
	);

	$errors = $stmt->rowCount();

	if (!$errors) {
		$tpl->assign(array('SUB_LIST' => '', 'TR_SUB_MESSAGE' => tr('No errors')));
		$tpl->parse('SUB_MESSAGE', 'sub_message');
	} else {
		while ($row = $stmt->fetchRow(PDO::FETCH_ASSOC)) {
			$tpl->assign(
				array(
					'SUB_MESSAGE' => '',
					'TR_SUB_NAME' => tohtml(idn_to_utf8($row['subdomain_name'] . '.' . $row['domain_name'])),
					'TR_SUB_ERROR' => tohtml($row['subdomain_status']),
					'CHANGE_ID' => $row['subdomain_id'],
					'CHANGE_TYPE' => 'subdomain'
				)
			);

			$tpl->parse('SUB_LIST', '.sub_list');
		}
	}

	return $errors;
}

/**
 * Get subdomain aliases errors
 *
 * @param  iMSCP_pTemplate $tpl Template engine instance
 * @return int
 */
function debugger_getAlssubErrors($tpl)
{
	/** @var $cfg iMSCP_Config_Handler_File */
	$cfg = iMSCP_Registry::get('config');

	$query = "
		SELECT
			`subdomain_alias_name`, `subdomain_alias_status`, `subdomain_alias_id`, `alias_name`
		FROM
			`subdomain_alias`
		LEFT JOIN
			`domain_aliasses` ON (`subdomain_alias_id` = `domain_aliasses`.`alias_id`)
		WHERE
			`subdomain_alias_status` NOT IN (?, ?, ?, ?, ?, ?, ?, ?)
	";
	$stmt = exec_query(
		$query,
		array(
			$cfg->ITEM_OK_STATUS, $cfg->ITEM_DISABLED_STATUS, $cfg->ITEM_TOADD_STATUS, $cfg->ITEM_TOCHANGE_STATUS,
			$cfg->ITEM_TORESTORE_STATUS, $cfg->ITEM_TOENABLE_STATUS, $cfg->ITEM_TODISABLE_STATUS,
			$cfg->ITEM_TODELETE_STATUS
		)
	);

	$errors = $stmt->rowCount();

	if (!$errors) {
		$tpl->assign(
			array('ALSSUB_LIST' => '', 'TR_ALSSUB_MESSAGE' => tr('No errors'))
		);
		$tpl->parse('ALSSUB_MESSAGE', 'alssub_message');
	} else {
		while ($row = $stmt->fetchRow(PDO::FETCH_ASSOC)) {
			$tpl->assign(
				array(
					'ALSSUB_MESSAGE' => '',
					'TR_ALSSUB_NAME' => tohtml(idn_to_utf8($row['subdomain_alias_name'] . '.' . $row['alias_name'])),
					'TR_ALSSUB_ERROR' => tohtml($row['subdomain_alias_status']),
					'CHANGE_ID' => $row['subdomain_alias_id'],
					'CHANGE_TYPE' => 'subdomain_alias'
				)
			);

			$tpl->parse('ALSSUB_LIST', '.alssub_list');
		}
	}

	return $errors;
}

/**
 * Gets htaccess errors
 *
 * @param iMSCP_pTemplate $tpl Template engine instance
 * @return int
 */
function debugger_getHtaccessErrors($tpl)
{
	/** @var $cfg iMSCP_Config_Handler_File */
	$cfg = iMSCP_Registry::get('config');

	$dmn_query = "
		SELECT
			`id`, `dmn_id`, `status`, 'htaccess' AS `type`, `domain_name`
		FROM
			`htaccess`
		LEFT JOIN
			`domain` ON (`dmn_id` = `domain_id`)
		WHERE
			`status` NOT IN (:ok, :toadd, :tochange, :todelete)
		UNION
		SELECT
			`id`, `dmn_id`, `status`, 'htaccess_groups' AS `type`, `domain_name`
		FROM
			`htaccess_groups`
		LEFT JOIN
			`domain` ON (`dmn_id` = `domain_id`)
		WHERE
			`status` NOT IN (:ok, :toadd, :tochange, :todelete)
		UNION
		SELECT
			`id`, `dmn_id`, `status`, 'htaccess_users' AS `type`, `domain_name`
		FROM
			`htaccess_users`
		LEFT JOIN
			`domain` ON (`dmn_id` = `domain_id`)
		WHERE
			`status` NOT IN (:ok, :toadd, :tochange, :todelete)
	";
	$stmt = exec_query(
		$dmn_query,
		array(
			'ok' => $cfg->ITEM_OK_STATUS, 'toadd' => $cfg->ITEM_TOADD_STATUS, 'tochange' => $cfg->ITEM_TOCHANGE_STATUS,
			'todelete' => $cfg->ITEM_TODELETE_STATUS
		)
	);

	$errors = $stmt->rowCount();

	if (!$errors) {
		$tpl->assign(array('HTACCESS_LIST' => '', 'TR_HTACCESS_MESSAGE' => tr('No errors')));
		$tpl->parse('HTACCESS_MESSAGE', 'htaccess_message');
	} else {
		while ($row = $stmt->fetchRow(PDO::FETCH_ASSOC)) {
			$tpl->assign(
				array(
					'HTACCESS_MESSAGE' => '',
					'TR_HTACCESS_NAME' => ($row['domain_name'] == null)
						? tr('Missing domain') : tohtml($row['domain_name']),
					'TR_HTACCESS_ERROR' => tohtml($row['status']),
					'CHANGE_ID' => $row['id'],
					'CHANGE_TYPE' => $row['type']
				)
			);

			$tpl->parse('HTACCESS_LIST', '.htaccess_list');
		}
	}

	return $errors;
}

/**
 * Get mails errors
 *
 * @throws iMSCP_Exception
 * @param iMSCP_pTemplate $tpl Template engine instance
 * @return int
 */
function debugger_getMailsErrors($tpl)
{
	/** @var $cfg iMSCP_Config_Handler_File */
	$cfg = iMSCP_Registry::get('config');

	$query = "
		SELECT
			`mail_acc`, `domain_id`, `mail_type`, `status`, `mail_id`
		FROM
			`mail_users`
		WHERE
			`status` NOT IN (?, ?, ?, ?, ?, ?, ?, ?, ?)
	";
	$stmt = exec_query(
		$query,
		array(
			$cfg->ITEM_OK_STATUS, $cfg->ITEM_DISABLED_STATUS, $cfg->ITEM_TOADD_STATUS, $cfg->ITEM_TOCHANGE_STATUS,
			$cfg->ITEM_TORESTORE_STATUS, $cfg->ITEM_TOENABLE_STATUS, $cfg->ITEM_TODISABLE_STATUS,
			$cfg->ITEM_TODELETE_STATUS, $cfg->ITEM_ORDERED_STATUS
		)
	);

	$errors = $stmt->rowCount();

	if (!$errors) {
		$tpl->assign(array('MAIL_LIST' => '', 'TR_MAIL_MESSAGE' => tr('No errors')));
		$tpl->parse('MAIL_MESSAGE', 'mail_message');
	} else {
		while ($row = $stmt->fetchRow(PDO::FETCH_ASSOC)) {
			$searchedId = $row['domain_id'];
			$mailAcc = $row['mail_acc'];
			$mailType = $row['mail_type'];
			$mailId = $row['mail_id'];
			$mailStatus = $row['status'];

			switch ($mailType) {
				case 'normal_mail':
				case 'normal_forward':
				case 'normal_mail,normal_forward':
					$query = "SELECT CONCAT('@', `domain_name`) AS `domain_name` FROM `domain` WHERE `domain_id` = ?";
					break;
				case 'subdom_mail':
				case 'subdom_forward':
				case 'subdom_mail,subdom_forward':
					$query = "
						SELECT
							CONCAT(
								'@', `subdomain_name`, '.', IF(`t2`.`domain_name` IS NULL,'" . tr('missing domain') . "',
								`t2`.`domain_name`)
							) AS 'domain_name'
						FROM
							`subdomain` AS `t1`
						LEFT JOIN
							`domain` AS `t2` ON (`t1`.`domain_id` = `t2`.`domain_id`)
						WHERE
							`subdomain_id` = ?
					";
					break;
				case 'alssub_mail':
				case 'alssub_forward':
				case 'alssub_mail,alssub_forward':
					$query = "
						SELECT
							CONCAT(
								'@', `t1`.`subdomain_alias_name`, '.', IF(`t2`.`alias_name` IS NULL,'" .
								tr('missing alias') . "',`t2`.`alias_name`)
							) AS `domain_name`
						FROM
							`subdomain_alias` AS `t1`
						LEFT JOIN
							`domain_aliasses` AS `t2` ON (`t1`.`alias_id` = `t2`.`alias_id`)
						WHERE
							`subdomain_alias_id` = ?
					";
					break;
				case 'normal_catchall':
				case 'alias_catchall':
				case 'alssub_catchall':
				case 'subdom_catchall':
					$query = "SELECT `mail_addr` AS `domain_name` FROM `mail_users` WHERE `mail_id` = ?";
					$searchedId = $mailId;
					$mailAcc = '';
					break;
				case 'alias_mail':
				case 'alias_forward':
				case 'alias_mail,alias_forward':
					$query = "
						SELECT CONCAT('@', `alias_name`) AS `domain_name` FROM `domain_aliasses` WHERE `alias_id` = ?
					";
					break;
				default:
					throw new iMSCP_Exception('FIXME: ' . __FILE__ . ':' . __LINE__ . $mailType);
			}

			$stmt2 = exec_query($query, $searchedId);
			$domainName = $stmt2->fields['domain_name'];
			$domainName = ltrim($domainName, '@');

			$tpl->assign(
				array(
					'MAIL_MESSAGE' => '',
					'TR_MAIL_NAME' => tohtml(
						$mailAcc . '@'. ($domainName == '' ?  ' ' . tr('orphan entry') : idn_to_utf8($domainName))
					),
					'TR_MAIL_ERROR' => tohtml($mailStatus),
					'CHANGE_ID' => $mailId,
					'CHANGE_TYPE' => 'mail'
				)
			);

			$tpl->parse('MAIL_LIST', '.mail_list');
		}
	}

	return $errors;
}

/**
 * Get plugins errors
 *
 * Note: There are only errors related to the plugin itself, not to the plugin items
 *
 * @param  iMSCP_pTemplate $tpl Template engine instance
 * @return int
 */
function debugger_getPluginsErrors($tpl)
{
	/** @var $cfg iMSCP_Config_Handler_File */
	$cfg = iMSCP_Registry::get('config');

	$query = "
		SELECT
			`plugin_id`, `plugin_name`, `plugin_status`
		FROM
			`plugin`
		WHERE
			`plugin_status` NOT IN (?, ?, ?, ?, ?, ?, ?, ?, ?)
	";
	$stmt = exec_query(
		$query,
		array(
			$cfg->ITEM_ENABLED_STATUS, $cfg->ITEM_DISABLED_STATUS, $cfg->ITEM_UNINSTALLED_STATUS,
			$cfg->ITEM_TOINSTALL_STATUS, $cfg->ITEM_TOUPDATE_STATUS, $cfg->ITEM_TOUNINSTALL_STATUS,
			$cfg->ITEM_TOENABLE_STATUS, $cfg->ITEM_TODISABLE_STATUS, $cfg->ITEM_TODELETE_STATUS
		)
	);

	$errors = $stmt->rowCount();

	if (!$errors) {
		$tpl->assign(array('PLUGIN_LIST' => '', 'TR_PLUGIN_MESSAGE' => tr('No errors')));
		$tpl->parse('PLUGIN_MESSAGE', 'plugin_message');
	} else {
		while ($row = $stmt->fetchRow(PDO::FETCH_ASSOC)) {
			$tpl->assign(
				array(
					'PLUGIN_MESSAGE' => '',
					'TR_PLUGIN_NAME' => tohtml($row['plugin_name']),
					'TR_PLUGIN_ERROR' => tohtml($row['plugin_status']),
					'CHANGE_ID' => $row['plugin_id'],
					'CHANGE_TYPE' => 'plugin')
			);

			$tpl->parse('PLUGIN_LIST', '.plugin_list');
		}
	}

	return $errors;
}

/**
 * Returns the number of requests that still to run.
 *
 * @param string $statusField status database field name
 * @param  string $tableName i-MSCP database table name
 * @return int Number of request
 */
function debugger_countRequests($statusField, $tableName)
{
	/** @var $cfg iMSCP_Config_Handler_File */
	$cfg = iMSCP_Registry::get('config');

	$query = "SELECT `$statusField` FROM `$tableName` WHERE `$statusField` IN (?, ?, ?, ?, ?, ?, ?, ?, ?)";
	$stmt = exec_query(
		$query,
		array(
			$cfg->ITEM_TOINSTALL_STATUS, $cfg->ITEM_TOUPDATE_STATUS, $cfg->ITEM_TOUNINSTALL_STATUS,
			$cfg->ITEM_TOADD_STATUS, $cfg->ITEM_TOCHANGE_STATUS, $cfg->ITEM_TORESTORE_STATUS,
			$cfg->ITEM_TOENABLE_STATUS, $cfg->ITEM_TODISABLE_STATUS, $cfg->ITEM_TODELETE_STATUS,
		)
	);

	return $stmt->rowCount();
}

/***********************************************************************************************************************
 * Main
 */

// Include core library
require 'imscp-lib.php';

iMSCP_Events_Manager::getInstance()->dispatch(iMSCP_Events::onAdminScriptStart);

check_login('admin');

/** @var iMSCP_Plugin_Manager $plugingManager */
$plugingManager = iMSCP_Registry::get('pluginManager');

/** @var $cfg iMSCP_Config_Handler_File */
$cfg = iMSCP_Registry::get('config');

$rqstCount = debugger_countRequests('admin_status', 'admin');
$rqstCount += debugger_countRequests('domain_status', 'domain');
$rqstCount += debugger_countRequests('alias_status', 'domain_aliasses');
$rqstCount += debugger_countRequests('subdomain_status', 'subdomain');
$rqstCount += debugger_countRequests('subdomain_alias_status', 'subdomain_alias');
$rqstCount += debugger_countRequests('status', 'mail_users');
$rqstCount += debugger_countRequests('status', 'htaccess');
$rqstCount += debugger_countRequests('status', 'htaccess_groups');
$rqstCount += debugger_countRequests('status', 'htaccess_users');
$rqstCount += debugger_countRequests('plugin_status', 'plugin');

if (isset($_GET['action'])) {
	if ($_GET['action'] == 'run_engine') {
		if ($rqstCount > 0) {
			$code = send_request();
			set_page_message(tr('Daemon returned %d as status code', $code), 'info');
		} else {
			set_page_message(tr('Nothing to do. Daemon request has been canceled.', $code), 'warning');
		}
		redirectTo('imscp_debugger.php');
	} elseif ($_GET['action'] == 'change_status' && (isset($_GET['id']) && isset($_GET['type']))) {
		/** @var iMSCP_Config_Handler_File $cfg */
		$cfg = iMSCP_Registry::get('config');

		switch ($_GET['type']) {
			case 'user':
				$query = "UPDATE `admin` SET `admin_status` = ? WHERE `admin_id` = ?";
				break;
			case 'domain':
				$query = "UPDATE `domain` SET `domain_status` = ? WHERE `domain_id` = ?";
				break;
			case 'alias':
				$query = "UPDATE `domain_aliasses` SET `alias_status` = ? WHERE `alias_id` = ?";
				break;
			case 'subdomain':
				$query = "UPDATE `subdomain` SET `subdomain_status` = ? WHERE `subdomain_id` = ?";
				break;
			case 'subdomain_alias':
				$query = "UPDATE `subdomain_alias` SET `subdomain_alias_status` = ? WHERE `subdomain_alias_id` = ?";
				break;
			case 'mail':
				$query = "UPDATE `mail_users` SET `status` = ? WHERE `mail_id` = ?";
				break;
			case 'htaccess':
			case 'htaccess_users':
			case 'htaccess_groups':
				$query = "UPDATE `" . $_GET['type'] . "` SET `status` = ? WHERE `id` = ?";
				break;
			case 'plugin':
				$query = "UPDATE `plugin` SET `plugin_status` = ? WHERE `plugin_id` = ?";
				break;
			default:
				set_page_message(tr('Unknown type.'), 'error');
				redirectTo('imscp_debugger.php');
		}

		$stmt = exec_query($query, array($cfg->ITEM_TOCHANGE_STATUS, $_GET['id']));

		if ($stmt !== false) {
			set_page_message(tr('Done'), 'success');
		} else {
			/** @var $db iMSCP_Database */
			$db = iMSCP_Registry::get('db');
			set_page_message(tr('Unknown Error') . '<br />' . $db->errorMsg(), 'error');
		}

		redirectTo('imscp_debugger.php');
	}
}

$tpl = new iMSCP_pTemplate();
$tpl->define_dynamic(
	array(
		'layout' => 'shared/layouts/ui.tpl',

		'page' => 'admin/imscp_debugger.tpl',
		'page_message' => 'layout',
		'user_message' => 'page',
		'user_list' => 'page',
		'dmn_message' => 'page',
		'dmn_list' => 'page',
		'als_message' => 'page',
		'als_list' => 'page',
		'sub_message' => 'page',
		'sub_list' => 'page',
		'alssub_message' => 'page',
		'alssub_list' => 'page',
		'htaccess_message' => 'page',
		'htaccess_list' => 'page',
		'mail_message' => 'page',
		'mail_list' => 'page',
		'plugin_message' => 'page',
		'plugin_list' => 'page'

	)
);

$errors = debugger_getUserErrors($tpl);
$errors += debugger_getDmnErrors($tpl);
$errors += debugger_getAlsErrors($tpl);
$errors += debugger_getSubErrors($tpl);
$errors += debugger_getAlssubErrors($tpl);
$errors += debugger_getMailsErrors($tpl);
$errors += debugger_getHtaccessErrors($tpl);
$errors += debugger_getPluginsErrors($tpl);

$tpl->assign(
	array(
		'TR_PAGE_TITLE' => tr('Admin / System Tools / Debugger'),
		'THEME_CHARSET' => tr('encoding'),
		'ISP_LOGO' => layout_getUserLogo(),

		'TR_USER_ERRORS' => tr('Users errors'),
		'TR_DMN_ERRORS' => tr('Domains errors'),
		'TR_ALS_ERRORS' => tr('Domain aliasses errors'),
		'TR_SUB_ERRORS' => tr('Subdomains errors'),
		'TR_ALSSUB_ERRORS' => tr('Subdomain aliases errors'),
		'TR_MAIL_ERRORS' => tr('Mail accounts errors'),
		'TR_HTACCESS_ERRORS' => tr('Htaccess errors'),
		'TR_PLUGINS_ERRORS' => tr('Plugins errors'),

		'TR_DAEMON_TOOLS' => tr('i-MSCP Daemon tools'),
		'TR_EXEC_REQUESTS' => tr('Execute requests'),
		'TR_CHANGE_STATUS' => tr("Set status to 'tochange'"),
		'EXEC_COUNT' => $rqstCount,
		'TR_ERRORS' => tr('%s Errors in database', $errors)
	)
);

generateNavigation($tpl);
generatePageMessage($tpl);

$tpl->parse('LAYOUT_CONTENT', 'page');

iMSCP_Events_Manager::getInstance()->dispatch(iMSCP_Events::onAdminScriptEnd, array('templateEngine' => $tpl));

$tpl->prnt();

unsetMessages();
