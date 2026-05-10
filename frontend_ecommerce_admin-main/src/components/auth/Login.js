import React, { useState } from 'react';
import PropTypes from 'prop-types';
import { postLogin } from '../../services/auth';
import Swal from 'sweetalert2';

export const Login = ({ setToken })  => {

    const [email, setEmail] = useState();
    const [password, setPassword] = useState();

    const handleSubmit = async e => {
        try {
            e.preventDefault();
            Swal.fire({
                allowOutsideClick: false, title: 'Validando....', text: 'Por favor espere'
            });
            const {data} = await postLogin({
                email,
                password
            });
            Swal.close();
            const token = data.token;
            console.log(token);
            setToken(token);
            window.location.reload();
        } catch (error) {
            Swal.fire('Error', 'Usuario o contraseña incorrecta', 'error');
            console.log(error)
        }
    }

    return(
        <div class="login-wrapper">
            <div class="container">
                <div class="d-flex justify-content-center mt-5">
                    <div class="card card-login">
                        <div class="card-header">
                            <h1 >
                                Sign in
                            </h1>
                        </div>
                        <div class="card-body">
                            <form action="post" onSubmit={handleSubmit}>
                                <div class="mb-3">
                                    <label for="inputEmail1" class="form-label" name="email">Email</label>
                                    <input
                                        type="email"
                                        class="form-control"
                                        id="inputEmail1"
                                        name="email"
                                        onChange={e => setEmail(e.target.value)}
                                        >
                                    </input>
                                </div>
                                <div class="mb-3">
                                    <label for="inputPassword1" class="form-label">Password</label>
                                    <input
                                        type="password"
                                        class="form-control"
                                        id="inputPassword1"
                                        name="password"
                                        onChange={e => setPassword(e.target.value)}
                                        >
                                   </input>
                                </div>
                                <button type="submit" class="btn btn-primary">Continue</button>
                            </form>
                        </div>
                        <div class="card-footer">
                        </div>
                    </div>
                </div>
            </div>
	    </div>
    );
}

Login.propTypes = {
    setToken: PropTypes.func.isRequired
};
