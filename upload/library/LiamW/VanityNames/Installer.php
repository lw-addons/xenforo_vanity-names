<?php

class LiamW_VanityNames_Installer
{
	protected static $_coreAlters = array(
		'xf_user' => array(
			'vanity_name' => "ALTER TABLE xf_user ADD vanity_name VARCHAR(50) NOT NULL DEFAULT ''"
		)
	);

	protected static function _canBeInstalled(&$error)
	{
		if (XenForo_Application::$versionId < 1020070)
		{
			$error = "Please upgrade to XenForo 1.2.0+ prior to installation!";

			return false;
		}

		$hashErrors = XenForo_Helper_Hash::compareHashes(LiamW_VanityNames_FileSums::getHashes());

		if ($hashErrors)
		{
			$error = "The following files could not be found or contain unexpected content: <ul>";

			foreach ($hashErrors as $file => $hashError)
			{
				$error .= "<li>$file - " . ($hashError == 'mismatch' ? 'File contains unexpected contents' : 'File not found') . "</li>";
			}

			$error .= "</ul>";

			return false;
		}

		return true;
	}

	public static function install()
	{
		if (!self::_canBeInstalled($error))
		{
			throw new XenForo_Exception($error, true);
		}

		self::_installCoreAlters();
	}

	public static function uninstall()
	{
		self::_uninstallCoreAlters();
	}

	protected static function _installCoreAlters(Zend_Db_Adapter_Abstract $db = null)
	{
		foreach (self::$_coreAlters AS $tableName => $coreAlters)
		{
			foreach ($coreAlters AS $columnName => $installSql)
			{
				self::_runQuery($installSql, $db);
			}
		}
	}

	protected static function _uninstallCoreAlters(Zend_Db_Adapter_Abstract $db = null)
	{
		foreach (self::$_coreAlters AS $tableName => $coreAlters)
		{
			foreach ($coreAlters AS $columnName => $installSql)
			{
				self::_runQuery("ALTER TABLE $tableName DROP $columnName", $db);
			}
		}
	}

	protected static function _runQuery($sql, Zend_Db_Adapter_Abstract $db = null)
	{
		if ($db == null)
		{
			$db = XenForo_Application::getDb();
		}

		try
		{
			$db->query($sql);
		} catch (Zend_Db_Exception $e)
		{
		}
	}
}