<?php

/**

 * @package		Joomla.Site

 * @subpackage	mod_login

 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.

 * @license		GNU General Public License version 2 or later; see LICENSE.txt

 */



// no direct access

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');

?>



<div class='mod_login'>

<?php if ($type == 'logout') : ?>

<h3><span><span>Logout</span></span></h3>

<div class='boxIndent2'>

<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form">

<?php if ($params->get('greeting')) : ?>

	<div class="login-greeting">

	<?php if($params->get('name') == 0) : {

		echo JText::sprintf('MOD_LOGIN_HINAME', $user->get('name'));

	} else : {

		echo JText::sprintf('MOD_LOGIN_HINAME', $user->get('username'));

	} endif; ?>

	</div>

<?php endif; ?>

	<div class="logout-button">

		<input type="submit" name="Submit" class="button" value="<?php echo JText::_('JLOGOUT'); ?>" />

		<input type="hidden" name="option" value="com_users" />

		<input type="hidden" name="task" value="user.logout" />

		<input type="hidden" name="return" value="<?php echo $return; ?>" />

		<?php echo JHtml::_('form.token'); ?>

	</div>

</form>

</div>

<?php else : ?>

<h3><span><span>Login</span></span></h3>

<div class='boxIndent2'>

<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" >

	<?php if ($params->get('pretext')): ?>

		<div class="pretext">

		<p><?php echo $params->get('pretext'); ?></p>

		</div>

	<?php endif; ?>

	<p id="form-login-username">

		<?php /*?><label for="modlgn-username"><?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?></label><?php */?>

		<input id="modlgn-username" type="text" name="username" class="inputbox"  size="18" />

	</p>

	<p id="form-login-password">

		<?php /*?><label for="modlgn-passwd"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label><?php */?>

		<input id="modlgn-passwd" type="password" name="password" class="inputbox" size="18"  />

	</p>

<div class="wrapper">

	<input type="submit" name="Submit" class="button" value="<?php echo JText::_('JLOGIN') ?>" />

	<input type="hidden" name="option" value="com_users" />

	<input type="hidden" name="task" value="user.login" />

	<input type="hidden" name="return" value="<?php echo $return; ?>" />

	<?php echo JHtml::_('form.token'); ?>

	<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>

	<p id="form-login-remember">

		<input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/>

		<label for="modlgn-remember"><?php echo JText::_('MOD_LOGIN_REMEMBER_ME') ?></label>

	</p>

	<?php endif; ?>

	<?php /*?><ul>

		<li>

			<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">

			<?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?></a>

		</li>

		<li>

			<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">

			<?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME'); ?></a>

		</li>

        <li class="yet">

			<?php echo JText::_('TM_NO_ACCOUNT_YET'); ?>

		</li>

	</ul><?php */?>

	<?php

		$usersConfig = JComponentHelper::getParams('com_users');

		if ($usersConfig->get('allowUserRegistration')) : ?>

		<div class="create">

			<a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">

				<?php echo JText::_('MOD_LOGIN_REGISTER'); ?></a>

		</div>

	<?php endif; ?>

</div>	

	

	<?php if ($params->get('posttext')): ?>

		<div class="posttext">

		<p><?php echo $params->get('posttext'); ?></p>

		</div>

	<?php endif; ?>

</form>

</div>

<?php endif; ?>

</div>