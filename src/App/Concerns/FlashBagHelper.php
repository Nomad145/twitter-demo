<?php

namespace App\Concerns;

/**
 * FlashBagHelper
 *
 * Provides helper methods for managing the FlashBag.  This trait has a
 * dependency on the Session component.
 *
 * @author Michael Phillips <michaeljoelphillips@gmail.com>
 */
trait FlashBagHelper
{
    /**
     * A helper method for adding a message to the FlashBag.
     *
     * @param string $type
     * @param string $message
     * @return void
     */
    protected function addFlash(string $type, string $message) : void
    {
        $this->session->getFlashBag()->add($type, $message);
    }
}
