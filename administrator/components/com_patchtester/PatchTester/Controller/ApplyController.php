<?php

/**
 * Patch testing component for the Joomla! CMS
 *
 * @copyright  Copyright (C) 2011 - 2012 Ian MacLennan, Copyright (C) 2013 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later
 */

namespace PatchTester\Controller;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use PatchTester\Model\PullModel;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects


/**
 * Controller class to apply patches
 *
 * @since  2.0
 */
class ApplyController extends AbstractController
{
    /**
     * Execute the controller.
     *
     * @return  void  Redirects the application
     *
     * @since   2.0
     */
    public function execute()
    {
        try {
            $model = new PullModel(null, Factory::getDbo());
// Initialize the state for the model
            $model->setState($this->initializeState($model));
            if ($model->apply($this->getInput()->getUint('pull_id'))) {
                $msg = Text::_('COM_PATCHTESTER_APPLY_OK');
            } else {
                $msg = Text::_('COM_PATCHTESTER_NO_FILES_TO_PATCH');
            }

            $type = 'message';
        } catch (\Exception $e) {
            $msg  = $e->getMessage();
            $type = 'error';
        }

        $this->getApplication()->enqueueMessage($msg, $type);
        $this->getApplication()->redirect(Route::_('index.php?option=com_patchtester', false));
    }
}
