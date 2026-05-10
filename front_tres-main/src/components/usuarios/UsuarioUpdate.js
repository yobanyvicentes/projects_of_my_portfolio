import React, { useState, useEffect } from 'react';
import Swal from 'sweetalert2';
import { useParams } from 'react-router-dom';
import { putUsuarios, getUsuarioById } from '../../services/usuarioService';
import { Link } from 'react-router-dom';

export const UsuarioUpdate = () => {

    const { usuarioId = '' } = useParams();
    const [usuario, setUsuario] = useState({});

    const [valoresform, setValoresform] = useState({});
    const { nombre = '', email = '', rol ='', estado = '' } = valoresform;

    const getUsuario = async () => {
        try {
            Swal.fire({
                allowOutsideClick: false,
                title: 'Cargando....',
                text: 'Por favor espere',
                timer: 5000//milisegundos
            });
            Swal.showLoading();
            Swal.close();
            const { data } = await getUsuarioById(usuarioId);
            setUsuario(data);
        } catch (error) {
            Swal.close();
            console.log(error);
        }
    }
    useEffect(() => {
        getUsuario();
    }, [usuarioId]);

    useEffect(() => {
        setValoresform({
            nombre: usuario.nombre,
            email: usuario.email,
            rol: usuario.rol,
            estado: usuario.estado,
        });
    }, [usuario])

    const handleOnSubmit = async (e) => {
        e.preventDefault();
        const usuarioModel = {
            nombre,
            rol,
            email,
            estado
        }
        try {
            console.log(usuarioModel);
            Swal.fire({
                allowOutsideClick: false, title: 'Cargando....', text: 'Por favor espere', timer: 2000//milisegundos
            });
            const { data } = await putUsuarios(usuarioId, usuarioModel);
            console.log(data);
            Swal.close();
        } catch (error) {
            Swal.fire('Error', 'hubo un error...', 'error')
            console.log("error al crear el usuario");
        }
    }

    const handleOnChange = ({ target }) => {
        const { name, value } = target;
        setValoresform({ ...valoresform, [name]: value });
    }

    return (
        <div className='container-fluid'>
            <div className='card mt-3 mb-2'>
                <div className='card-header'>
                    <h5>Usuarios</h5>
                </div>
                <div className='card-body'>
                    <div className='row'>
                        <div className='col'>
                            <form
                                className='form'
                                onSubmit={(e) => {
                                    handleOnSubmit(e)
                                }
                                }
                            >
                                <div className='row' te>
                                    <h5>Actualizar Usuario</h5>
                                </div>
                                <div className='row'>
                                    <div className='col-md-3'>
                                        <div className='mb-3'>
                                            <label className='form-label' for='nombreid'>Nombre</label>
                                            <input className='form-control' type="text" name="nombre" value={nombre} id='nombreid' required
                                                onChange={(e) => {
                                                    handleOnChange(e);
                                                }}
                                            />
                                        </div>
                                    </div>
                                    <div className='col-md-3'>
                                        <div className='mb-3'>
                                            <label className='form-label' for='emailid'>Email</label>
                                            <input className='form-control' type="text" name="email" value={email} id='emailid' required
                                                onChange={(e) => {
                                                    handleOnChange(e);
                                                }}
                                            />
                                        </div>
                                    </div>
                                    <div className='col-md-2'>
                                        <div className='mb-3'>
                                            <label className='form-label' for='rolid'>Rol</label>
                                            <select className='form-select' name="rol" value={rol} id='rolid' required
                                                onChange={(e) => {
                                                    handleOnChange(e);
                                                }}
                                            >
                                                <option selected>--Seleccione--</option>
                                                <option value="admin">admin</option>
                                                <option value="normal">normal</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div className='col-md-2'>
                                        <div className='mb-3'>
                                            <label className='form-label' for='estadoid'>Estado</label>
                                            <select className='form-select' name="estado" value={estado} id='estadoid' required
                                                onChange={(e) => {
                                                    handleOnChange(e);
                                                }}
                                            >
                                                <option selected>--Seleccione--</option>
                                                <option value="Activo">Activo</option>
                                                <option value="Inactivo">Inactivo</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div className='row'>
                                    <div className='col-md-2'>
                                        <button className='btn btn-primary' type="onSubmit">1-Guardar</button>
                                    </div>
                                    <div className='col-md-3'>
                                        <Link to={`../../usuarios`}>
                                        <button className='btn btn-success' type="onSubmit">2-Ver Datos Actualizados</button>
                                        </Link>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
}

