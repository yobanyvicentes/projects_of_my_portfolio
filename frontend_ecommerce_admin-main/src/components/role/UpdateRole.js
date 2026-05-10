import React, { useState, useEffect } from 'react';
import Swal from 'sweetalert2';
import { useParams, useHistory } from 'react-router-dom';
import { getRoleById, putRole } from '../../services/role';

export const UpdateRole = () => {

    const history = useHistory();

    const { roleId = '' } = useParams();
    const [ role, setRole ] = useState({});

    const [ formValues, setFormValues ] = useState({});
    const { name = '', internal_id = '' } = formValues;

    const getRole = async () => {
        try {
            Swal.fire({
                allowOutsideClick: false,
                title: 'Cargando....',
                text: 'Por favor espere',
                timer: 5000//milisegundos
            });
            Swal.showLoading();
            Swal.close();
            const { data } = await getRoleById(roleId);
            setRole(data);
        } catch (error) {
            Swal.close();
            console.log(error);
        }
    }

    useEffect(() => {
        getRole();
    }, [roleId]);

    useEffect(() => {
        setFormValues({
            name: role.name,
            internal_id: role.internalId,
        });
    }, [role]);

    const handleOnSubmit = async (e) => {
        e.preventDefault();
        const roleModel = {
            name,
            internal_id,
        }
        try {
            Swal.fire({
                allowOutsideClick: false, title: 'Cargando....', text: 'Por favor espere', timer: 2000//milisegundos
            });
            const { data } = await putRole(roleId, roleModel);
            console.log(data)
            Swal.close();
            setFormValues('');
            history.push('/role');
        } catch (error) {
            Swal.fire('Error', 'hubo un error al intentar actualizar', 'error')
            console.log("error al actualizar la marca");
        }
    }

    const handleOnChange = ({ target }) => {
        const { name, value } = target;
        setFormValues({ ...formValues, [name]: value });
    }

    return (
        <div className='container-fluid'>
            <div className='card mt-3 mb-2'>
                <div className='card-header'>
                    <h5>Marcas</h5>
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
                                    <h5>Actualizar Marca</h5>
                                </div>
                                <div className='row'>
                                    <div className='col-md-4'>
                                        <div className='mb-3'>
                                            <label className='form-label' for='nameid'>Nombre</label>
                                            <input className='form-control' type="text" name="name" value={name} id='nameid' required
                                                onChange={(e) => {
                                                    handleOnChange(e);
                                                }}
                                            />
                                        </div>
                                    </div>
                                    <div className='col-md-4'>
                                        <div className='mb-3'>
                                            <label className='form-label' for='internal_id'>ID interno</label>
                                            <input className='form-control' type='text' name="internal_id" value={internal_id} id='internal_id' required
                                                onChange={(e) => {
                                                    handleOnChange(e);
                                                }}
                                            />
                                        </div>
                                    </div>
                                    <div className='col-md-4'>
                                        <div className='mb-3 mt-2'>
                                            <button className='btn btn-primary mt-4' type="onSubmit" >Guardar</button>
                                        </div>
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
