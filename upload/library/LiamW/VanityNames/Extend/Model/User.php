<?php

class LiamW_VanityNames_Extend_Model_User extends XFCP_LiamW_VanityNames_Extend_Model_User
{
	/**
	 * Returns a user record based on an input vanity name.
	 *
	 * @param string $vanityName
	 * @param array  $conditions
	 * @param array  $fetchOptions User fetch options
	 *
	 * @return array|false
	 */
	public function getUserByVanityName($vanityName, array $conditions = array(), array $fetchOptions = array())
	{
		$whereClause = $this->prepareUserConditions($conditions, $fetchOptions);
		$joinOptions = $this->prepareUserFetchOptions($fetchOptions);

		return $this->_getDb()->fetchRow('
			SELECT user.*
				' . $joinOptions['selectFields'] . '
			FROM xf_user AS user
			' . $joinOptions['joinTables'] . '
			WHERE user.vanity_name = ? AND ' . $whereClause . '
		', $vanityName);
	}

	public function removeAllVanityNames()
	{
		$this->_getDb()->query("UPDATE xf_user SET vanity_name=''");
	}
}

if (false)
{
	class XFCP_LiamW_VanityNames_Extend_Model_User extends XenForo_Model_User
	{
	}
}