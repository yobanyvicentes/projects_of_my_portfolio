import React from 'react';
import Swal from 'sweetalert2';

export const LogoutButton = ({ setToken })  => {

    const logout = () => {
        try {
            localStorage.removeItem('token');
            window.location.reload();
        } catch (error) {
            Swal.fire('Error', 'porfavor, comuniquese con soporte', 'error');
            console.log(error)
        }
    }

    return(
        <div class="logout">
            <button type="submit" class="btn btn-danger" onClick={()=>{logout()}}>Logout</button>
	    </div>
    );
}

