<?php

namespace Auth\Identities;

use Obullo\Authentication\Identity\AbstractIdentity;

class AuthorizedUser extends AbstractIdentity
{
    /**
     * Get the identifier column value
     *
     * @return mixed
     */
    public function getIdentifier()
    {
        $id = $this->getColumnIdentifier();

        return $this->$id;
    }

    /**
     * Get the password column value
     *
     * @return mixed
     */
    public function getPassword()
    {
        $password = $this->getColumnPassword();

        return $this->$password;
    }

}

/* End of file AuthorizedUser.php */
/* Location: .app/classes/Auth/Identities/AuthorizedUser.php */