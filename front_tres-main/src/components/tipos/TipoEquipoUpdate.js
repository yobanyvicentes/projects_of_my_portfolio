import React, { useState, useEffect } from 'react';
import Swal from 'sweetalert2';
import { useParams } from 'react-router-dom';
import { putTipoEquipos, getTipoEquipoById } from '../../services/tipoEquipoService';
import { Link } from 'react-router-dom';

export const TipoEquipoUpdate = () => {

    const { tipoEquipoId = '' } = useParams();
    const [tipoEquipo, setTipoEquipo] = useState({});

    const [valoresform, setValoresform] = useState({});
    const { nombre = '', estado = '' } = valoresform;

    const getTipoEquipo = async () => {
        try {
            Swal.fire({
                allowOutsideClick: false,
                title: 'Cargando....',
                text: 'Por favor espere',
                timer: 5000//milisegundos
            });
            Swal.showLoading();
            Swal.close();
            const { data } = await getTipoEquipoById(tipoEquipoId);
            setTipoEquipo(data);
        } catch (error) {
            Swal.close();
            console.log(error);
        }
    }

    useEffect(() => {
        getTipoEquipo();
    }, [tipoEquipoId]);

    useEffect(() => {
        setValoresform({
            nombre: tipoEquipo.nombre,
            estado: tipoEquipo.estado,
        });
    }, [tipoEquipo])

    const handleOnSubmit = async (e) => {
        e.preventDefault();
        const tipoEquipoModel = {
            nombre,
            estado,
        }
        try {
            console.log(tipoEquipoModel);
            Swal.fire({
                allowOutsideClick: false, title: 'Cargando....', text: 'Por favor espere', timer: 2000//milisegundos
            });
            const { data } = await putTipoEquipos(tipoEquipoId, tipoEquipoModel);
            console.log(data);
            Swal.close();
        } catch (error) {
            Swal.fire('Error', 'hubo un error...', 'error')
            console.log("error al crear el tipoEquipo");
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
                    <h5>TipoEquipos</h5>
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
                                    <h5>Actualizar TipoEquipo</h5>
                                </div>
                                <div className='row'>
                                    <div className='col-md-4'>
                                        <div className='mb-3'>
                                            <label className='form-label' for='nombreid'>Nombre</label>
                                            <input className='form-control' type="text" name="nombre" value={nombre} id='nombreid' required
                                                onChange={(e) => {
                                                    handleOnChange(e);
                                                }}
                                            />
                                        </div>
                                    </div>
                                    <div className='col-md-4'>
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
                                        <Link to={`../../tipos`}>
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

